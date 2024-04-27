@extends('layouts.kichijoji_grey_days_exclusive')
@section('header')
@endsection
@section('content')
    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 ">

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

                    <form action="{{ route('kichijoji-grey-days-exclusive.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="itemRow">
                            <label for="f_name" class="itemTitle"><span>お名前</span></label>
                            <div class="itemContent">
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
                        <div class='itemRow'>
                            <label for="f_read" class="itemTitle"><span>ヨミ</span></label>
                            <div class="itemContent">
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
                        <div class='itemRow'>

                            <label class="itemTitle"><span>性別</span></label>
                            <div class="itemContent">
                                <div class='flex'>
                                    @foreach(App\Consts\Common::SEX_LIST as $key => $val)
                                        <label class="radio-label">
                                            <input type="radio" class="" name="sex" value="{{ $key }}"
                                                   @if(old('sex') == $key)
                                                       checked="checked"
                                                @endif
                                            >
                                            {{ $val }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                        <div class='itemRow'>
                            <label for="zip21" class="itemTitle">住所</label>
                            <div class='itemContent'>
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
                        <div class="itemRow">
                            <label for="tel" class="itemTitle">電話番号</label>
                            <div class="itemContent">
                                <div class="flex w-72 px-2">
                                    <input type="text" name="tel" id="tel" value="{{ old("tel") }}" class="form-control"
                                           placeholder="03-5577-2300">
                                </div>
                            </div>
                        </div>
                        <div class="itemRow">
                            <label for="email" class="itemTitle">メールアドレス</label>
                            <div class="itemContent px-2">
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
                                        <input type="email" name="email_confirmation" value="{{ old("email_confirmation") }}"
                                               class="form-control" placeholder="sample@newbalance.co.jp(確認用)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="itemRow">
                            <label for="email" class="itemTitle">ニューバランス Greyに関する質問</label>
                            <div class="itemContent px-2">
                                <div class="w-full mb-1">
                                    <textarea name="comment" id="comment"
                                              class="form-control h-20">{{ old("comment") }}</textarea>
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
@endsection
