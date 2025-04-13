
<div class="item-row">
    <label for="sex" class="item-title">性別</label>
    <div class="item-content px-2">
        <div class="flex" style="flex-wrap: wrap;gap: .5rem;">
            @foreach(App\Consts\Common::SEX_LIST as $key => $value)
                <label class="radio-label" style="padding: 0 .5rem;">
                    <input type="radio" class="" name="sex" value="{{ $key }}"
                           @if(old('sex') == $key)
                               checked="checked"
                        @endif
                    >
                    {{ $value }}
                </label>
            @endforeach
        </div>
    </div>
</div>
