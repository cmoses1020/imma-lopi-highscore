<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @hasSection('title')

            <title>@yield('title') - {{ config('app.name') }}</title>
        @else
            <title>{{ config('app.name') }}</title>
        @endif

        <!-- Favicon -->
		<link rel="shortcut icon" href="{{ url(asset('favicon.ico')) }}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @livewireScripts

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="bg-pink-pattern bg-repeat">
        <div class="px-8 py-4 flex justify-between w-full">
            <div class="flex space-x-6 items-center">
                <a href="{{ route('home') }}" class="block transition duration-300 uppercase font-medium text-sm items-center justify-center text-lopi-purple-700 hover:text-lopi-purple-500 hover:scale-110">Home</a>
                <a href="{{ route('high-score') }}" class="block transition duration-300 uppercase font-medium text-sm items-center justify-center text-lopi-purple-700 hover:text-lopi-purple-500 hover:scale-110">High Scores</a>
            </div>
            
            <div class="flex space-x-6 items-center">
                <div>
                <a href="{{ route('about') }}" class="block transition duration-300 uppercase font-medium text-sm items-center justify-center text-lopi-purple-700 hover:text-lopi-purple-500 hover:scale-110">About Us</a>
                </div>

                <a href="https://www.youtube.com/@Punkalopi" target="_blank" class="block transition duration-300 hover:scale-150">
                    <x-fab-youtube class="text-lopi-purple-700 hover:text-lopi-purple-500 w-10 h-10 transition duration-300 hover:scale-110" />
                </a>

                <a href="https://discord.com/invite/ZBck86Wr79" target="_blank" class="block transition duration-300 hover:scale-150">
                    <x-fab-discord class="text-lopi-purple-700 w-10 h-10 transition duration-300 hover:text-lopi-purple-500 hover:scale-110" />
                </a>

                <a title="twitter.com/Punkalopi" class="block transition duration-300 hover:scale-150" href="https://twitter.com/Punkalopi" target="_blank">
                    <x-fab-twitter class="text-lopi-purple-700 w-10 h-10 transition duration-300 hover:text-lopi-purple-500 hover:scale-110" />
                </a>
            </div>
        </div>
        @yield('body')
        @stack('scripts')
    </body>
</html>
