<?php
    $successes = session('successes');
    $errors = session('errors');
?>

@if ($successes || $errors)
    <div class="flash-area">
        <div class="flash-message-area" >
            {{-- サクセスメッセージ --}}
            @if ($successes)
                @foreach ($successes as $success)
                    <div class="flash-message-box flash-message-success" >
                        {{ $success }}
                    </div>
                @endforeach
            @endif

            {{-- エラーメッセージ --}}
            @if ($errors)
                @foreach ($errors as $error)
                    <div class="flash-message-box flash-message-error" >
                        {{ $error }}
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endif
