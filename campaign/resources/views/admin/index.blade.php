<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-white text-center text-3xl font-bold leading-tight">
        </h2>
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="mx-auto sm:px-6 lg:px-8" style="max-width: 1200px">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                @foreach(App\Consts\CommonApplyConst::APPLY_TITLE_LIST as $key => $value)
                    <div class="p-2 mb-2" ><a href="{{ route('admin.common_apply', ['applyType' => $key]) }}" class="list-href"
                        > {{ App\Consts\CommonApplyConst::APPLY_TITLE_LIST[$key] }} 申込リストを確認する</a>
                    </div>
                @endforeach

                @if(\Auth::user()->id === 1)
                    <div class="mb-4"><a href="{{ route('admin.form-create') }}">新規フォーム作成はこちら</a></div>
                @endif

                <table class="list-tbl">
                    <thead>
                    <tr>
                        <th>イベント名</th>
                        <th>受付期間</th>

                        @if(\Auth::user()->id === 1)
                            <th>設定</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($form_settings as $form_setting)
                        <tr>
                            <td>
                                <a href="{{ route('admin.list', ['apply_type' => $form_setting->apply_type, 'form_no' => $form_setting->form_no ]) }}"
                                >{{ $form_setting->title }}</a>
                            </td>
                            <td>
                                {{ $form_setting->start_at }}&nbsp;~&nbsp;{{ $form_setting->end_at->format('Y-m-d H:i:s') }}
                            </td>
                            @if(\Auth::user()->id === 1)
                                <td>
                                    <a href="{{ route('admin.form-create', ['apply_type' => $form_setting->apply_type, 'form_no' => $form_setting->form_no]) }}">設定</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-admin-layout>

<style>
    .list-tbl {
        border-collapse: collapse;
        width: 100%;
    }

    .list-tbl th,
    .list-tbl td {
        border: 1px solid #888; /* 薄いグレーの罫線 */
        padding: 8px;
    }
</style>

