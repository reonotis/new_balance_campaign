<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?<?= date('YmdHis') ?>">
        <link rel="stylesheet" href="{{ asset('css/flash.css') }}?<?= date('YmdHis') ?>">
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}?<?= date('YmdHis') ?>">

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript" ></script>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/admin.js') }}?<?= date('YmdHis') ?>" type="text/javascript" defer></script>

        @if(isset($head))
            {{ $head }}
        @endif
    </head>
    <body class="antialiased">
        <div class="min-h-screen ">
            @include('layouts.admin_navigation')

            <!-- Page Heading -->
            <header class="">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 md:pt-24 md:pb-12">
                    {{ $header }}
                </div>
            </header>

            @include('layouts.flash_message')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
