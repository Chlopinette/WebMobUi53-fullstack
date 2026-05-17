<x-default-layout>
    <x-slot:title>
        Connexion
    </x-slot>

    <div class="max-w-md mx-auto bg-white border-4 border-black p-8">
        <header class="mb-8 text-center">
            <h1 class="text-4xl font-black uppercase">
                Connexion
            </h1>
            <p class="mt-2 text-black/70 font-bold">
                Heureux de vous revoir !
            </p>
        </header>

        <form method="POST" action="{{ url('/auth/login') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-lg font-black uppercase mb-2">
                    Adresse Email
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
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
                    placeholder="Votre mot de passe"
                    class="w-full p-4 border-4 border-black text-lg font-bold focus:outline-none focus:ring-4 focus:ring-yellow-300 @error('password') ring-4 ring-red-500 @enderror">
                @error('password')
                    <p class="mt-2 text-red-600 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                        class="h-6 w-6 border-4 border-black accent-pink-400">
                    <span class="ml-3 text-lg font-bold uppercase">
                        Se souvenir de moi
                    </span>
                </label>
            </div>

            <footer class="flex flex-col gap-4">
                <button type="submit"
                    class="w-full px-6 py-4 bg-pink-400 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all">
                    Se connecter
                </button>

                <p class="text-center font-bold">
                    Pas encore de compte ?
                    <a href="{{ url('/auth/register') }}" class="text-pink-500 hover:underline">
                        Inscrivez-vous
                    </a>
                </p>
            </footer>
        </form>
    </div>
</x-default-layout>
