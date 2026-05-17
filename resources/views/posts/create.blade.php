<x-default-layout>
    <x-slot:title>
        Écrire un nouvel article
    </x-slot>

    <div class="bg-white border-4 border-black p-8">
        <header class="mb-8 text-center">
            <h1 class="text-4xl font-black uppercase">
                Nouvel Article
            </h1>
            <p class="mt-2 text-black/70 font-bold">
                Partagez vos pensées avec la communauté.
            </p>
        </header>

        <form method="POST" action="{{ url('/posts') }}">
            @csrf

            <div class="mb-6">
                <label for="title" class="block text-lg font-black uppercase mb-2">
                    Titre de l'article
                </label>
                <input id="title" type="text" name="title" value="{{ old('title') }}"
                    placeholder="Un titre accrocheur..."
                    class="w-full p-4 border-4 border-black text-lg font-bold focus:outline-none focus:ring-4 focus:ring-yellow-300 @error('title') ring-4 ring-red-500 @enderror">
                @error('title')
                    <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="content" class="block text-lg font-black uppercase mb-2">
                    Contenu
                </label>
                <textarea id="content" name="content" rows="10"
                    placeholder="Écrivez votre histoire ici..."
                    class="w-full p-4 border-4 border-black font-serif text-lg focus:outline-none focus:ring-4 focus:ring-yellow-300 @error('content') ring-4 ring-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <footer class="flex items-center justify-end gap-4">
                <a href="{{ url('/posts') }}"
                    class="px-6 py-3 bg-gray-200 text-black font-black uppercase border-4 border-black">
                    Annuler
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-pink-400 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all">
                    Publier l'article
                </button>
            </footer>
        </form>
    </div>
</x-default-layout>
