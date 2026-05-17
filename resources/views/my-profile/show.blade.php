<x-default-layout>
    <x-slot:title>
        Mon Profil
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white border-4 border-black p-8 text-center">
        <header class="mb-8">
            <div class="inline-block relative mb-6">
                <div
                    class="w-40 h-40 rounded-full overflow-hidden border-4 border-black bg-yellow-300 flex items-center justify-center">
                    @if ($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->username }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-6xl font-black">{{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}</span>
                    @endif
                </div>
            </div>

            <h1 class="text-5xl font-black uppercase">
                {{ $user->first_name }} {{ $user->last_name }}
            </h1>
            <p class="text-2xl text-black/70 font-bold">
                {{ '@' . $user->username }}
            </p>
        </header>

        <div class="border-t-4 border-b-4 border-black py-6 mb-8">
            <p class="text-lg font-bold">
                <span class="font-black uppercase">Email :</span> {{ $user->email }}
            </p>
            <p class="text-lg font-bold mt-2">
                <span class="font-black uppercase">Membre depuis :</span> {{ $user->created_at->isoFormat('LL') }}
            </p>
        </div>

        <footer class="flex flex-wrap justify-center gap-4">
            <a href="{{ url('/my-profile/edit') }}"
                class="px-6 py-3 bg-pink-400 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all">
                Modifier mon profil
            </a>
            <a href="{{ url('/tokens') }}"
                class="px-6 py-3 bg-yellow-300 text-black font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all">
                Gérer mes tokens
            </a>
            <form method="POST" action="{{ url('/auth/logout') }}">
                @csrf
                <button type="submit"
                    class="w-full px-6 py-3 bg-black text-white font-black uppercase border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all">
                    Déconnexion
                </button>
            </form>
        </footer>
    </div>
</x-default-layout>
