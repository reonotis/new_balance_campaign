<x-Base-layout>

    <x-slot name="page_title">
        <h2 class="text-center text-3xl font-bold leading-tight">
            [Brian Blakely Customs]ライブカスタマイズ体験　ご予約フォーム
            <br class="brSp2">
        </h2>
    </x-slot>
    <x-slot name="script">
        <link rel="stylesheet" href="{{ asset('css/harajuku_anniversary.css') }}?<?= date('YmdHis') ?>">
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="form-area">
                    <div class="event-explanation">
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">場所</div>
                            <div class="event-explanation-content">ニューバランス原宿　4階　（東京都渋谷区神宮前4-32-16）</div>
                        </div>
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">注意事項</div>
                            <div class="event-explanation-content">
                                ※ご予約の時間の5分前にはニューバランス原宿店4階にご到着ください。<br>
                                ※ご予約時間から15分経過した場合は自動キャンセルとさせていただきますのでくれぐれもご注意ください。<br>
                                ※体験中に撮影が入る場合がございます。あらかじめご了承ください。<br>
                                ※1月15日（木）に下記の電話番号より確認のお電話をさせていただきます。<br>
                                <br>
                                03-6277-8627  [Brian Blakely Cusoms]ライブカスタマイズ体験　事務局<br>
                                <br>
                                ※後日アンケート配信をさせていただきますので、ご協力をお願いいたします。<br>
                                ※当選した会員様のみ予約いただけます。<br>
                            </div>
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

                    <form action="{{ route('versity-jacket.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="item-row">
                            <label for="f_name" class="item-title">ご都合の良い日程を一つ選択してください</label>
                            <div class="item-content">
                                <p class="mail_supplement mb-1">※ご希望日時は先着順となっている為、既に埋まっている日時は選択する事が出来ません。</p>
                                ■1月16日（金）
                                <div class="flex mb-2 ml-2" style="flex-wrap: wrap;gap: 0.25rem 1rem;">
                                    @foreach(App\Consts\VersityJacketConst::DAY_1 as $key => $val)
                                        <label class="radio-label">
                                            <input type="radio" class="" name="choice_1" value="{{ $key }}"
                                                   @if(old('choice_1') == $key)
                                                       checked="checked"
                                                   @endif
                                                   @if(in_array($key, $exist_choice_1))
                                                       disabled
                                                   @endif
                                                   >
                                            {{ $val }}
                                        </label>
                                    @endforeach
                                </div>
                                ■1月17日（土）
                                <div class="flex mb-2 ml-2" style="flex-wrap: wrap;gap: 0.25rem 1rem;">
                                    @foreach(App\Consts\VersityJacketConst::DAY_2 as $key => $val)
                                        <label class="radio-label">
                                            <input type="radio" class="" name="choice_1" value="{{ $key }}"
                                                   @if(old('choice_1') == $key)
                                                       checked="checked"
                                                   @endif
                                                   @if(in_array($key, $exist_choice_1))
                                                       disabled
                                                   @endif
                                                   >
                                            {{ $val }}
                                        </label>
                                    @endforeach
                                </div>
                                ■1月18日（日）
                                <div class="flex mb-2 ml-2" style="flex-wrap: wrap;gap: 0.25rem 1rem;">
                                    @foreach(App\Consts\VersityJacketConst::DAY_3 as $key => $val)
                                        <label class="radio-label">
                                            <input type="radio" class="" name="choice_1" value="{{ $key }}"
                                                   @if(old('choice_1') == $key)
                                                       checked="checked"
                                                   @endif
                                                   @if(in_array($key, $exist_choice_1))
                                                       disabled
                                                   @endif
                                                   >
                                            {{ $val }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
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
                                        <input type='text' name='l_read' value='{{ old("l_read") }}'
                                               placeholder='タロウ'>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item-row">
                            <label for="email" class="item-title">メールアドレス</label>
                            <div class="item-content px-2">
                                <p class="mail_supplement mb-1">※メールアドレスは確認用の為、2回入力してください。</p>
                                <p class="mail_supplement mb-1">※myNBにご登録のメールアドレスを入力してください。</p>
                                <p class="mail_supplement mb-1">
                                    ※docomo、au、softbank、iCloud等の各キャリアのメールアドレスは、ドメイン指定受信を設定されている可能性がありメールが正しく届かない事がある為、PC用のメールアドレスを記入する事をお勧め致します。</p>
                                <div class="">
                                    <div class="w-full mb-1">
                                        <input type="email" name="email" id="email" value="{{ old("email") }}"
                                               class="form-control" placeholder="sample@newbalance.co.jp">
                                    </div>
                                    <div class="w-full">
                                        <input type="email" name="email_confirmation"
                                               value="{{ old("email_confirmation") }}"
                                               class="form-control" placeholder="sample@newbalance.co.jp(確認用)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item-row">
                            <label for="tel" class="item-title">myNB会員ID(10桁)</label>
                            <div class="item-content">
                                <div class="flex w-72 px-2">
                                    <input type="text" name="nbid" id="nbid" value="{{ old("nbid") }}" class="form-control">
                                </div>
                                <p class="mail_supplement mb-1">※myNB会員IDについては下記よりご確認ください。<br><a href="https://shop.newbalance.jp/guide-faq-mynb.html" target="_blank">https://shop.newbalance.jp/guide-faq-mynb.html</a></p>
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
