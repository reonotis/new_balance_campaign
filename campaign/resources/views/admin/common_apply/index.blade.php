<x-admin-layout>
    <x-slot name="head">
        @if (array_key_exists('img_pass', $displayItemList))
            <script src="{{ asset('js/admin_pop_up_img.js') }}?<?= date('YmdHis') ?>" type="text/javascript" defer></script>
        @endif
    </x-slot>

    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight">
            {{ $applyTitle }} 申込一覧
        </h2>
    </x-slot>

    <div class="pt-4 pb-12 px-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between" >
                    <a href="{{ route('admin.redirect_apply_form', ['applyType' => $applyType]) }}" target="_blank" >申込サイトを確認する</a>

                    @if($lotteryResultEmail)
                        <div id="email_count">
                            送信予定件数は {{ $emailCount }} 件です
                        </div>
                    @endif
                </div>
                @if(empty($applyList))
                    申し込みはありません
                @else
                    <div class="flex justify-between mb-2" style="align-items: center;" >
                        <div style="width: 600px;">{{ $paginationList->links() }}</div>
                        <div style="margin-right: 50px;" class="flex">
                            <a href="{{ route('admin.common_apply_csv_dl', ['applyType' => $applyType]) }}" class="common-button" >CSV ダウンロード</a>
                        </div>
                    </div>
                    <table class="list-table" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>申込日時</th>
                                @foreach ($displayItemList as $itemKey => $displayItem)
                                    <th>{{ $displayItem }}</th>
                                @endforeach
                                @if($lotteryResultEmail)
                                    <th>当選メール送信</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applyList as $apply)
                                <tr>
                                    <td class="id_column">{{ $apply['id'] }}</td>
                                    <td class="create_column">{{ $apply['created_at'] }}</td>
                                    @foreach ($displayItemList as $itemKey => $displayItem)
                                        <td class="{{ $itemKey }}_column">
                                            @switch($itemKey)
                                                @case('name')
                                                    <p>{{ $apply[$itemKey][0] }} 様</p>
                                                    <p>({{ $apply[$itemKey][1] }})</p>
                                                    @break
                                                @case('address')
                                                    <p>{{ $apply[$itemKey][0] }}</p>
                                                    <p>{{ $apply[$itemKey][1] }}</p>
                                                    <p>{{ $apply[$itemKey][2] }}</p>
                                                    @break
                                                @case('img_pass')
                                                    <img src="{{ $apply[$itemKey] }}"
                                                         class="resize_img"
                                                         style="width:100px; margin: 0 auto">
                                                    @break
                                                @case('comment')
                                                    {!! nl2br(e($apply[$itemKey])) !!}
                                                    @break
                                                @case('choice_1')
                                                    {{ \App\Consts\CommonApplyConst::CHOICE_1[$applyType][$apply[$itemKey]] }}
                                                    @break
                                                @case('choice_2')
                                                    {{ \App\Consts\CommonApplyConst::CHOICE_2[$applyType][$apply[$itemKey]] }}
                                                    @break
                                                @case('choice_3')
                                                    {{ \App\Consts\CommonApplyConst::CHOICE_3[$applyType][$apply[$itemKey]] }}
                                                    @break
                                                @default
                                                    <p>{{ $apply[$itemKey] }}</p>
                                            @endswitch
                                        </td>
                                    @endforeach
                                    @if($lotteryResultEmail)
                                        <td>
                                            @if($apply['sent_lottery_result_email_flg'] == 1)
                                                送信済み
                                            @else
                                                <input type="checkbox" name="" value="1"
                                                       class="lottery-result-email" id="lottery_result_email_{{ $apply['id'] }}"
                                                        @if($apply['send_lottery_result_email_flg'])
                                                            checked
                                                        @endif
                                                >
                                            @endif
                                        </td>
                                    @endif
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
    const api_url = @json(route('api_admin_lottery_result_email', ['applyType' => $applyType]));
</script>
