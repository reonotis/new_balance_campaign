// サムネイル画像をクリックしたら
$(document).on('click', '.resize_img', function () {
    $(".resize_img").click(function () {
        var thisSrc = $(this).attr('src')
        let full_img = thisSrc.replace("/resize", "");
        $("#popup-img").children().attr('src', full_img)
        $("#popup-img-mask").fadeIn(250);
    });
});

// ×ボタンをクリックしたら
$(document).on('click', '#popup-img-close-btn', function () {
    $("#popup-img-mask").fadeOut(250);
});


// TODO
$(function () {
    // 当選メール送信のチェックボックスをクリックしたら
    $(".lottery-result-email").click(function () {
        let val = 0;
        if ($(this).prop("checked")) {
            val = 1;
        }
        let id = $(this).attr('id').replace('lottery_result_email_', '');
        $.ajax({
            url: api_url,
            method: 'get',
            dataType: 'json',
            data: {
                'id': id,
                'value': val,
            },
        }).done(function (res) {
            if (typeof res['success'] != 'undefined') {
                $('#email_count').html('送信予定件数は ' + res['success']['count'] + ' 件です\n')
            } else {
                alert('更新に失敗しました。。画面を再ロードしてください');
            }

        }).fail(function () {
            $('#reception_table_area').html('');
            alert('更新に失敗しました。画面を再ロードしてください');
        });

    });
});

