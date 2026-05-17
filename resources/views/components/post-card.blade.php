<article class="bg-white border-4 border-black shadow-[8px_8px_0px_black] flex flex-col">
    <header class="p-6 border-b-4 border-black">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ url('@' . $post->user->username) }}" class="block">
                <div
                    class="h-12 w-12 rounded-full overflow-hidden border-2 border-black bg-gray-200 flex items-center justify-center">
                    @if ($post->user->profile_picture)
                        <img src="{{ asset('storage/' . $post->user->profile_picture) }}"
                            alt="{{ $post->user->username }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-lg font-black">{{ strtoupper(substr($post->user->first_name, 0, 1) . substr($post->user->last_name, 0, 1)) }}</span>
                    @endif
                </div>
            </a>
            <div>
                <a href="{{ url('@' . $post->user->username) }}" class="hover:underline">
                    <p class="font-black text-lg">
                        {{ $post->user->first_name }} {{ $post->user->last_name }}
                    </p>
                </a>
                <p class="text-sm text-black/60 font-bold" title="{{ $post->created_at->isoFormat('LLLL') }}">
                    {{ $post->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
        @if ($post->title)
            <a href="{{ url('/posts/' . $post->id) }}">
                <h2 class="text-2xl font-black uppercase leading-tight">
                    {{ $post->title }}
                </h2>
            </a>
        @endif
    </header>

    <div class="p-6 flex-grow">
        <a href="{{ url('/posts/' . $post->id) }}">
            <p class="text-black/80">
                {{ Str::limit($post->content, 150) }}
            </p>
        </a>
    </div>

    <footer class="p-6 border-t-4 border-black bg-yellow-300">
        <div class="flex items-center justify-between">
            <span class="font-black uppercase text-sm">
                {{ trans_choice('ui.posts.likes_count', count($post->likes)) }}
            </span>
            <a href="{{ url('/posts/' . $post->id) }}"
                class="px-4 py-2 bg-black text-white font-black uppercase text-sm border-2 border-black hover:bg-pink-500 hover:text-black transition-all">
                Lire la suite
            </a>
        </div>
    </footer>
</article>
