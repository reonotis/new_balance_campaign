<x-Base-layout>

    <x-slot name="page_title">
        <h2 class="text-center text-3xl font-bold leading-tight">
            {{ $form_setting->title }}
        </h2>
    </x-slot>

    <x-slot name="script">
        @if($form_setting->css_file_name)
            <link rel="stylesheet" href="{{ asset('css/' . $form_setting->css_file_name) }}?<?= date('YmdHis') ?>">
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="p-6 bg-white">
                        <div class="card-header mb-4">
                            申し込みが完了しました。
                        </div>
                        <div class="card-body">
                            入力したメールアドレスに通知メールを送信しておりますのでご確認ください。
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-Base-layout>






<div class="event-explanation-row"><div class="event-explanation-title">【Ellipseについて】</div><div class="event-explanation-content">毎日のランニングを一日の中で最も楽しい時間にするために設計されています。ランナーが「もっと楽しみたい」と感じている部分に応えるためにデザインされており、走ることそのものの楽しさをより感じられるように作られたシューズです。</div></div><div class="event-explanation-row"><div class="event-explanation-title">【応募概要】</div><div class="event-explanation-content">■応募締切</div><div class="event-explanation-content">2026年3月30日（月）23:59</div><div class="event-explanation-content">■当選発表</div><div class="event-explanation-content">４月上旬（予定）にメールにてご連絡いたします。<br>※応募フォームに記載いただいたメールアドレスへご連絡の上発送いたします。<br>※ドメイン指定受信を利用されている場合は、「news@email.newbalance.jp」を受信できるように設定をお願いいたします。<br></div></div>
