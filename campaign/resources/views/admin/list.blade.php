<x-admin-layout>
    <x-slot name="head">
    </x-slot>

    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight">
            {{ $form_setting->title }} 申込一覧
        </h2>
    </x-slot>

    <div class="pt-4 pb-12 px-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between" >
                    <div>
                        <div class="mb-4">
                            <a href="{{ route('admin') }}">一覧に戻る</a>
                        </div>
                        <div>
{{--                            <a href="{{ route('admin.redirect_apply_form', ['applyType' => $applyType]) }}" target="_blank" >申込サイトを確認する</a>--}}
                        </div>
                    </div>
                </div>
                @if(empty($applications))
                    申し込みはありません
                @else
                    <div class="flex justify-between mb-2" style="align-items: center;" >
                        <div style="width: 600px;">
                            {{ $applications->appends(request()->query())->links() }}
                        </div>
                        <div style="margin-right: 50px;" class="flex">
                            <a href="" class="common-button" >CSV ダウンロード</a>
                        </div>
                    </div>
                    <table class="list-table" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>申込日時</th>
                                @foreach($form_setting->formItem as $form_item)
                                    @switch($form_item->type_no)
                                        @case(App\Models\FormItem::ITEM_TYPE_NAME)
                                        @case(App\Models\FormItem::ITEM_TYPE_YOMI)
                                        @case(App\Models\FormItem::ITEM_TYPE_SEX)
                                        @case(App\Models\FormItem::ITEM_TYPE_AGE)
                                        @case(App\Models\FormItem::ITEM_TYPE_ADDRESS)
                                        @case(App\Models\FormItem::ITEM_TYPE_TEL)
                                        @case(App\Models\FormItem::ITEM_TYPE_EMAIL)
                                            <th>{{ App\Models\FormItem::ITEM_TYPE_LIST[$form_item->type_no]  }}</th>
                                            @break
                                        @case(App\Models\FormItem::ITEM_TYPE_CHOICE_1)
                                        @case(App\Models\FormItem::ITEM_TYPE_CHOICE_2)
                                        @case(App\Models\FormItem::ITEM_TYPE_CHOICE_3)
                                            <th>{{ $form_item->choice_data['item_name'] }}</th>
                                            @break
                                    @endswitch
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $apply)
                                <tr>
                                    <td class="id_column">{{ $apply['id'] }}</td>
                                    <td class="create_column">{{ $apply['created_at'] }}</td>

                                    @foreach($form_setting->formItem as $form_item)
                                        @switch($form_item->type_no)
                                            @case(App\Models\FormItem::ITEM_TYPE_NAME)
                                                <td>{{ $apply->f_name.$apply->l_name }}</td>
                                                @break
                                            @case(App\Models\FormItem::ITEM_TYPE_YOMI)
                                                <td>{{ $apply->f_read.$apply->l_read }}</td>
                                                @break
                                            @case(App\Models\FormItem::ITEM_TYPE_SEX)
                                                <td>
                                                    {{ App\Consts\Common::SEX_LIST[$apply->sex]?? '' }}
                                                </td>
                                                @break
                                            @case(App\Models\FormItem::ITEM_TYPE_AGE)
                                                <td>{{ $apply->age }}</td>
                                                @break
                                            @case(App\Models\FormItem::ITEM_TYPE_ADDRESS)
                                                <td>
                                                    {{ $apply->zip21 . '-' . $apply->zip22  }}<br>
                                                    {{ $apply->pref21 }}&nbsp;{{ $apply->address21 }}&nbsp;{{ $apply->street21 }}
                                                </td>
                                                @break
                                            @case(App\Models\FormItem::ITEM_TYPE_TEL)
                                                <td>{{ $apply->tel }}</td>
                                                @break
                                            @case(App\Models\FormItem::ITEM_TYPE_EMAIL)
                                                <td>{{ $apply->email }}</td>
                                                @break
                                            @case(App\Models\FormItem::ITEM_TYPE_CHOICE_1)
                                                <td>{{ $apply->choice_1 }}</td>
                                                @break
                                            @case(App\Models\FormItem::ITEM_TYPE_CHOICE_2)
                                                <td>{{ $apply->choice_2 }}</td>
                                                @break
                                            @case(App\Models\FormItem::ITEM_TYPE_CHOICE_3)
                                                <td>{{ $apply->choice_3 }}</td>
                                                @break
                                        @endswitch
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <div id="popup-img-mask" style="display: none;" >
        <div id="popup-img-close-btn" ></div>
        <div id="popup-img" >
            <img src="" >
        </div>
    </div>

</x-admin-layout>

<script>
</script>
