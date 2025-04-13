【申し込み内容】<br>

@foreach($form_items as $form_item)
    @switch($form_item->type_no)
        @case(1)
            <b>■お名前</b>　　　 : {{ $request->f_name . ' ' . $request->l_name }} ({{ $request->f_read . ' ' . $request->l_read }}) 様<br>
            @break
        @case(2)
            <b>■ヨミ</b>　　　 　 : ({{ $request->f_read . ' ' . $request->l_read }}) 様<br>
            @break
        @case(3)
            <b>■性別</b>　　　　 : {{ \App\Consts\Common::SEX_LIST[$request['sex']] }}<br>
            @break
        @case(4)
            <b>■年齢</b>　  　　　: {{ $request->age }}<br>
            @break
        @case(5)
            <b>■住所</b>　　　　 : {{ $request->zip21 . '-' . $request->zip22 }}<br>
          　　　　　　　　{{ $request->pref21 . ' ' . $request->address21 . ' ' . $request->street21 }}<br>
            @break
        @case(6)
          <b>■電話番号</b>　　 : {{ $request['tel'] }}<br>
            @break
        @case(7)
          <b>■メールアドレス</b>　　 : {{ $request['email'] }}<br>
            @break
    @endswitch
@endforeach
--------------------------------------<br>
全体のお申込みは下記よりご確認いただけます。<br>
URL : <a href="{{$url}}">{{$url}}</a>
<br>
