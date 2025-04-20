<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-white text-center text-3xl font-bold leading-tight">
        </h2>
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <table class="create-form" style="width: 800px">
                    <tr>
                        <th>イベント名</th>
                        <td>{{ $form_setting->title }}</td>
                    </tr>
                    <tr>
                        <th>回数</th>
                        <td>{{ $form_setting->form_no }}</td>
                    </tr>
                </table>

                <div class="flex" >
                    <div class="btn" id="add-item">追加</div>
                </div>

                <form method="post" action="{{ route('admin.form-item-update', ['form_setting' => $form_setting->id]) }}">
                    @csrf
                    <div id="sortable-items" class="cursor-move">
                        {{-- 設定してある項目 --}}
                        @foreach($form_items as $form_item)
                            {{-- 選択肢3種類 --}}
                            @if(in_array($form_item->type_no, [
                                \App\Models\FormItem::ITEM_TYPE_CHOICE_1,
                                \App\Models\FormItem::ITEM_TYPE_CHOICE_2,
                                \App\Models\FormItem::ITEM_TYPE_CHOICE_3,
                            ]))
                                <div class="flex item-row">
                                    <div style="width: 40px;">
                                        <input type="checkbox" name="type_no[]" value="{{ $form_item->type_no }}" checked>
                                    </div>
                                    <div style="width: 120px;">
                                        {{ \App\Models\FormItem::ITEM_TYPE_LIST[$form_item->type_no] }}
                                    </div>
                                    <div class="">
                                        <div style="width: 420px;">
                                            <label>
                                                <input type="radio" name="item_type[{{ $form_item->type_no }}]" value="1"
                                                    @if($form_item->choice_data['item_type'] == 1)  checked @endif
                                                >ラジオボタン
                                            </label>
                                            <label>
                                                <input type="radio" name="item_type[{{ $form_item->type_no }}]" value="2"
                                                       @if($form_item->choice_data['item_type'] == 2)  checked @endif
                                                >チェックボックス
                                            </label>
                                            <label>
                                                <input type="radio" name="item_type[{{ $form_item->type_no }}]" value="3"
                                                       @if($form_item->choice_data['item_type'] == 3)  checked @endif
                                                >セレクトボックス
                                            </label>
                                        </div>
                                        <div style="width: 300px;">
                                            項目名
                                            <input type="text" name="item_name[{{ $form_item->type_no }}]" value="{{ $form_item->choice_data['item_name'] }}">
                                        </div>
                                        <div style="flex:1;">
                                            選択肢
                                            <textarea name="choices[{{ $form_item->type_no }}]">{{ $form_item->choice_data['choices'] }}</textarea>
                                        </div>
                                        <div style="flex:1;">
                                            注意書き
                                            <textarea type="text" name="support_msg[{{ $form_item->type_no }}]">{{ $form_item->choice_data['support_msg']?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            {{-- コメント3種類 --}}
                            @elseif(in_array($form_item->type_no, [
                                \App\Models\FormItem::ITEM_TYPE_COMMENT_1,
                                \App\Models\FormItem::ITEM_TYPE_COMMENT_2,
                                \App\Models\FormItem::ITEM_TYPE_COMMENT_3,
                            ]))
                                <div class="flex item-row">
                                    <div style="width: 40px;">
                                        <input type="checkbox" name="type_no[]" value="{{ $form_item->type_no }}" checked>
                                    </div>
                                    <div style="width: 120px;">
                                        {{ \App\Models\FormItem::ITEM_TYPE_LIST[$form_item->type_no] }}
                                    </div>
                                    <div style="width: 300px;">
                                        項目名
                                        <input type="text" name="comment_title[{{ $form_item->type_no }}]" value="{{ $form_item->comment_title }}">
                                    </div>
                                </div>
                            {{-- 注意事項 --}}
                            @elseif($form_item->type_no == \App\Models\FormItem::ITEM_TYPE_NOTES)
                                <div class="flex item-row">
                                    <div style="width: 40px;">
                                        <input type="checkbox" name="type_no[]" value="{{ $form_item->type_no }}" checked>
                                    </div>
                                    <div style="width: 120px;">
                                        {{ \App\Models\FormItem::ITEM_TYPE_LIST[$form_item->type_no] }}
                                    </div>
                                    <div class="">
                                        <div style="width: 300px;">
                                            項目名
                                            <input type="text" name="item_name[{{ $form_item->type_no }}]" value="{{ $form_item->choice_data['item_name']?? '' }}">
                                        </div>
                                        <div style="flex:1;">
                                            注意書き
                                            <textarea type="text" name="support_msg[{{ $form_item->type_no }}]">{{ $form_item->choice_data['support_msg']?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex item-row">
                                    <div style="width: 40px;">
                                        <input type="checkbox" name="type_no[]" value="{{ $form_item->type_no }}" checked>
                                    </div>
                                    <div style="width: 120px;">
                                        {{ \App\Models\FormItem::ITEM_TYPE_LIST[$form_item->type_no] }}
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        {{-- 設定していない項目 --}}
                        @foreach($none_setting_items as $none_setting_item_key => $none_setting_item)
                            @if(in_array($none_setting_item_key, [
                                \App\Models\FormItem::ITEM_TYPE_CHOICE_1,
                                \App\Models\FormItem::ITEM_TYPE_CHOICE_2,
                                \App\Models\FormItem::ITEM_TYPE_CHOICE_3,
                            ]))
                                <div class="flex item-row">
                                    <div style="width: 40px;">
                                        <input type="checkbox" name="type_no[]" value="{{ $none_setting_item_key }}" >
                                    </div>
                                    <div style="width: 120px;">
                                        {{ \App\Models\FormItem::ITEM_TYPE_LIST[$none_setting_item_key] }}
                                    </div>
                                    <div class="">
                                        <div style="width: 420px;">
                                            <label>
                                                <input type="radio" name="item_type[{{ $none_setting_item_key }}]" value="1" >ラジオボタン
                                            </label>
                                            <label>
                                                <input type="radio" name="item_type[{{ $none_setting_item_key }}]" value="2" >チェックボックス
                                            </label>
                                            <label>
                                                <input type="radio" name="item_type[{{ $none_setting_item_key }}]" value="3" >セレクトボックス
                                            </label>
                                        </div>
                                        <div style="width: 300px;">
                                            項目名
                                            <input type="text" name="item_name[{{ $none_setting_item_key }}]">
                                        </div>
                                        <div style="flex:1;">
                                            選択肢
                                            <textarea name="choices[{{ $none_setting_item_key }}]"></textarea>
                                        </div>
                                    </div>
                                </div>
                            @elseif(in_array($none_setting_item_key, [
                                \App\Models\FormItem::ITEM_TYPE_COMMENT_1,
                                \App\Models\FormItem::ITEM_TYPE_COMMENT_2,
                                \App\Models\FormItem::ITEM_TYPE_COMMENT_3,
                            ]))
                                <div class="flex item-row">
                                    <div style="width: 40px;">
                                        <input type="checkbox" name="type_no[]" value="{{ $none_setting_item_key }}">
                                    </div>
                                    <div style="width: 120px;">
                                        {{ $none_setting_item }}
                                    </div>
                                    <div style="width: 300px;">
                                        項目名
                                        <input type="text" name="comment_title[{{ $none_setting_item_key }}]">
                                    </div>
                                </div>
                            @elseif($none_setting_item_key == \App\Models\FormItem::ITEM_TYPE_NOTES)
                                <div class="flex item-row">
                                    <div style="width: 40px;">
                                        <input type="checkbox" name="type_no[]" value="{{ $none_setting_item_key }}" >
                                    </div>
                                    <div style="width: 120px;">
                                        {{ $none_setting_item }}
                                    </div>
                                    <div class="">
                                        <div style="width: 300px;">
                                            項目名
                                            <input type="text" name="item_name[{{ $none_setting_item_key }}]" >
                                        </div>
                                        <div style="flex:1;">
                                            注意書き
                                            <textarea type="text" name="support_msg[{{ $none_setting_item_key }}]"></textarea>
                                        </div>
                                    </div>

                                </div>
                            @else
                                <div class="flex item-row">
                                    <div style="width: 40px;">
                                        <input type="checkbox" name="type_no[]" value="{{ $none_setting_item_key }}">
                                    </div>
                                    <div style="width: 300px;">
                                        {{ $none_setting_item }}
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                    <input type="submit" class="btn mt-2" value="更新">
                </form>

            </div>
        </div>
    </div>
</x-admin-layout>

<style>
    .item-row {
        padding: .25rem;
        border-bottom: solid 1px #333;
    }

    .create-form {
        border-spacing: 0 10px; /* 横0px、縦10pxのスペース */
        border-collapse: separate; /* これが必要 */
    }

    .create-form th {
        background: #8cb1d2;
    }

    .create-form th,
    .create-form td {
        padding: .5rem;
        border-bottom: solid 1px #333;
    }

    .btn {
        background: #0a2153;
        color: white;
        padding: .5rem 1rem;
        cursor: pointer;
        border-radius: .5rem;
        transition: .2s;
    }

    .btn:hover {
        background: white;
        color: #0a2153;
    }

    textarea {
        resize: auto;
        height: 100px;
        width: 300px;
    }

</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Sortable(document.getElementById('sortable-items'), {
            animation: 150,
            handle: '.cursor-move',
        });
    });

    document.getElementById('add-item').addEventListener('click', function () {
        const container = document.getElementById('sortable-items');

        // ★ 追加する項目の内容（仮：type_no[]=999, ラベル: 新規項目）
        const newItemHTML = ``;

        // div要素を作成してHTMLを挿入
        const wrapper = document.createElement('div');
        wrapper.innerHTML = newItemHTML;

        // 最後に追加
        container.appendChild(wrapper);
    });

</script>
