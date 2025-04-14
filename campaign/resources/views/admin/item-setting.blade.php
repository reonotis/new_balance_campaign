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

                <form method="post" action="{{ route('admin.form-item-setting-update') }}">
                    @csrf
                    <div id="sortable-items" class="cursor-move">
                        <input type="hidden" name="apply_type" value="{{ $apply_type }}">
                        <input type="hidden" name="form_no" value="{{ $form_no }}">
                        {{-- 設定してある項目 --}}
                        @foreach($form_items as $form_item)
                            @if(in_array($form_item->type_no, [
                                \App\Models\FormItem::ITEM_TYPE_CHOICE_1,
                                \App\Models\FormItem::ITEM_TYPE_CHOICE_2,
                                \App\Models\FormItem::ITEM_TYPE_CHOICE_3,
                            ]))
                                <div class="flex">
                                    <div style="width: 40px;">
                                        <input type="checkbox" name="type_no[]" value="{{ $form_item->type_no }}" checked>
                                    </div>
                                    <div style="width: 100px;">
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
                                        <div style="width: 300px;">
                                            選択肢
                                            <textarea name="choices[{{ $form_item->type_no }}]">{{ $form_item->choice_data['choices'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex">
                                    <div style="width: 40px;">
                                        <input type="checkbox" name="type_no[]" value="{{ $form_item->type_no }}" checked>
                                    </div>
                                    <div style="width: 300px;">
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
                                <div class="flex">
                                    <div style="width: 40px;">
                                        <input type="checkbox" name="type_no[]" value="{{ $none_setting_item_key }}" >
                                    </div>
                                    <div style="width: 100px;">
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
                                        <div style="width: 300px;">
                                            選択肢
                                            <textarea name="choices[{{ $none_setting_item_key }}]"></textarea>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex">
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
                    <input type="submit" class="btn" value="更新">
                </form>


            </div>
        </div>
    </div>
</x-admin-layout>

<style>
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
<script>
</script>

