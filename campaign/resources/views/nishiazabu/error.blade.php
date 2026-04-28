<x-Base-layout>

    <x-slot name="page_title">
        <h2 class="text-center text-3xl font-bold leading-tight">
            GREY ART MUSEUM 2026 myNB Members Day
            <br>
            応募フォーム
        </h2>
    </x-slot>
    <x-slot name="script">
        <link rel="stylesheet" href="{{ asset('css/kichijoji_grey_days_5k_runn.css') }}?<?= date('YmdHis') ?>">
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="p-6 bg-white">
                        <div class="card-body">
                            {{ $errorMessage }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-Base-layout>
