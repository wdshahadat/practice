$(document).ready(function () {
    $('#sign_in').validate({
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
        }
    });

    $(document).on('change', '#author', function () {
        var auth = {
            partner: {
                u: 'admin',
                p: 'superadmin'
            },
            manager: {
                u: 'saifullah',
                p: 'saifullah'
            }
        }
        $('#username').val(auth[this.value].u);
        $('#password').val(auth[this.value].p);
    })
});
