<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    @isset($description)
        <meta name="description" content="{{ $description }}">
    @endisset
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @isset($title)
        <title>{{ $title }} - {{ config('app.name') }}</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endisset

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css'])
    @isset($scripts)
        {{ $scripts }}
    @endisset
</head>

<body class="flex min-h-screen flex-col bg-yellow-300 text-black font-sans">
    <header class="bg-white border-b-4 border-black">
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-20 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ url('/') }}" class="text-3xl font-black uppercase tracking-tighter">
                        {{ config('app.name') }}
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/posts') }}" class="font-bold uppercase hover:text-pink-500">Posts</a>
                        <a href="{{ route('polls.dashboard') }}" class="font-bold uppercase hover:text-pink-500">Sondages</a>
                        <a href="{{ url('/my-profile') }}" class="block hover:opacity-80 transition">
                            <div
                                class="h-10 w-10 rounded-full overflow-hidden border-2 border-black bg-gray-200 flex items-center justify-center">
                                @if (Auth::user()->profile_picture)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                        alt="{{ Auth::user()->username }}" class="w-full h-full object-cover">
                                @else
                                    <img src="/icons/profile.svg" alt="{{ Auth::user()->username }}" class="h-10 w-10">
                                @endif
                            </div>
                        </a>
                    @else
                        <a href="{{ url('/auth/login') }}"
                            class="font-bold uppercase hover:text-pink-500">
                            {{ __('ui.auth.login.title') }}
                        </a>
                        <a href="{{ url('/auth/register') }}"
                            class="px-4 py-2 bg-pink-400 text-black font-black uppercase border-2 border-black shadow-[2px_2px_0px_black] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                            {{ __('ui.auth.register.title') }}
                        </a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 py-8 sm:px-6 lg:px-8 flex-grow max-w-4xl">
        {{ $slot }}
    </main>

    <footer class="bg-white border-t-4 border-black text-sm">
        <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <p class="font-bold">
                    {{ __('ui.about.copyright', ['year' => date('Y')]) }}
                </p>
                <a href="{{ url('/about') }}" class="font-bold uppercase hover:text-pink-500">
                    {{ __('ui.about.title') }}
                </a>
            </div>
        </div>
    </footer>
</body>

</html>
