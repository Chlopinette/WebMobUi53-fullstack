<x-default-layout>
    <x-slot:title>
        Inscription
    </x-slot>

    <div class="max-w-md mx-auto bg-white border-4 border-black p-8">
        <header class="mb-8 text-center">
            <h1 class="text-4xl font-black uppercase">
                Créer un compte
            </h1>
            <p class="mt-2 text-black/70 font-bold">
                Rejoignez la communauté !
            </p>
        </header>

        <form method="POST" action="{{ url('/auth/register') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-6">
                    <label for="first_name" class="block text-lg font-black uppercase mb-2">
                        Prénom
                    </label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required
                        placeholder="Jean"
                        class="w-full p-4 border-4 border-black text-lg font-bold focus:outline-none focus:ring-4 focus:ring-yellow-300 @error('first_name') ring-4 ring-red-500 @enderror">
                    @error('first_name')
                        <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="last_name" class="block text-lg font-black uppercase mb-2">
                        Nom
                    </label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                        placeholder="Dupont"
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
                <input id="username" type="text" name="username" value="{{ old('username') }}" required
                    placeholder="jeandupont"
                    class="w-full p-4 border-4 border-black text-lg font-bold focus:outline-none focus:ring-4 focus:ring-yellow-300 @error('username') ring-4 ring-red-500 @enderror">
                @error('username')
                    <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-lg font-black uppercase mb-2">
                    Adresse Email
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    placeholder="vous@exemple.com"
                    class="w-full p-4 border-4 border-black text-lg font-bold focus:outline-none focus:ring-4 focus:ring-yellow-300 @error('email') ring-4 ring-red-500 @enderror">
                @error('email')
                    <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-lg font-black uppercase mb-2">
                    Mot de passe
                </label>
                <input id="password" type="password" name="password" required
                    placeholder="Choisissez un mot de passe"
                    class="w-full p-4 border-4 border-black text-lg font-bold focus:outline-none focus:ring-4 focus:ring-yellow-300 @error('password') ring-4 ring-red-500 @enderror">
                @error('password')
                    <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="password_confirmation" class="block text-lg font-black uppercase mb-2">
                    Confirmer le mot de passe
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    placeholder="Confirmez votre mot de passe"
                    class="w-full p-4 border-4 border-black text-lg font-bold focus:outline-none focus:ring-4 focus:ring-yellow-300">
            </div>

            <footer class="flex flex-col gap-4">
                <button type="submit"
                    class="w-full px-6 py-4 bg-pink-400 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all">
                    S'inscrire
                </button>

                <p class="text-center font-bold">
                    Déjà un compte ?
                    <a href="{{ url('/auth/login') }}" class="text-pink-500 hover:underline">
                        Connectez-vous
                    </a>
                </p>
            </footer>
        </form>
    </div>
</x-default-layout>
