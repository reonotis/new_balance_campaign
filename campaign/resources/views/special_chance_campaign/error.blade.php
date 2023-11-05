@extends('layouts.special_chance_campaign')
@section('header')
    <h2 class="text-center font-bold leading-tight">
        スペシャルチャンス<br class="brSp2">キャンペーン<br class="brSp2">お申込フォーム
    </h2>
@endsection
@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="p-6 bg-white">
                        <div class="card-body">
                            {{ $errorMessage }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
