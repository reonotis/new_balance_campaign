

<div class="item-row">
    <label for="f_name" class="item-title">お名前</label>
    <div class="item-content">
        <div class="flex">
            <div class="w-1/2 px-2">
                <input type="text" name="f_name" id="f_name" value="{{ old("f_name") }}"
                       placeholder="田中">
            </div>
            <div class="w-1/2 px-2">
                <input type="text" name="l_name" value="{{ old("l_name") }}" placeholder="太郎">
            </div>
        </div>
    </div>
</div>

