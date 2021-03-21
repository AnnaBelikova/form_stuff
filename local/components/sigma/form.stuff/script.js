$(document).on('ready', function(){
    $('form'). on('submit', function(event) {
        event.preventDefault();
        if(!e.isDefaultPrevented()){
            e.returnValue = false;
        }
        let url = './';
        $.ajax({
            url: url,
            method: 'post',
            data: $(this).serialize(),
            success: function (data) {
                response = JSON.parse(data);
                console.log(response);
            }
        });
    });
});