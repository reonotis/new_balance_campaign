「New Balance Running Campaign」のイベントに申し込みがありました。<br>
<br>
--------------------------------------<br>
【申し込み内容】<br>
<b>■お名前</b>　　　 : {{ $name }} ({{ $read }}) 様<br>
<b>■年齢</b>　  　　 : {{ $age }}<br>
<b>■メールアドレス</b> : {{ $email }}<br>
<b>■電話番号</b>　　 : {{ $tel }}<br>
<b>■住所</b>　　　　 : {{ $zip }}<br>
　　　　　　　　{{ $streetAddress }}<br>
<b>■電話番号</b>　　 : {{ $hope_gift }}<br>
<b>■希望景品</b>　　 : {{ \App\Consts\StepConst::HOPE_GIFT[$hope_gift] }}<br>
<br>
--------------------------------------<br>
全体のお申込みは下記よりご確認いただけます。<br>
URL : <a href="{{$url}}">{{$url}}</a>
<br>
