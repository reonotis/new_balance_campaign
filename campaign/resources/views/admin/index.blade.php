<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-white text-center text-3xl font-bold leading-tight">
        </h2>
    </x-slot>

    <div class="pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 mb-2" ><a href="{{ route('admin.try-on-2023') }}" class="list-href" >Running TRY ON キャンペーン のリストを確認する</a></div>
                <div class="p-2 mb-2" ><a href="{{ route('admin.go-murakami-2023') }}" class="list-href" >村上宗隆選手応援 キャンペーン のリストを確認する</a></div>
                <div class="p-2 mb-2" ><a href="{{ route('admin.aruku-tokyo-2022') }}" class="list-href" >国立までアルクTOKYO キャンペーン のリストを確認する</a></div>
                <div class="p-2 mb-2" ><a href="{{ route('admin.try_on') }}" class="list-href" >TRY ON キャンペーン のリストを確認する</a></div>
                <div class="p-2 mb-2" ><a href="{{ route('admin.golf-try-on-2023') }}" class="list-href" >GOLF TRY ON 2023 キャンペーン のリストを確認する</a></div>
                <div class="p-2 mb-2" ><a href="{{ route('admin.s223') }}" class="list-href" >New Balance Fall ＆ Winter 2023 Apparel Collection 申込リストを確認する</a></div>
                @foreach(App\Consts\CommonApplyConst::APPLY_TITLE_LIST as $key => $value)
                    <div class="p-2 mb-2" ><a href="{{ route('admin.common_apply', ['applyType' => $key]) }}" class="list-href"
                        > {{ App\Consts\CommonApplyConst::APPLY_TITLE_LIST[$key] }} 申込リストを確認する</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</x-admin-layout>
