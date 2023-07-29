<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight">
            New Balance Fall ＆ Winter 2023 Apparel Collection 申込一覧
        </h2>
    </x-slot>

    <script src="{{ asset('js/admin_s223.js') }}?<?= date('YmdHis') ?>" type="text/javascript" defer></script>

    <div class="pt-4 pb-12 px-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">

            <div style="margin-bottom: 1rem">
                @foreach(App\Consts\S223::CHOICE_TIME as $key => $val)
                    <label style="margin-right: 1rem">
                        <input type="radio" class="" name="choice_time" value="{{ $key }}"
                            @if($key == 1)
                                checked="checked"
                            @endif
                        >{{ $val['time'] }}</label>
                @endforeach
            </div>
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <table class="list-table" >
                    <thead>
                        <tr>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100 rounded-tl">申込日時</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">希望時間</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">名前</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">住所</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">電話番号</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">メールアドレス</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $apply)
                            <tr class="choice-time choice-time-{{ $apply->choice_1 }}">
                                <td class="border-b-2 border-gray-200 px-4 py-2">{{ $apply->created_at->format('Y/m/d H:i')}}</td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{{ App\Consts\S223::CHOICE_TIME[$apply->choice_1]['time'] }}</td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">
                                    <p class="customer-name" >{{ $apply->f_name ." ". $apply->l_name }} 様</p>
                                    <p class="customer-read" >{{ $apply->f_read ." ". $apply->l_read }}</p>
                                </td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">
                                    <p class="customer-read" >{{ $apply->zip21 ." - ". $apply->zip22 }}</p>
                                    <p class="customer-read" >{{ $apply->pref21 ." ". $apply->addr21 }}</p>
                                    <p class="customer-read" >{{ $apply->street21 }}</p>
                                </td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{{ $apply->tel }}</td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{{ $apply->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-admin-layout>
