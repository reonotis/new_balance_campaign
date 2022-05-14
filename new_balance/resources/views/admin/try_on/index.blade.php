<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight">
            TRY ON キャンペーン
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
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">住所</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">電話番号</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">メールアドレス</th>
                            <th class="px-4 py-3 tracking-wider text-gray-900 text-sm bg-gray-100">画像</th>
                            <th class="w-10 tracking-wider text-gray-900 text-sm bg-gray-100 rounded-tr">応募動機</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tryOns as $tryOn)
                            <tr>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{{ $tryOn->created_at->format('Y/m/d H:i')}}</td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">
                                    <p class="customer-name" >{{ $tryOn->f_name ." ". $tryOn->l_name }} 様</p>
                                    <p class="customer-read" >{{ $tryOn->f_read ." ". $tryOn->l_read }}</p>
                                </td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">
                                    <p class="customer-read" >{{ $tryOn->zip21 ." - ". $tryOn->zip22 }}</p>
                                    <p class="customer-read" >{{ $tryOn->pref21 ." ". $tryOn->addr21 }}</p>
                                    <p class="customer-read" >{{ $tryOn->street21 }}</p>

                                </td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{{ $tryOn->tel }}</td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{{ $tryOn->email }}</td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">
                                    <div class="w-32" >
                                        <img src="{{ asset('storage/try_on_img_resize/' . $tryOn->img_pass) }}" class="try_on_img_resize" >
                                    </div>
                                </td>
                                <td class="border-b-2 border-gray-200 px-4 py-2">{!! nl2br(e($tryOn->reason_applying)) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>{{ $tryOns->links() }}


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
