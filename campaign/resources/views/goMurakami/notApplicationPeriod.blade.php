<x-FCTokyoKokuritsu-layout>
    <x-slot name="header">
        <h2 class="text-white text-center text-3xl font-bold leading-tight">
            村上宗孝選手応援キャンペーン <br class="brSp2">お申込フォーム
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="p-6 bg-white">
                        <div class="card-header mb-4">
                            申込期間外です
                        </div>
                        <div class="card-body">
                            {{ $checkMessage }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-FCTokyoKokuritsu-layout>
