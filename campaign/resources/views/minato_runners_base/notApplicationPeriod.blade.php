<x-MinatoRunnersBase-layout>
    <x-slot name="support">
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
