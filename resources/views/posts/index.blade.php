<x-default-layout>
    <x-slot:title>
        Tous les articles
    </x-slot>

    <div class="flex justify-between items-center mb-12">
        <h1 class="text-5xl font-black text-black uppercase leading-none tracking-tighter">
            Tous les Articles
        </h1>
        @can('create', App\Models\Post::class)
            <a href="{{ url('/posts/create') }}"
                class="px-6 py-3 bg-pink-400 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all">
                Écrire un article
            </a>
        @endcan
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($posts as $post)
            <x-post-card :post="$post" />
        @endforeach
    </div>

    <div class="mt-12">
        {{ $posts->links() }}
    </div>
</x-default-layout>
