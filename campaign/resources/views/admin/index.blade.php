<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-white text-center text-3xl font-bold leading-tight">
        </h2>
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="mx-auto sm:px-6 lg:px-8" style="max-width: 1400px">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                @foreach(App\Consts\CommonApplyConst::APPLY_TITLE_LIST as $key => $value)
                    @if($key >= 20)
                        <div class="p-2 mb-2" ><a href="{{ route('admin.common_apply', ['applyType' => $key]) }}" class="list-href"
                            > {{ App\Consts\CommonApplyConst::APPLY_TITLE_LIST[$key] }} 申込リストを確認する</a>
                        </div>
                    @endif
                @endforeach

                @if(\Auth::user()->id === 1)
                    <div class="mb-4"><a href="{{ route('admin.form-create') }}">新規フォーム作成はこちら</a></div>
                @endif

                <div class="pagination-container">
                    {{ $form_settings->links() }}
                </div>

                <table class="list-tbl">
                    <thead>
                    <tr>
                        <th>イベント名</th>
                        <th>受付期間</th>
                        <th>申込件数(最大申込可能数)</th>

                        @if(\Auth::user()->id === 1)
                            <th>フォーム設定</th>
                            <th>項目設定</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($form_settings as $form_setting)
                        <tr>
                            <td>
                                <a href="{{ route('admin.list', ['form_setting' => $form_setting->id]) }}" class="list-href"
                                >{{ $form_setting->title }}</a>
                            </td>
                            <td>
                                {{ $form_setting->start_at }}&nbsp;~&nbsp;{{ $form_setting->end_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td>
                                {{ $application_count[$form_setting->id] ?? 0 }}件
                                @if($form_setting->max_application_count)
                                ({{ $form_setting->max_application_count }}件)
                                @endif
                            </td>
                            @if(\Auth::user()->id === 1)
                                <td>
                                    <a href="{{ route('admin.form-edit', ['form_setting' => $form_setting->id]) }}">設定</a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.form-item-edit', ['form_setting' => $form_setting->id]) }}">設定</a>
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

