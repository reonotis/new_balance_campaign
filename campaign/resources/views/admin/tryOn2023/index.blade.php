<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight">
            Running TRY ON キャンペーン
        </h2>
    </x-slot>

    <div class="pt-4 pb-12 px-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <table class="list-table" >
                    <thead>
                        <tr>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100 rounded-tl">日時</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">名前</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">年齢</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">住所</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">電話番号</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">メールアドレス</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">画像</th>
                            <th class="w-10 tracking-wider text-gray-900 text-sm bg-gray-100 rounded-tr">応募動機</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applyList as $apply)
                            <tr>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{{ $apply->created_at->format('Y/m/d H:i')}}</td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">
                                    <p class="customer-name" >{{ $apply->f_name ." ". $apply->l_name }} 様</p>
                                    <p class="customer-read" >{{ $apply->f_read ." ". $apply->l_read }}</p>
                                </td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{{ $apply->age }}歳</td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">
                                    <p class="customer-read" >{{ $apply->zip21 ." - ". $apply->zip22 }}</p>
                                    <p class="customer-read" >{{ $apply->pref21 ." ". $apply->addr21 }}</p>
                                    <p class="customer-read" >{{ $apply->street21 }}</p>

                                </td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{{ $apply->tel }}</td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{{ $apply->email }}</td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">
                                    <div class="w-32" >
                                        <img src="{{ asset('storage/TO2023/' . $apply->img_pass) }}" class="try_on_img_resize" >
                                    </div>
                                </td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{!! nl2br(e($apply->reason_applying)) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>{{ $applyList->links() }}

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
