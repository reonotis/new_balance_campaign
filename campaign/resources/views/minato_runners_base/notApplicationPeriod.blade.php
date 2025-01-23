<x-MinatoRunnersBase-layout>
    <x-slot name="support">
        <div class="support-content">
            <p>名古屋ウィメンズマラソン攻略「Runningイベント 20kmチャレンジ」</p>
            <p>日時：2/16（日）10:30-13:30</p>
            <p>場所：スーパースポーツゼビオ ららぽーと名古屋みなとアクルス店</p>
            <p>内容：ロング走 max20km（4km周回コース）</p>
            <p>参加人数：先着30名</p>
            <p>参加費：無料</p>
        </div>
    </x-slot>
    <x-slot name="header">
        <h2 class="text-white text-center text-3xl font-bold leading-tight">
            MINATO RUNNERS BASE キャンペーン <br class="brSp2">お申込フォーム
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="p-6 bg-white" style="border-radius: 25px">
                        <div class="card-body" style="padding: 1rem 0;">
                            {!! $checkMessage !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-MinatoRunnersBase-layout>
