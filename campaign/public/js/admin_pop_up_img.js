
$(function() {
  // 画像をクリックしたら
  $(".resize_img").click(function() {
    var thisSrc = $(this).attr('src')
    let full_img = thisSrc.replace("/resize", "");
    $("#popup-img").children().attr('src', full_img)
    $("#popup-img-mask").fadeIn(250);
  });

  // ×ボタンをクリックしたら
  $("#popup-img-close-btn").click(function() {
    $("#popup-img-mask").fadeOut(250);
  });
});

