$(document).ready(function(){

    // Functions
    loginUser();
    registerUser();
});

function loginUser(){
    $('#login-form').on('submit', function(e){
        e.preventDefault();

        var form = new FormData(this);
        var url = BASE_URL + 'account/login'
        var res = SERVER(url, form);
        if(res.status){
            $('body').fadeOut('slow', function() {
                window.location.href = BASE_URL + 'message';
            });
        }else{
            $('#error-message').html(`<div id="flashMessage1">` + res.message +`</div>`);
            setTimeout(function() {
                $('#flashMessage1').fadeOut('slow');
            }, 2000);
        }
    })
}


function registerUser(){
    $('#register-form').on('submit', function(e){
        e.preventDefault();

        var inputsEmpty = true;
        $('.input-field').each(function() {
            if ($(this).val().trim() === '') {
                inputsEmpty = false;
                return false; 
            }
        });


        if (!inputsEmpty) {
            $('#error-message').html(`<div id="flashMessage">All fields should not be empty</div>`);
                setTimeout(function() {
                    $('#flashMessage').fadeOut('slow');
                }, 2000);
        }else{
            var name = $('.input-field[name="name"]').val().trim();

            if(name.length < 5 || name.length > 20){
                
                $('#error-message').html(`<div id="flashMessage">Name must be 5 to 20 characters</div>`);
                setTimeout(function() {
                    $('#flashMessage').fadeOut('slow');
                }, 2000);
            }else{
                var form = new FormData(this);
                var url = BASE_URL + 'account/auth'
                var res = SERVER(url, form);
            
                if(res.status){
                    $('body').fadeOut('slow', function() {
                        window.location.href = BASE_URL + 'message/thankyou';
                    });
                }else{
                    $('#error-message').html(`<div id="flashMessage">` + res.message +`</div>`);
                    setTimeout(function() {
                        $('#flashMessage').fadeOut('slow');
                    }, 2000);
                }
            }
        }
    })
}
