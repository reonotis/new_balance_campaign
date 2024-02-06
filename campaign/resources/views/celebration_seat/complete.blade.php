@extends('layouts.celebration_seat')
@section('header')
    <h2 class="text-center font-bold leading-tight">
        Run your way. Celebration Sheet.<br class="brSp2">応募フォーム
    </h2>
@endsection
@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="p-6 bg-white">
                        <div class="card-header mb-4">
                            ご応募が完了ました。
                        </div>
                        <div class="card-body">
                            入力したメールアドレスに通知メールを送信しておりますのでご確認ください
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
