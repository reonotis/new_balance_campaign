<x-Base-layout>
    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="form-area">
                    <div class="event-explanation">
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">東京六体フェス2024</div>
                            <div class="event-explanation-title">New Balance Run Club Tokyoチーム参加</div>
                        </div>
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">日時</div>
                            <div class="event-explanation-content">
                                ①42.195kmリレー（15名／1チーム×2チーム）DAY１：9/28（土）14:00-19:00<br>
                                ②６時間耐久リレー（15名／1チーム×2チーム）DAY２：9/29（日）10:30-17:30
                            </div>
                        </div>
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">場所</div>
                            <div class="event-explanation-content">
                                味の素スタジアム（東京都調布市西町376番地3）
                            </div>
                        </div>
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">参加費用</div>
                            <div class="event-explanation-content">
                                3,000円（お支払方法は参加者様へ後日別途ご案内いたします）
                            </div>
                        </div>
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">アクセス</div>
                            <div class="event-explanation-content">
                                京王線飛田給駅より徒歩10分<br>
                            </div>
                        </div>
                        <div class="event-explanation-row">
                            <div class="event-explanation-title">補足事項</div>
                            <div class="event-explanation-content">
                                ①②各先着30名となります。<br>
                                ※お一人様1種目のみのエントリーとさせていただきます。<br>
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

                    <form action="{{route('tokyo-rokutai-fes-2024.store')}}" method="post" >
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
                        <div class="item-row">
                            <label for="sex" class="item-title">性別</label>
                            <div class="item-content px-2">
                                <div class="flex" style="flex-wrap: wrap;gap: .5rem;">
                                    @foreach(App\Consts\Common::SEX_LIST as $key => $value)
                                        <label class="radio-label" style="padding: 0 .5rem;">
                                            <input type="radio" class="" name="sex" value="{{ $key }}"
                                                @if(old('sex') == $key)
                                                    checked="checked"
                                                @endif
                                            >
                                            {{ $value }}
                                        </label>
                                    @endforeach
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
                            <label for="" class="item-title">希望種目（選択どちらかのみ）</label>
                            <div class="item-content px-2">
                                <p class="mail_supplement mb-1">※既に申込件数に達している種目は選択できません</p>
                                <div class="flex" style="flex-wrap: wrap;gap: .5rem;">
                                    @foreach(App\Consts\TokyoRokutaiFesConst::PREFERRED_EVENT as $value => $label)
                                        <label class="radio-label" style="padding: 0 .5rem;">
                                            <input type="radio" class="" name="preferred_event" value="{{ $value }}"
                                                   @if(old('preferred_event') == $value)
                                                       checked="checked"
                                                   @endif
                                                   @if($application_count_list[$value] >= 30)
                                                       disabled
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

<style>
    input[type="radio"]:disabled {
        background: #ddd;
    }
</style>
