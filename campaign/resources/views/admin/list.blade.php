<x-admin-layout>
    <x-slot name="head">
    </x-slot>

    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight">
            {{ $form_setting->title }} 申込一覧
        </h2>
        <div>
            <div class="mt-4">
                <a href="{{ route('admin') }}">一覧に戻る</a>
            </div>
        </div>
    </x-slot>

    <div class="pt-4 pb-12 px-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between" >
                    <a href="{{ route('common_form.index', ['route_name' => $form_setting->route_name]) }}" target="_blank" >申込サイトを確認する</a>
                    @if($form_setting->send_bulk_mail_flg)
                        <div class="flex items-center">
                            <span id="send_mail_count_span">送信予定件数は {{ $send_mail_count }} 件です</span>
                            <button class="common-button" id="send_mail">一斉メール送信</button>
                        </div>
                    @endif
                    <a href="{{ route('admin.csv-download', ['form_setting' => $form_setting->id]) }}" class="common-button" >CSV ダウンロード</a>
                </div>

                <!-- DataTable本体 -->
                <table id="applications-table"></table>
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

<!-- CDN読み込み -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>

    const api_url = @json(route('admin.api.application_sand_email_flg', ['form_setting' => $form_setting->id]));

    $(document).ready(function() {
        const columnsUrl = "{{ route('admin.get-application-column', ['form_setting' => $form_setting->id]) }}";
        const url = "{{ route('admin.get-application-list', ['form_setting' => $form_setting->id]) }}";  // ← Laravelルート名を使う

        // 一覧表を表示する
        $.get(columnsUrl, function (columns) {
            $('#applications-table').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [ [10, 20, 50, 100], ['10件', '20件', '50件', '100件'] ],
                pageLength: 20,
                language: {
                    emptyTable: "データが存在しません",
                    info: "全 _TOTAL_ 件中 _START_ ～ _END_ を表示",
                    infoEmpty: "0 件中 0 ～ 0 を表示",
                    infoFiltered: "（全 _MAX_ 件からフィルタ）",
                    lengthMenu: "_MENU_ 表示",
                    loadingRecords: "読み込み中...",
                    processing: "処理中...",
                    zeroRecords: "一致するレコードが見つかりませんでした",
                    paginate: {
                        first: "先頭",
                        last: "最後",
                        next: "次",
                        previous: "前"
                    },
                    aria: {
                        sortAscending: ": 昇順に並び替え",
                        sortDescending: ": 降順に並び替え"
                    }
                },
                ajax: {
                    url: url,
                },
                columns: columns,
                searching: false,
            });
        });

        $(document).on('click', '.application-sand-email', function () {
            let val = ($(this).prop("checked")) ? 1 : 0;
            let id = $(this).data('id');

            $.ajax({
                url: api_url,
                method: 'get',
                dataType: 'json',
                data: {
                    'id': id,
                    'value': val,
                },
            }).done(function (res) {
                if (typeof res['success'] != 'undefined') {
                    $('#send_mail_count_span').html('送信予定件数は ' + res['success']['count'] + ' 件です\n')
                } else {
                    alert('更新に失敗しました。。画面を再ロードしてください');
                }
            }).fail(function () {
                $('#reception_table_area').html('');
                alert('更新に失敗しました。画面を再ロードしてください');
            });
        });

        $('#send_mail').on('click', function(){
            alert('機能作成中')
        })
    });


</script>

<style>
    #applications-table_length select {
        padding-right: 2rem!important;
    }

    #applications-table thead {
        background: white;
    }
</style>
