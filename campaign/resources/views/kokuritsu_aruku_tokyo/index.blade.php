<x-FCTokyoKokuritsu-layout>
    <x-slot name="header">
        <h2 class="text-white text-center text-3xl font-bold leading-tight">
            国立アルク東京キャンペーン <br class="brSp2">お申込フォーム
        </h2>
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4">

                    <x-flash-massege class="mb-4"></x-flash-massege>

                     <form action="{{route('aruku-tokyo-2022.store')}}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <div class="itemRow">
                            <div class="itemTitle"><label for="f_name" >お名前</label></div>
                            <div class="itemContent">
                                <div class="flex">
                                    <div class="w-1/2 px-2" >
                                        <input type="text" name="f_name" id="f_name" value="{{ old("f_name") }}" class="fc_form1" placeholder="田中" >
                                    </div>
                                    <div class="w-1/2 px-2" >
                                        <label><input type="text" name="l_name" value="{{ old("l_name") }}" class="fc_form1" placeholder="太郎" ></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='itemRow'>
                            <div class='itemTitle'><label for="f_read" >ヨミ</label></div>
                            <div class='itemContent'>
                                <div class='flex'>
                                    <div class="w-1/2 px-2" >
                                        <input type='text' name='f_read' id='f_read' value='{{ old("f_read") }}' class='fc_form1' placeholder='タナカ' >
                                    </div>
                                    <div class="w-1/2 px-2" >
                                        <label><input type='text' name='l_read' value='{{ old("l_read") }}' class='fc_form1' placeholder='タロウ' ></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='itemRow'>
                            <div class="itemTitle"><label for="zip21" >住所</label></div>
                            <div class='itemContent'>
                                <div class="flex justify-start items-center mb-1" >
                                    <div class="px-2 w-32" >
                                        <input type='text' name='zip21' id='zip21' value='{{ old("zip21") }}' placeholder='101' >
                                    </div>
                                    <div class="px-1" >-</div>
                                    <div class="px-2 w-32" >
                                        <label><input type='text' name='zip22' value='{{ old("zip22") }}' onKeyUp='AjaxZip3.zip2addr("zip21","zip22","pref21","address21","street21");' placeholder='0051' ></label>
                                    </div>
                                </div>
                                <div class="flex mb-1" >
                                    <div class="px-2 w-48" >
                                        <label for='pref21'></label><input type='text' name='pref21' id='pref21' value='{{ old("pref21") }}' class='Prefectures' placeholder='東京都' >
                                    </div>
                                    <div class="px-2 w-48" >
                                        <label for='address21'></label><input type='text' name='address21' id='address21' value='{{ old("address21") }}' class='Municipality' placeholder='千代田区' >
                                    </div>
                                </div>
                                <div class="flex px-2" >
                                    <label for="street21"></label><input type="text" name="street21" id="street21" value="{{ old("street21") }}" class="form-control" placeholder="神田神保町1-105" >
                                </div>
                            </div>
                        </div>
                        <div class="itemRow">
                            <div class="itemTitle"><label for="tel" >電話番号</label></div>
                            <div class="itemContent">
                                <div class="flex w-72 px-2" >
                                    <input type="text" name="tel" id="tel" value="{{ old("tel") }}" class="form-control" placeholder="03-5577-2300" >
                                </div>
                            </div>
                        </div>
                        <div class="itemRow">
                            <div class="itemTitle"><label for="email1" >メールアドレス</label></div>
                            <div class="itemContent px-2">
                                <p class="mail_supplement mb-1" >※メールアドレスは確認用の為、2回入力してください</p>
                                <p class="mail_supplement mb-1" >※docomo、au、softbank、iCloud等の各キャリアのメールアドレスは、ドメイン指定受信を設定されている可能性がありメールが正しく届かない事がある為、PC用のメールアドレスを記入する事をお勧め致します。</p>
                                <div class="px-2" >
                                    <div class="w-full mb-1">
                                        <input type="email" name="email1" id="email1" value="{{ old("email1") }}" class="form-control" placeholder="sample@newbalance.co.jp" >
                                    </div>
                                    <div class="w-full">
                                        <label for="email2"></label><input type="email" name="email2" id="email2" value="{{ old("email2") }}" class="form-control" placeholder="sample@newbalance.co.jp(確認用)" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="itemRow">
                            <div class="itemTitle"><label for="email1" >希望サイズ</label></div>
                            <div class="itemContent">
                                <div class="w-48 px-2" >
                                    <select name="shoes_size" class="form-control" >
                                        <option value="">-</option>
                                        @foreach( App\Consts\FcTokyoConst::SHOES_SIZE as $size)
                                            <option value="{{ $size }}" @if(old("shoes_size") == $size) {{ 'selected' }} @endif >{{ $size }}</option>
                                        @endforeach
                                    </select>
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

    <script type="text/javascript">
        function applyConfirm() {
            return window.confirm('申し込みを行ってもよろしいですか？');
        }
    </script>
</x-FCTokyoKokuritsu-layout>
