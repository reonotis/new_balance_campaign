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

    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">

                <img class="banner-image" src="{{ asset('img/banner/grey-days-nishi-azabu.jpg' ) }}?date={{ date('YmdHi') }}" alt="">

                <div class="form-area">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="session-error">
                            <div class="font-medium text-red-600">下記エラーをご確認下さい</div>
                            <ul class="mt-3 list-disc text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('nishiazabu.store')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class='item-row'>
                            <label for="f_read" class="item-title">注意事項</label>
                            <div class="item-content" style="text-align: center; padding:1rem 0;">
                                イベント当日、商品の販売はありません
                            </div>
                        </div>

                        <x-form_items.name/>

                        <div class='item-row'>
                            <label for="f_read" class="item-title">ヨミ（アルファベット）</label>
                            <div class="item-content">
                                <div class='flex'>
                                    <div class="w-full px-2">
                                        <input type='text' name='f_read' id='f_read' value='{{ old("f_read") }}'
                                               placeholder='Tanaka Taro'>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <x-form_items.nbid/>

                        <x-form_items.sex/>

                        <x-form_items.age/>

                        <x-form_items.address/>

                        <x-form_items.tel/>

                        <x-form_items.email/>

                        <div class='item-row'>
                            <label for="comment" class="item-title">ご自身が最も気に入っているグレーシューズの品番とその理由を教えてください。</label>
                            <div class="item-content">
                                <div class="w-full px-2">
                                    <textarea type='text' name='comment' id='comment' class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class='item-row'>
                            <label for="comment2" class="item-title">初めてご購入いただいたニューバランスのシューズを教えてください。</label>
                            <div class="item-content">
                                <div class="w-full px-2">
                                    <textarea type='text' name='comment2' id='comment2' class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class='item-row'>
                            <label for="comment3" class="item-title">これから履いてみたい、購入を考えているシューズ/アパレルがあれば、教えてください。</label>
                            <div class="item-content">
                                <div class="w-full px-2">
                                    <textarea type='text' name='comment3' id='comment3' class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="p-2 w-full mt-4 flex justify-around">
                            <button type="submit" onclick="return applyConfirm()" class="submit-btn">申し込む</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script language="javascript" type="text/javascript">
        function applyConfirm() {
            return (window.confirm('申し込みを行ってもよろしいですか？')) ? true : false;
        }
    </script>
</x-Base-layout>
