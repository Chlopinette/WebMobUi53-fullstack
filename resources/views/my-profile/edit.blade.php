<x-default-layout>
    <x-slot:title>
        Modifier mon profil
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white border-4 border-black p-8">
        <header class="mb-8 text-center">
            <h1 class="text-4xl font-black uppercase">
                Modifier mon profil
            </h1>
            <p class="mt-2 text-black/70 font-bold">
                Gardez vos informations à jour.
            </p>
        </header>

        <form method="POST" enctype="multipart/form-data" action="{{ url('/my-profile') }}">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="profile_picture" class="block text-lg font-black uppercase mb-2">
                    Photo de profil
                </label>
                <input type="file" id="profile_picture" name="profile_picture"
                    accept="image/jpeg,image/png,image/bmp,image/gif,image/webp"
                    class="w-full p-3 border-4 border-black text-lg font-bold bg-white
                           file:mr-4 file:py-2 file:px-4
                           file:border-0 file:bg-black file:text-white
                           file:font-black file:uppercase file:cursor-pointer
                           hover:file:bg-pink-500 hover:file:text-black transition-all">
                @error('profile_picture')
                    <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-6">
                    <label for="first_name" class="block text-lg font-black uppercase mb-2">
                        Prénom
                    </label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                        class="w-full p-4 border-4 border-black text-lg font-bold focus:outline-none focus:ring-4 focus:ring-yellow-300 @error('first_name') ring-4 ring-red-500 @enderror">
                    @error('first_name')
                        <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="last_name" class="block text-lg font-black uppercase mb-2">
                        Nom
                    </label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                        class="w-full p-4 border-4 border-black text-lg font-bold focus:outline-none focus:ring-4 focus:ring-yellow-300 @error('last_name') ring-4 ring-red-500 @enderror">
                    @error('last_name')
                        <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="username" class="block text-lg font-black uppercase mb-2">
                    Nom d'utilisateur
                </label>
                <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}"
                    class="w-full p-4 border-4 border-black text-lg font-bold focus:outline-none focus:ring-4 focus:ring-yellow-300 @error('username') ring-4 ring-red-500 @enderror">
                @error('username')
                    <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="email" class="block text-lg font-black uppercase mb-2">
                    Adresse Email
                </label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full p-4 border-4 border-black text-lg font-bold focus:outline-none focus:ring-4 focus:ring-yellow-300 @error('email') ring-4 ring-red-500 @enderror">
                @error('email')
                    <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <footer class="flex items-center justify-between">
                <a href="{{ url('/my-profile') }}"
                    class="px-6 py-3 bg-gray-200 text-black font-black uppercase border-4 border-black">
                    Annuler
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-pink-400 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all">
                    Enregistrer les modifications
                </button>
            </footer>
        </form>

        <div class="mt-12 border-t-4 border-black pt-8">
             <h3 class="text-2xl font-black uppercase text-red-600 mb-4">Zone de danger</h3>
             <p class="mb-4 font-bold">La suppression de votre compte est une action irréversible.</p>
            <form id="delete-profile-form" method="POST" action="{{ url('/my-profile') }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                    onclick="return confirm('Êtes-vous absolument certain de vouloir supprimer votre compte ? Cette action est définitive.')"
                    class="px-6 py-3 bg-red-600 text-white font-black uppercase border-4 border-black">
                    Supprimer mon compte
                </button>
            </form>
        </div>
    </div>
</x-default-layout>
