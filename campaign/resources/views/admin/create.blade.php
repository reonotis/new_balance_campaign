<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-white text-center text-3xl font-bold leading-tight">
        </h2>
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <form method="post" action="{{ route('admin.form-register') }}">
                    @csrf
                    <table class="create-form" style="width: 800px">
                        <tr>
                            <th>
                                申込タイプ（ID）<br>
                                apply_type
                            </th>
                            <td>
                                <input type="number" name="apply_type" id="apply_type" value="{{ old("apply_type") }}">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                フォームナンバー（回数）<br>
                                form_no
                            </th>
                            <td>
                                <input type="number" name="form_no" id="form_no" value="{{ old("form_no") }}">
                            </td>
                        </tr>
                        <tr>
                            <th>イベント名</th>
                            <td>
                                <input type="text" name="title" id="title" class="w-full" value="{{ old("title") }}">
                            </td>
                        </tr>
                        <tr>
                            <th>最大申込可能数</th>
                            <td>
                                <input type="number" name="max_application_count" id="max_application_count" value="{{ old("max_application_count") }}">
                            </td>
                        </tr>
                        <tr>
                            <th>ルーティング</th>
                            <td><input type="text" name="route_name" id="route_name" value="{{ old("route_name") }}" class="w-full"></td>
                        </tr>
                        <tr>
                            <th>期間</th>
                            <td>
                                <input type="date" name="start_at" id="start_at" value="{{ old("start_at") }}">
                                <input type="date" name="end_at" id="end_at" value="{{ old("end_at") }}">
                            </td>
                        </tr>
                        <tr>
                            <th>事務局メールアドレス</th>
                            <td>
                                <input type="email" name="secretariat_mail_address" id="secretariat_mail_address" value="{{ old("secretariat_mail_address") }}" class="w-full">
                            </td>
                        </tr>
                        <tr>
                            <th>自動返信メール題名</th>
                            <td>
                                <input type="text" name="mail_title" id="mail_title" value="{{ old("mail_title") }}" class="w-full">
                            </td>
                        </tr>
                        <tr>
                            <th>自動返信メール本文</th>
                            <td>
                                <textarea name="mail_text" id="mail_text" rows="10" class="w-full">{{ old("mail_text") }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>画像を格納するディレクトリ名</th>
                            <td>
                                <input type="text" name="image_dir_name" id="image_dir_name" value="{{ old("image_dir_name") }}" >
                            </td>
                        </tr>
                    </table>
                    <div class="flex">
                        <input type="submit" class="btn" value="登録/更新">
                    </div>
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
        margin: 0 auto;
        cursor: pointer;
        border-radius: .5rem;
        transition: .2s;
    }


    .btn:hover {
        background: white;
        color: #0a2153;
    }


</style>

<script>

    const api_url = @json(route('api_admin_get_form_detail'));
    $(document).ready(function () {

        $('#apply_type').on('change', function () {
            getData()
        });

        $('#form_no').on('change', function () {
            getData()
        });

    });

    function getData() {
        let apply_type = $('#apply_type').val();
        let formNo = $('#form_no').val();

        $.ajax({
            url: api_url + '?apply_type=' + apply_type + '&form_no=' + formNo,
            method: 'GET',
            success: function (data) {
                if (data.form_setting) {
                    const form_setting = data.form_setting;
                    $('#title').val(form_setting.title);
                    $('#form_no').val(form_setting.form_no);
                    $('#max_application_count').val(form_setting.max_application_count);
                    $('#route_name').val(form_setting.route_name);
                    $('#start_at').val(formatDate(form_setting.start_at));
                    $('#end_at').val(formatDate(form_setting.end_at));
                    $('#secretariat_mail_address').val(form_setting.secretariat_mail_address);
                    $('#mail_title').val(form_setting.mail_title);
                    $('#mail_text').val(form_setting.mail_text);
                    $('#image_dir_name').val(form_setting.image_dir_name);
                } else {
                    $('#title').val("");
                    $('#max_application_count').val("");
                    $('#route_name').val("");
                    $('#start_at').val("");
                    $('#end_at').val("");
                    $('#secretariat_mail_address').val("");
                    $('#mail_title').val("");
                    $('#mail_text').val("");
                    $('#image_dir_name').val("");
                }
            },
            error: function () {
                alert('データの取得に失敗しました');
            }
        });
    }

    // 日付文字列を YYYY-MM-DD に変換する関数
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const yyyy = date.getFullYear();
        const mm = ('0' + (date.getMonth() + 1)).slice(-2);
        const dd = ('0' + date.getDate()).slice(-2);
        return `${yyyy}-${mm}-${dd}`;
    }
</script>

