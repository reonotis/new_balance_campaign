
<div class='item-row'>
    <label for="zip21" class="item-title">住所</label>
    <div class='item-content'>
        <div class="flex justify-start items-center mb-1">
            <div class="px-2 w-32">
                <input type='text' name='zip21' id='zip21' value='{{ old("zip21") }}'
                       placeholder='101'>
            </div>
            <div class="px-1">-</div>
            <div class="px-2 w-32">
                <input type='text' name='zip22' value='{{ old("zip22") }}'
                       onKeyUp='AjaxZip3.zip2addr("zip21","zip22","pref21","address21","street21");'
                       placeholder='0051'>
            </div>
        </div>
        <div class="flex mb-1">
            <div class="px-2 w-48">
                <input type='text' name='pref21' value='{{ old("pref21") }}' class='Prefectures'
                       placeholder='東京都'>
            </div>
            <div class="px-2 w-48">
                <input type='text' name='address21' value='{{ old("address21") }}'
                       class='Municipality' placeholder='千代田区'>
            </div>
        </div>
        <div class="flex px-2">
            <input type="text" name="street21" value="{{ old("street21") }}"
                   class="form-control" placeholder="神田神保町1-105">
        </div>
    </div>
</div>
