@props(['errors'])

@if ($errors)
    <div class="session-error" >
        <div class="font-medium text-red-600">
            下記エラーをご確認管さい
        </div>

        <ul class="mt-3 list-disc text-sm text-red-600">
            @foreach ($errors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
