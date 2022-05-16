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
        <link rel="stylesheet" href="{{ asset('css/try_on.css') }}?<?= date('YmdHis') ?>">
        <link rel="shortcut icon" href="{{ asset('img/logo/favicon.ico') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/try_on.js') }}" defer></script>
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>	<!-- 住所入力 -->
        <script src="http://code.jquery.com/jquery-1.9.0.js" type="text/javascript" ></script>


    </head>
    <body class="antialiased">
        <div class="min-h-screen ">

            <!-- Page Heading -->
            <header class="">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 md:pt-24 md:pb-12">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
