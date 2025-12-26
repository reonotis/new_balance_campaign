<x-Base-layout>

    <x-slot name="page_title">
        <h2 class="text-center text-3xl font-bold leading-tight">
            [Brian Blakely Customs]ライブカスタマイズ体験　ご予約フォーム
        </h2>
    </x-slot>
    <x-slot name="script">
        <link rel="stylesheet" href="{{ asset('css/harajuku_anniversary.css') }}?<?= date('YmdHis') ?>">
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="p-6 bg-white">
                        <div class="card-header mb-4">
                            ご予約が完了しました。
                        </div>
                        <div class="card-body">
                            入力したメールアドレスに通知メールを送信しておりますのでご確認ください。
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-Base-layout>
