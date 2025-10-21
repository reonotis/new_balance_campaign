

<div class="item-row">
    <label for="email" class="item-title">メールアドレス</label>
    <div class="item-content px-2">
        <p class="mail_supplement mb-1">※myNB会員ご登録の方は、会員登録しているメールアドレスをご記載ください</p>
        <p class="mail_supplement mb-1">※メールアドレスは確認用の為、2回入力してください</p>
        <p class="mail_supplement mb-1">
            ※docomo、au、softbank、iCloud等の各キャリアのメールアドレスは、ドメイン指定受信を設定されている可能性がありメールが正しく届かない事がある為、PC用のメールアドレスを記入する事をお勧め致します。</p>
        <div class="">
            <div class="w-full mb-1">
                <input type="email" name="email" id="email" value="{{ old("email") }}"
                       class="form-control" placeholder="sample@newbalance.co.jp">
            </div>
            <div class="w-full">
                <input type="email" name="email_confirmation" value="{{ old("email_confirmation") }}"
                       class="form-control" placeholder="sample@newbalance.co.jp(確認用)">
            </div>
        </div>
    </div>
</div>
