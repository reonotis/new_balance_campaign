6/28（土）のイベントに申し込みがありました。<br>
<br>
--------------------------------------<br>
【申し込み内容】<br>
<b>■お名前</b>　　　 : {{ $name }} ({{ $read }}) 様<br>
<b>■年齢</b>　  　　 : {{ $age }}<br>
<b>■メールアドレス</b> : {{ $email }}<br>
<b>■電話番号</b>　　 : {{ $tel }}<br>
<b>■住所</b>　　　　 : {{ $zip }}<br>
　　　　　　　　{{ $streetAddress }}<br>
<b>■シューズのサイズ</b>:<br>
    ・{{ \App\Consts\SSXOchanomizuConst::SHOES_SIZE[$shoes_size] }}<br>
<b>■イベントを知ったきっかけ</b>:<br>
    ・{{ \App\Consts\SSXOchanomizuConst::HOW_FOUND[$howFound] }}<br>
<br>
--------------------------------------<br>
<br>
<br>
