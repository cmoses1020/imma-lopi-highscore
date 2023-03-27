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
            <div>
                <a href="{{ route('home') }}" class="flex uppercase font-medium text-sm items-center justify-center text-lopi-purple-700 hover:text-lopi-purple-500">Home</a>
            </div>
            
            <div class="flex space-y-4 items-center">
                <div class="flex text-3xl">
                    <div>
                        <x-fa-brands-fa-discord class="transition duration-300 hover:scale-150" />
                    </div>

                    <a title="twitter.com/Punkalopi" class="block transition duration-300 hover:scale-150" href="https://twitter.com/Punkalopi" target="_blank">
                        🐦
                    </a>
                </div>
                <a href="{{ route('about') }}" class="flex uppercase font-medium text-sm items-center justify-center text-lopi-purple-700 hover:text-lopi-purple-500">About Us</a>
            </div>
        </div>
        @yield('body')
        @stack('scripts')
    </body>
</html>
