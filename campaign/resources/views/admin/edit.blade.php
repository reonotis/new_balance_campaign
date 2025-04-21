<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-white text-center text-3xl font-bold leading-tight">
        </h2>
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <form method="post" action="{{ route('admin.form-update', ['form_setting' => $form_setting->id]) }}">
                    @csrf
                    <table class="create-form" style="width: 800px">
                        <tr>
                            <th>イベント名</th>
                            <td><input type="text" name="title" id="title" class="w-full" value="{{ old("title", $form_setting->title) }}"></td>
                        </tr>
                        <tr>
                            <th>最大申込可能数</th>
                            <td><input type="number" name="max_application_count" id="max_application_count" value="{{ old("max_application_count", $form_setting->max_application_count) }}"></td>
                        </tr>
                        <tr>
                            <th>ルーティング</th>
                            <td>{{ $form_setting->route_name }}</td>
                        </tr>
                        <tr>
                            <th>期間</th>
                            <td>
                                <input type="date" name="start_at" id="start_at" value="{{ old("start_at", $form_setting->start_at->format('Y-m-d')) }}">
                                <input type="date" name="end_at" id="end_at" value="{{ old("end_at", $form_setting->end_at->format('Y-m-d')) }}">
                            </td>
                        </tr>
                        <tr>
                            <th>記入事項</th>
                            <td>
                                <textarea name="form_information" id="form_information" rows="10" class="w-full">{{ old("information", $form_setting->form_information) }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>事務局メールアドレス</th>
                            <td>
                                <input type="email" name="secretariat_mail_address" id="secretariat_mail_address" value="{{ old("secretariat_mail_address", $form_setting->secretariat_mail_address) }}" class="w-full">
                            </td>
                        </tr>
                        <tr>
                            <th>自動返信メール題名</th>
                            <td>
                                <input type="text" name="mail_title" id="mail_title" value="{{ old("mail_title", $form_setting->mail_title) }}" class="w-full">
                            </td>
                        </tr>
                        <tr>
                            <th>自動返信メール本文</th>
                            <td>
                                <textarea name="mail_text" id="mail_text" rows="10" class="w-full">{{ old("mail_text", $form_setting->mail_text) }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>画像を格納するディレクトリ名</th>
                            <td>
                                <input type="text" name="image_dir_name" id="image_dir_name" value="{{ old("image_dir_name", $form_setting->image_dir_name) }}" >
                            </td>
                        </tr>
                        <tr>
                            <th>cssファイル名</th>
                            <td><input type="text" name="css_file_name" id="css_file_name" class="w-full" value="{{ old("css_file_name", $form_setting->css_file_name) }}"></td>
                        </tr>
                        <tr>
                            <th>バナーファイル名</th>
                            <td><input type="text" name="banner_file_name" id="banner_file_name" class="w-full" value="{{ old("banner_file_name", $form_setting->banner_file_name) }}"></td>
                        </tr>
                    </table>
                    <div class="flex">
                        <input type="submit" class="btn" value="更新">
                    </div>
                </form>
                <div class="flex mt-2">
                    <a href="{{ route('admin.form-item-edit', ['form_setting' => $form_setting->id] ) }}" id="" class="btn">項目設定</a>
                </div>
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


    // 日付文字列を YYYY-MM-DD に変換する関数
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const yyyy = date.getFullYear();
        const mm = ('0' + (date.getMonth() + 1)).slice(-2);
        const dd = ('0' + date.getDate()).slice(-2);
        return `${yyyy}-${mm}-${dd}`;
    }

    {{--document.getElementById('formSettingBtn').addEventListener('click', function (e) {--}}
    {{--    e.preventDefault();--}}

    {{--    let apply_type = $('#apply_type').val();--}}
    {{--    let form_no = $('#form_no').val();--}}

    {{--    if (!apply_type) {--}}
    {{--        alert('フォーム番号を入力してください');--}}
    {{--        return;--}}
    {{--    }--}}

    {{--    const baseUrl = "{{ route('admin.form-item-setting') }}";--}}
    {{--    const url = `${baseUrl}?apply_type=${encodeURIComponent(apply_type)}&form_no=${encodeURIComponent(form_no)}`;--}}

    {{--    window.location.href = url;--}}
    {{--});--}}


</script>

