<x-Base-layout>

    <x-slot name="page_title">
        <h2 class="text-center text-3xl font-bold leading-tight">
            スーパースポーツゼビオ <br class="brSp2">東京御茶ノ水本店ランニングクラブ<br>
            ３０２ RUNNING CLUB <br class="brSp2">Rebel v5 先行試し履きイベント <br class="brSp2">応募フォーム
        </h2>
    </x-slot>
    <x-slot name="script">
        {{--        <link rel="stylesheet" href="{{ asset('css/nagasaki_opening.css') }}?<?= date('YmdHis') ?>">--}}
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="p-6 bg-white" style="border-radius: 25px">
                        <div class="card-body" style="padding: 1rem 0;">
                            <p>現在申し込みはできません</p>
                            {!! $checkMessage !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-Base-layout>
