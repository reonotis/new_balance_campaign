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
    <link rel="stylesheet" href="{{ asset('css/go_fun.css') }}?<?= date('YmdHis') ?>">
    <link rel="shortcut icon" href="{{ asset('img/logo/favicon.ico') }}">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>    <!-- 住所入力 -->
    <script src="{{ asset('js/app.js') }}" defer></script>

</head>
<body class="antialiased">
<div class="min-h-screen ">

    <!-- Page Heading -->
    <header class="">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 md:pt-24 md:pb-12">

            <div class="w-full flex justify-center mb-2 ">
                <div class="w-32 mb-4">
                    <svg id="レイヤー_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                         y="0px" viewBox="0 0 146 80" style="enable-background:new 0 0 146 80;" xml:space="preserve">
                        <style type="text/css">
                            .st0 {
                                fill: #fff;
                            }
                        </style>
                        <path class="st0" d="M29.5,36.3l-2.4,4.2l24.2,1.4l4.4-7.6L29.5,36.3z M43.5,12.1L41,16.3l34.4,2.3l-2.2-8.3L43.5,12.1z M48,4.2
                        l24,1.5L70.5,0L50.4,0L48,4.2z M22.6,48.4l-2.4,4.2l25,0l3.5-6L22.6,48.4z M98.3,30.3c1.8,0,4.3,1.8,2.9,5.4
                        c-1.5,3.8-6.3,5.1-8.9,5.1h-5.8l6.1-10.5L98.3,30.3z M104.1,10.3l4.9,0c2.1,0,3.7,2.4,2.5,5.3c-1.3,3.1-4.8,4.6-8.1,4.7l-5,0
                        L104.1,10.3z M75.6,18.6l16,1l-0.6,1.1l-54.4,3.5l-2.4,4.2l50.6,3.2L84,32.6l-28.1,1.6l2.1,8l19.8,1.2l-0.6,1.1l-18.2,1.3l1.7,6.7
                        l34.8,0c8.4,0,23.5-5.5,25.2-17.2c1-6.6-4.7-9.1-7-9.4c10.8-3,14.5-10.3,15.3-13.2C131.5,3.2,124.8,0,115.8,0L86.3,0l-3.7,6.5
                        l15.9,1.1l-0.6,1.1L80.7,9.7L75.6,18.6z M135.2,74.2c0.1,0.9,1.4,2.8,3.8,2.8c1.5,0,2.5-0.6,3.1-1.7h3.4c-0.8,2.5-3.4,4.7-6.6,4.7
                        c-4.1,0-7.2-3.3-7.2-7.2c0-3.9,3-7.4,7.2-7.4c4.3,0,7.1,3.7,7.1,7.2c0,0.6-0.1,1.2-0.2,1.6H135.2z M135.1,71.6h7.6
                        c-0.4-2.2-2.1-3.2-3.7-3.2C137.8,68.4,135.7,69,135.1,71.6 M127.6,75.1c-0.9,1.4-1.9,1.9-3,1.9c-2.5,0-4.1-1.9-4.1-4.3
                        c0-2.8,2-4.3,4-4.3c2,0,2.8,1.3,3.2,2h3.7c-1.4-4.2-5.1-5-6.9-5c-3.8,0-7.2,3.1-7.2,7.2c0,4.5,3.8,7.3,7.3,7.3
                        c3.2,0,5.7-1.8,6.8-4.9H127.6z M103.8,79.6h3.3v-6.5c0-1.2,0-2.6,0.6-3.4c0.6-0.9,1.7-1.2,2.2-1.2c2.8,0,2.8,3.2,2.8,4.4v6.7h3.3
                        v-7.4c0-1.1,0-3.3-1.3-4.8c-1.1-1.4-2.9-1.9-4.1-1.9c-2.1,0-3.2,1.1-3.7,1.6v-1.2h-3V79.6z M102.1,79.6h-3v-1.7
                        C98,79.1,96,80,94.3,80c-3.5,0-6.6-2.8-6.6-7.4c0-4.2,3-7.2,6.8-7.2c2.8,0,4.4,2,4.5,2.2h0v-1.7h3V79.6z M98.9,72.7
                        c0-2.8-2-4.3-4-4.3c-2.7,0-4.1,2.3-4.1,4.4c0,2.3,1.7,4.2,4.1,4.2C97.2,77,98.9,75.2,98.9,72.7 M86.6,61.3h-3.3v18.2h3.3V61.3z
                        M81.6,79.6h-3v-1.7c-1,1.2-3.1,2.1-4.8,2.1c-3.5,0-6.6-2.8-6.6-7.4c0-4.2,3.1-7.2,6.8-7.2c2.8,0,4.4,2,4.5,2.2h0v-1.7h3V79.6z
                        M78.5,72.7c0-2.8-2-4.3-4-4.3c-2.7,0-4.1,2.3-4.1,4.4c0,2.3,1.7,4.2,4.1,4.2C76.8,77,78.5,75.2,78.5,72.7 M52,61.3h3.3v5.8
                        c1.1-1.1,2.7-1.7,4.3-1.7c4.2,0,6.9,3.6,6.9,7.2c0,2.7-1.8,7.3-6.8,7.3c-2.6,0-3.8-1.2-4.6-2.1v1.6h-3V61.3z M55,72.5
                        c0,3,2.2,4.5,4.1,4.5c2.1,0,3.9-1.6,3.9-4.3c0-2.7-1.9-4.3-4-4.3C56.6,68.4,55,70.6,55,72.5 M31.4,79.6h2l2.6-8.9h0l2.6,8.9h2
                        l5-13.7h-3.5l-2.5,8.3h0l-2.2-8.3h-2.6l-2.2,8.3h-0.1L30,65.9h-3.5L31.4,79.6z M16.4,74.2c0.1,0.9,1.4,2.8,3.8,2.8
                        c1.4,0,2.5-0.6,3.1-1.7h3.4C26,77.7,23.4,80,20.2,80c-4.1,0-7.2-3.3-7.2-7.2c0-3.9,3-7.4,7.2-7.4c4.3,0,7.1,3.7,7.1,7.2
                        c0,0.6-0.1,1.2-0.2,1.6H16.4z M16.3,71.6h7.6c-0.4-2.2-2.1-3.2-3.7-3.2C19,68.4,16.9,69,16.3,71.6 M0,79.6V65.9h3v1.2
                        c0.5-0.5,1.7-1.6,3.7-1.6c1.2,0,3,0.5,4.1,1.9c1.3,1.5,1.3,3.8,1.3,4.8v7.4H8.9v-6.7c0-1.1,0-4.4-2.8-4.4c-0.6,0-1.7,0.3-2.2,1.2
                        c-0.6,0.8-0.6,2.3-0.6,3.4v6.5H0z"/>
                    </svg>
                </div>

            </div>

            <h2 class="text-white text-center text-3xl font-bold leading-tight">
                New Balance GO FUN!<br class="brSp2"> キャンペーン応募フォーム
            </h2>
        </div>
    </header>

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>
</body>
</html>
