<x-Base-layout>

    <x-slot name="page_title">
        <h2 class="text-center text-3xl font-bold leading-tight">
            TENJIN RUNNERS GATE<br class="brSp2"> スペシャルイベント <br class="brSp2">応募フォーム
        </h2>
    </x-slot>
    <x-slot name="script">
{{--        <link rel="stylesheet" href="{{ asset('css/nagasaki_opening.css') }}?<?= date('YmdHis') ?>">--}}
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="form-area">
                    <div class="event-explanation">
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">スーパースポーツゼビオ 福岡天神店ランニングクラブ</div>
                            <div class="event-explanation-title">TENJIN RUNNERS GATE（テンジン ランナーズ ゲート）スペシャルイベント</div>
                        </div>
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">日時</div>
                            <div class="event-explanation-content">1/26（日）9:30-13:30</div>
                        </div>
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">場所</div>
                            <div class="event-explanation-content">XEBIO福岡天神店</div>
                        </div>
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">内容</div>
                            <div class="event-explanation-content">
                                大濠公園にてトレーニングやランニングの後、参加者の皆さんと大濠公園近くのカフェでコーチと一緒にランチをします。
                            </div>
                        </div>
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">参加費</div>
                            <div class="event-explanation-content">無料</div>
                        </div>
                    </div>

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

                    <form action="{{route('super-sports-tenjin-runners-club.store')}}" method="post" >
                        @csrf
                        <div class="item-row">
                            <label for="f_name" class="item-title">お名前</label>
                            <div class="item-content">
                                <div class="flex">
                                    <div class="w-1/2 px-2">
                                        <input type="text" name="f_name" id="f_name" value="{{ old("f_name") }}"
                                               placeholder="田中">
                                    </div>
                                    <div class="w-1/2 px-2">
                                        <input type="text" name="l_name" value="{{ old("l_name") }}" placeholder="太郎">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='item-row'>
                            <label for="f_read" class="item-title">ヨミ</label>
                            <div class="item-content">
                                <div class='flex'>
                                    <div class="w-1/2 px-2">
                                        <input type='text' name='f_read' id='f_read' value='{{ old("f_read") }}'
                                               placeholder='タナカ'>
                                    </div>
                                    <div class="w-1/2 px-2">
                                        <input type='text' name='l_read' value='{{ old("l_read") }}' placeholder='タロウ'>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='item-row'>
                            <label for="age" class="item-title">年齢</label>
                            <div class="item-content">
                                <div class='flex'>
                                    <div class="w-1/2 px-2">
                                        <input type='number' name='age' id='age' value='{{ old("age") }}' min='1' placeholder="30" style="width: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='item-row'>
                            <label for="zip21" class="item-title">住所</label>
                            <div class='item-content'>
                                <div class="flex justify-start items-center mb-1">
                                    <div class="px-2 w-32">
                                        <input type='text' name='zip21' id='zip21' value='{{ old("zip21") }}'
                                               placeholder='101'>
                                    </div>
                                    <div class="px-1">-</div>
                                    <div class="px-2 w-32">
                                        <input type='text' name='zip22' value='{{ old("zip22") }}'
                                               onKeyUp='AjaxZip3.zip2addr("zip21","zip22","pref21","address21","street21");'
                                               placeholder='0051'>
                                    </div>
                                </div>
                                <div class="flex mb-1">
                                    <div class="px-2 w-48">
                                        <input type='text' name='pref21' value='{{ old("pref21") }}' class='Prefectures'
                                               placeholder='東京都'>
                                    </div>
                                    <div class="px-2 w-48">
                                        <input type='text' name='address21' value='{{ old("address21") }}'
                                               class='Municipality' placeholder='千代田区'>
                                    </div>
                                </div>
                                <div class="flex px-2">
                                    <input type="text" name="street21" value="{{ old("street21") }}"
                                           class="form-control" placeholder="神田神保町1-105">
                                </div>
                            </div>
                        </div>
                        <div class="item-row">
                            <label for="tel" class="item-title">電話番号</label>
                            <div class="item-content">
                                <p class="mail_supplement mb-1">※ハイフン（-）を入れて入力してください。</p>
                                <div class="flex w-72 px-2">
                                    <input type="text" name="tel" id="tel" value="{{ old("tel") }}" class="form-control"
                                           placeholder="03-5577-2300">
                                </div>
                            </div>
                        </div>
                        <div class="item-row">
                            <label for="email" class="item-title">メールアドレス</label>
                            <div class="item-content px-2">
                                <p class="mail_supplement mb-1">※メールアドレスは確認用の為、2回入力してください</p>
                                <p class="mail_supplement mb-1">
                                    ※docomo、au、softbank、iCloud等の各キャリアのメールアドレスは、ドメイン指定受信を設定されている可能性がありメールが正しく届かない事がある為、PC用のメールアドレスを記入する事をお勧め致します。</p>
                                <div class="">
                                    <div class="w-full mb-1">
                                        <input type="email" name="email" id="email" value="{{ old("email") }}"
                                               class="form-control" placeholder="sample@newbalance.co.jp">
                                    </div>
                                    <div class="w-full">
                                        <input type="email" name="email_confirmation" value="{{ old("email_confirmation") }}"
                                               class="form-control" placeholder="sample@newbalance.co.jp(確認用)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="item-row">
                            <label for="" class="item-title">どこでイベントについて知りましたか</label>
                            <div class="item-content px-2">
                                <div class="flex" style="flex-wrap: wrap;gap: .5rem;">
                                    @foreach(App\Consts\SSXFukuokaTenjinConst::HOW_FOUND as $value => $label)
                                        <label class="radio-label" style="padding: 0 .5rem;">
                                            <input type="radio" class="" name="how_found" value="{{ $value }}"
                                                   @if(old('how_found') == $value)
                                                       checked="checked"
                                                @endif
                                            >
                                            {{ $label }}
                                        </label>
                                    @endforeach
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
