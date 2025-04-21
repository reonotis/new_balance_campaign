【申し込み内容】<br>

@foreach($form_items as $form_item)
    @switch($form_item->type_no)
        @case(App\Models\FormItem::ITEM_TYPE_NAME)
            <b>■お名前</b>　　　 : {{ $request->f_name . ' ' . $request->l_name }} ({{ $request->f_read . ' ' . $request->l_read }}) 様<br>
            @break
        @case(App\Models\FormItem::ITEM_TYPE_YOMI)
            <b>■ヨミ</b>　　　 　 : ({{ $request->f_read . ' ' . $request->l_read }}) 様<br>
            @break
        @case(App\Models\FormItem::ITEM_TYPE_SEX)
            <b>■性別</b>　　　　 : {{ \App\Consts\Common::SEX_LIST[$request['sex']] }}<br>
            @break
        @case(App\Models\FormItem::ITEM_TYPE_AGE)
            <b>■年齢</b>　  　　　: {{ $request->age }}<br>
            @break
        @case(App\Models\FormItem::ITEM_TYPE_ADDRESS)
            <b>■住所</b>　　　　 : {{ $request->zip21 . '-' . $request->zip22 }}<br>
          　　　　　　　　{{ $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21 }}<br>
            @break
        @case(App\Models\FormItem::ITEM_TYPE_TEL)
          <b>■電話番号</b>　　 : {{ $request['tel'] }}<br>
            @break
        @case(App\Models\FormItem::ITEM_TYPE_EMAIL)
          <b>■メールアドレス</b>　　 : {{ $request['email'] }}<br>
            @break
        @case(App\Models\FormItem::ITEM_TYPE_CHOICE_1)
          <b>■{{ $form_item->choice_data['item_name'] }}</b>　　 : {{ $request['choice_11'] }}<br>
            @break
        @case(App\Models\FormItem::ITEM_TYPE_CHOICE_2)
          <b>■{{ $form_item->choice_data['item_name'] }}</b>　　 : {{ $request['choice_12'] }}<br>
            @break
        @case(App\Models\FormItem::ITEM_TYPE_CHOICE_3)
          <b>■{{ $form_item->choice_data['item_name'] }}</b>　　 : {{ $request['choice_13'] }}<br>
            @break
    @endswitch
@endforeach
--------------------------------------<br>
全体のお申込みは下記よりご確認いただけます。<br>
URL : <a href="{{$url}}">{{$url}}</a>
<br>
