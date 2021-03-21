function checkAge(inputValue){
    if(inputValue<=0 || inputValue>150){
        return false;
    }else{
        return true;
    }
}




$(document).on('ready', function(){
    $('form'). on('submit', function(event) {
        event.preventDefault();
        let error='';
        if(!checkAge($('[name=AGE]').val())){
            error += 'Возраст указан неверно.';
            $('.stuff_form_error').html(error);
        }else{
            let url = './';
            var str = $('#stuff_form').serialize();
            var request = {
                'NAME': $('[name=NAME]').val(),
                'SURNAME': $('[name=SURNAME]').val(),
                'SECOND_NAME': $('[name=SECOND_NAME]').val(),
                'POSITION' : $('[name=POSITION]').val(),
                'AGE' : $('[name=AGE]').val()
            };

            $.ajax({
                url: url,
                method: 'post',
                type: 'post',
                data: request,
                success: function (data) {
                    $('.stuff_form_popup').html(data);
                    let text = $('.stuff_form_popup').text();
                    if(text.indexOf('Ошибка:') == -1){
                        $('.stuff_form_popup').fadeIn(500);
                        $("body").append("<div id='overlay'></div>");$('#overlay').show();
                    }else{
                        error += text;
                        $('.stuff_form_error').html(error);
                    }
                },
                error: function (error) {
                    console.log("ERROR : ", error);
                }
            });
        }


    });

    $('.stuff_form_popup ').on('click', function(){
        $('#stuff_form').trigger("reset");
        $('.stuff_form_popup').fadeOut(500);
        $('#overlay').hide();
    });
});