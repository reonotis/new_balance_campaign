<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-white text-center text-3xl font-bold leading-tight">
        </h2>
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                @foreach(App\Consts\CommonApplyConst::APPLY_TITLE_LIST as $key => $value)
                    <div class="p-2 mb-2" ><a href="{{ route('admin.common_apply', ['applyType' => $key]) }}" class="list-href"
                        > {{ App\Consts\CommonApplyConst::APPLY_TITLE_LIST[$key] }} 申込リストを確認する</a>
                    </div>
                @endforeach

                @if(\Auth::user()->id === 1)
                    <div><a href="{{ route('admin.form-create') }}">新規フォーム作成はこちら</a></div>
                @endif

                <table>
                    <thead>
                    <tr>
                        <th>イベント名</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($form_settings as $form)
                        <tr>
                            <td>{{ $form->title }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-admin-layout>
