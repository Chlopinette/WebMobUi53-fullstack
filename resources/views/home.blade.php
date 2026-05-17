<x-default-layout>
    <x-slot:title>
        Accueil
    </x-slot>

    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-6xl font-black text-black uppercase leading-none tracking-tighter">
            Le Blog de la Communauté
        </h1>
        <p class="mt-4 text-lg md:text-xl font-bold text-black/70">
            Partagez vos idées, découvrez de nouvelles perspectives.
        </p>
    </div>

    <h2 class="text-center text-2xl md:text-3xl font-black text-black uppercase mb-8">
        Derniers Articles
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($posts as $post)
            <x-post-card :post="$post" />
        @endforeach
    </div>

    <div class="text-center mt-12">
        <a href="{{ url('/posts') }}"
            class="inline-block px-8 py-4 bg-pink-400 text-black font-black uppercase border-4 border-black shadow-[6px_6px_0px_black] hover:shadow-none hover:translate-x-[6px] hover:translate-y-[6px] transition-all">
            Voir tous les articles
        </a>
    </div>
</x-default-layout>
