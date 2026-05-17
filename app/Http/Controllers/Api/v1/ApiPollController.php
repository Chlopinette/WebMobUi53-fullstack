<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiPollController extends Controller
{
    /**
     * Display a listing of the authenticated user's polls.
     */
    public function index(Request $request)
    {
        $polls = $request->user()
            ->polls()
            ->with('options')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($polls);
    }

    /**
     * Store a newly created poll in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question'               => 'required|string|max:255',
            'title'                  => 'nullable|string|max:255',
            'options'                => 'required|array|min:2',
            'options.*'              => 'required|string|max:255',
            'allow_multiple_choices' => 'sometimes|boolean',
            'results_public'         => 'sometimes|boolean',
            'duration'               => 'nullable|integer|min:1',
        ]);

        $duration = $validated['duration'] ?? null;
        $startedAt = now();
        $endsAt = $duration ? $startedAt->copy()->addSeconds($duration) : null;

        $poll = $request->user()->polls()->create([
            'question'               => $validated['question'],
            'title'                  => $validated['title'] ?? null,
            'secret_token'           => Str::random(32),
            'is_draft'               => false, // Toujours lancé directement
            'allow_multiple_choices' => $validated['allow_multiple_choices'] ?? false,
            'results_public'         => $validated['results_public'] ?? false,
            'duration'               => $duration,
            'started_at'             => $startedAt,
            'ends_at'                => $endsAt,
        ]);

        foreach ($validated['options'] as $optionText) {
            $poll->options()->create(['text' => $optionText]);
        }

        return response()->json($poll->load('options'), 201);
    }

    /**
     * Update the specified poll in storage.
     */
    public function update(Request $request, int $id)
    {
        $poll = $request->user()->polls()->find($id);

        if (!$poll) {
            return response()->json(['message' => 'Poll not found.'], 404);
        }

        $validated = $request->validate([
            'question'               => 'sometimes|required|string|max:255',
            'title'                  => 'nullable|string|max:255',
            'is_draft'               => 'sometimes|boolean',
            'allow_multiple_choices' => 'sometimes|boolean',
            'results_public'         => 'sometimes|boolean',
            'duration'               => 'nullable|integer|min:1',
            'ends_at'                => 'nullable|date|after:now',
        ]);

        // Si on lance le sondage depuis brouillon
        if (isset($validated['is_draft'])
            && $validated['is_draft'] === false
            && $poll->is_draft === true
        ) {
            $validated['started_at'] = now();

            // Calcule ends_at depuis duration si présente et pas encore de ends_at
            if ($poll->duration && !$poll->ends_at) {
                $validated['ends_at'] = now()->addSeconds($poll->duration);
            }
        }

        $poll->update($validated);

        return response()->json($poll->fresh(['options']));
    }

    /**
     * Display the specified poll by its secret token.
     */
    public function show(Request $request, string $token)
    {
        $poll = Poll::with(['options' => function ($query) {
            $query->withCount('votes');
        }, 'myVote'])->where('secret_token', $token)->first();

        if (!$poll) {
            return response()->json(['message' => 'Poll not found.'], 404);
        }

        // Vérifie si le sondage est terminé
        $isExpired = $poll->ends_at && now('UTC')->gt($poll->ends_at->utc());

        return response()->json([
            'id'                     => $poll->id,
            'question'               => $poll->question,
            'title'                  => $poll->title,
            'is_draft'               => $poll->is_draft,
            'allow_multiple_choices' => $poll->allow_multiple_choices,
            'results_public'         => $poll->results_public,
            'secret_token'           => $poll->secret_token,
            'started_at'             => $poll->started_at,
            'ends_at'                => $poll->ends_at,
            'is_expired'             => $isExpired,
            'has_voted'              => $poll->myVote !== null,
            'user_vote_option_id'    => $poll->myVote->poll_option_id ?? null,
            'options'                => $poll->options,
        ]);
    }

    /**
     * Handle a vote on a poll.
     */
    public function vote(Request $request, string $token)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'You must be logged in to vote.'], 401);
        }
        $poll = Poll::where('secret_token', $token)->first();

        if (!$poll) {
            return response()->json(['message' => 'Poll not found.'], 404);
        }

        if ($poll->is_draft) {
            return response()->json(['message' => 'This poll is not active yet.'], 422);
        }

        if ($poll->ends_at && now()->isAfter($poll->ends_at)) {
            return response()->json(['message' => 'This poll has ended.'], 422);
        }

        // Unicité du vote pour les utilisateurs connectés
        if ($request->user()) {
            $alreadyVoted = PollVote::where('poll_id', $poll->id)
                ->where('user_id', $request->user()->id)
                ->exists();

            if ($alreadyVoted) {
                return response()->json(['message' => 'You have already voted.'], 422);
            }
        }

        if ($poll->allow_multiple_choices) {
            // Choix multiple — on attend un tableau d'option_ids
            $validated = $request->validate([
                'option_ids'   => 'required|array|min:1',
                'option_ids.*' => 'required|integer|exists:poll_options,id',
            ]);

            foreach ($validated['option_ids'] as $optionId) {
                // Vérifie que l'option appartient bien à ce sondage
                $belongs = $poll->options()->where('id', $optionId)->exists();
                if (!$belongs) continue;

                PollVote::create([
                    'poll_id'        => $poll->id,
                    'poll_option_id' => $optionId,
                    'user_id'        => $request->user()?->id,
                ]);
            }
        } else {
            // Choix unique
            $validated = $request->validate([
                'option_id' => 'required|integer|exists:poll_options,id',
            ]);

            $belongs = $poll->options()->where('id', $validated['option_id'])->exists();
            if (!$belongs) {
                return response()->json(['message' => 'Invalid option for this poll.'], 422);
            }

            PollVote::create([
                'poll_id'        => $poll->id,
                'poll_option_id' => $validated['option_id'],
                'user_id'        => $request->user()?->id,
            ]);
        }

        return response()->json(['message' => 'Vote cast successfully.']);
    }

    public function remove(Request $request, int $id)
    {
        $poll = Poll::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$poll) {
            return response()->json(['message' => 'Poll not found.'], 404);
        }

        $poll->delete();

        return response()->json(['message' => 'success'], 200);
    }
}
