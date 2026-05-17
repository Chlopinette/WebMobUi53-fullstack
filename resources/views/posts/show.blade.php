<x-default-layout>
    <x-slot:title>
        {{ $post->title ?? 'Article' }}
    </x-slot>

    <article class="bg-white border-4 border-black">
        <header class="p-4 md:p-8 border-b-4 border-black">
            @if ($post->title)
                <h1 class="text-3xl md:text-5xl font-black uppercase leading-tight tracking-tight mb-4">
                    {{ $post->title }}
                </h1>
            @endif

            <div class="flex items-center gap-4">
                <a href="{{ url('@' . $post->user->username) }}" class="block">
                    <div
                        class="h-14 w-14 rounded-full overflow-hidden border-2 border-black bg-gray-200 flex items-center justify-center">
                        @if ($post->user->profile_picture)
                            <img src="{{ asset('storage/' . $post->user->profile_picture) }}"
                                alt="{{ $post->user->username }}" class="w-full h-full object-cover">
                        @else
                             <span class="text-xl font-black">{{ strtoupper(substr($post->user->first_name, 0, 1) . substr($post->user->last_name, 0, 1)) }}</span>
                        @endif
                    </div>
                </a>
                <div>
                    <a href="{{ url('@' . $post->user->username) }}" class="hover:underline">
                        <p class="font-black text-xl">
                            {{ $post->user->first_name }} {{ $post->user->last_name }}
                        </p>
                    </a>
                    <p class="text-sm text-black/60 font-bold" title="{{ $post->created_at->isoFormat('LLLL') }}">
                        Publié {{ $post->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </header>

        <div class="p-4 md:p-8 text-lg prose prose-lg max-w-none prose-p:font-serif prose-p:text-black/80 prose-headings:font-black prose-headings:uppercase">
            {!! nl2br(e($post->content)) !!}
        </div>

        <footer class="p-4 md:p-8 border-t-4 border-black bg-yellow-300">
            @auth
                <div class="mb-6">
                    <h3 class="text-xl font-black uppercase mb-3">Votre réaction</h3>
                    <form method="POST" action="{{ url('/likes/' . $post->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="flex flex-wrap gap-3">
                            @php
                                $reactions = ['like' => '👍', 'love' => '❤️', 'haha' => '😂', 'wow' => '😮', 'sad' => '😢', 'angry' => '😡'];
                            @endphp
                            @foreach ($reactions as $key => $emoji)
                                <button type="submit" name="reaction" value="{{ $key }}"
                                    class="text-3xl p-2 rounded-lg transition-transform hover:scale-125 {{ $reaction === $key ? 'ring-4 ring-black' : '' }}">
                                    {{ $emoji }}
                                </button>
                            @endforeach
                        </div>
                    </form>
                </div>
            @endauth

            <div>
                <h3 class="text-xl font-black uppercase mb-4">
                    {{ trans_choice('ui.posts.likes_count', count($post->likes)) }}
                </h3>
                <ul class="flex flex-wrap gap-4">
                    @forelse ($post->likes as $user)
                        <li class="flex items-center gap-2 text-sm font-bold">
                            <a href="{{ url('@' . $user->username) }}" class="flex items-center gap-2 hover:underline">
                                <div class="h-8 w-8 rounded-full overflow-hidden border-2 border-black bg-gray-200">
                                     @if ($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->username }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs font-black flex items-center justify-center w-full h-full">{{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <span>{{ '@' . $user->username }}</span>
                            </a>
                            <span class="text-xl">
                                {{ $reactions[$user->pivot->reaction] ?? '' }}
                            </span>
                        </li>
                    @empty
                        <li class="text-black/60 font-bold">
                            Soyez le premier à réagir !
                        </li>
                    @endforelse
                </ul>
            </div>
        </footer>
    </article>
</x-default-layout>
