「MINATO RUNNERS BASE キャンペーン」にお申し込みがありました。<br>
<br>
--------------------------------------<br>
【申し込み内容】<br>
<b>■お名前</b>　　　 : {{ $name }} ({{ $read }}) 様<br>
<b>■年齢</b>　  　　 : {{ $age }}<br>
<b>■メールアドレス</b> : {{ $email }}<br>
<b>■電話番号</b>　　 : {{ $tel }}<br>
<b>■住所</b>　　　　 : {{ $zip }}<br>
　　　　　　　　{{ $streetAddress }}<br>
<b>■イベントを知ったきっかけ</b>:<br>
@foreach($howFound as $val)
    ・{{ $val }}<br>
@endforeach
<br>
--------------------------------------<br>
<br>
<br>
