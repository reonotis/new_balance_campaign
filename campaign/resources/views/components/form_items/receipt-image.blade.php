

<div class="item-row">
    <label for="f_name" class="item-title">{{ \app\Models\FormItem::ITEM_TYPE_LIST[\app\Models\FormItem::ITEM_TYPE_RECEIPT_IMAGE] }}</label>
    <div class="item-content">
        <div class="flex">
            <div class="w-1/2 px-2">
                <input type="file" id="image" name="image" accept="image/jpeg, image/png">
                <img src="" id="image_preview">
            </div>
        </div>
    </div>
</div>

