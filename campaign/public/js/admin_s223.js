reset_display_list()

$('input[name="choice_time"]:radio').change(function () {
    reset_display_list()
})

function reset_display_list() {
    let class_name = "choice-time-" + $('input[name="choice_time"]:checked').val()

    $('.choice-time').each(function(index, element){
        if($(element).hasClass(class_name)){
            $(element).show();
        }else{
            $(element).hide();
        }
    })

}

