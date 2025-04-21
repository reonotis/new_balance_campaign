<x-Base-layout>

    <x-slot name="page_title">
        <h2 class="text-center text-3xl font-bold leading-tight">
            {{ $form_setting->title }} <br class="brSp2">応募フォーム
        </h2>
    </x-slot>

    <x-slot name="script">
        @if($form_setting->css_file_name)
            <link rel="stylesheet" href="{{ asset('css/' . $form_setting->css_file_name) }}?<?= date('YmdHis') ?>">
        @endif
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">

                @if(!empty($form_setting->banner_file_name))
                    <img class="banner-image" src="{{ asset('img/banner/' . $form_setting->banner_file_name) }}" alt="">
                @endif

                <div class="form-area">
                    @if(!empty($form_setting->form_information))
                        <div class="event-explanation">
                            {!! nl2br($form_setting->form_information) !!}
                        </div>
                    @endif

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

                    <form action="{{ $send_route }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="form_setting_id" value="{{ $form_setting->id }}">

                        @foreach($form_items as $form_item)
                            @switch($form_item->type_no)
                                @case(App\Models\FormItem::ITEM_TYPE_NAME)
                                    <x-form_items.name/>
                                    @break
                                @case(App\Models\FormItem::ITEM_TYPE_YOMI)
                                    <x-form_items.yomi/>
                                    @break
                                @case(App\Models\FormItem::ITEM_TYPE_SEX)
                                    <x-form_items.sex/>
                                    @break
                                @case(App\Models\FormItem::ITEM_TYPE_AGE)
                                    <x-form_items.age/>
                                    @break
                                @case(App\Models\FormItem::ITEM_TYPE_ADDRESS)
                                    <x-form_items.address/>
                                    @break
                                @case(App\Models\FormItem::ITEM_TYPE_TEL)
                                    <x-form_items.tel/>
                                    @break
                                @case(App\Models\FormItem::ITEM_TYPE_EMAIL)
                                    <x-form_items.email/>
                                    @break
                                @case(App\Models\FormItem::ITEM_TYPE_CHOICE_1)
                                @case(App\Models\FormItem::ITEM_TYPE_CHOICE_2)
                                @case(App\Models\FormItem::ITEM_TYPE_CHOICE_3)
                                    <div class="item-row">
                                        <label for="tel" class="item-title">{{ $form_item->choice_data['item_name'] }}</label>
                                        <div class="item-content">
                                            @php
                                                $choices = preg_split('/\r\n|\r|\n/', $form_item->choice_data['choices']);
                                            @endphp

                                            @if($form_item->choice_data['item_type'] == 1)
                                                {{-- ラジオボタン --}}
                                                <div class="w-full mb-1 flex" style="flex-wrap: wrap;">
                                                    @foreach($choices as $choice)
                                                        <label class="radio-label" style="margin: 0 .5rem">
                                                            <input type="radio" class="" name="choice_{{ $form_item->type_no }}" value="{{ $choice }}"
                                                                   @if(old('desired_size') == $choice)
                                                                        checked="checked"
                                                                   @endif
                                                            >
                                                            {{ $choice }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @elseif($form_item->choice_data['item_type'] == 2)
                                                {{-- チェックボックス --}}
                                                <div class="w-full mb-1" >
                                                    @foreach($choices as $choice)
                                                        <label class="radio-label" style="margin: 0 .5rem">
                                                            <input type="checkbox" class="" name="choice_{{ $form_item->type_no }}" value="{{ $choice }}"
                                                                   @if(old('desired_size') == $choice)
                                                                       checked="checked"
                                                                @endif
                                                            >
                                                            {{ $choice }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @elseif($form_item->choice_data['item_type'] == 3)
                                                {{-- セレクトボックス --}}
                                                <select name="choice_{{ $form_item->type_no }}"
                                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    @foreach($choices as $choice)
                                                        <option value="{{ $choice }}">{{ $choice }}</option>
                                                    @endforeach
                                                </select>
                                            @endif

                                            @if(!empty($form_item->choice_data['support_msg']))
                                                <div class="px-2 support_msg" >
                                                    {!! nl2br($form_item->choice_data['support_msg']) !!}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @break
                                @case(App\Models\FormItem::ITEM_TYPE_RECEIPT_IMAGE)
                                    <x-form_items.receipt-image/>
                                    @break
                                @case(App\Models\FormItem::ITEM_TYPE_COMMENT_1)
                                @case(App\Models\FormItem::ITEM_TYPE_COMMENT_2)
                                @case(App\Models\FormItem::ITEM_TYPE_COMMENT_3)
                                    <x-form_items.comment title="{{ $form_item->comment_title }}" typeNo="{{ (string)$form_item->type_no }}"/>
                                    @break
                                @case(App\Models\FormItem::ITEM_TYPE_NOTES)
                                    <div class="item-row">
                                        <label for="tel" class="item-title">{{ $form_item->choice_data['item_name'] }}</label>
                                        <div class="item-content">
                                            <div class="agreement">
                                                {!! nl2br($form_item->choice_data['support_msg']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    @break
                                @default
                                    @dd('不正なデータが登録されています')
                            @endswitch
                        @endforeach

                        @if(!empty($form_setting->agreement))
                            <div class="item-row">
                                <label class="item-title">規約</label>
                                <div class="item-content">
                                    <div class="agreement">
                                        {!! nl2br($form_setting->agreement) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

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


{{--<div class="event-explanation-row"><div class="event-explanation-title">日時</div><div class="event-explanation-content">5/22(木) 9:00~12:00</div></div><div class="event-explanation-row"><div class="event-explanation-title">場所</div><div class="event-explanation-content">ニューバランスファクトリーストア軽井沢</div></div><div class="event-explanation-row"><div class="event-explanation-title">参加定員</div><div class="event-explanation-content">30名</div></div><div class="event-explanation-row"><div class="event-explanation-title">参加費</div><div class="event-explanation-content">無料</div></div>--}}

