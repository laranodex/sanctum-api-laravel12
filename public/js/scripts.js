// ==========================================
// Password Toggle
// ==========================================
$('.toggle-password').on('click', function () {

    const button = $(this);

    const input = button
        .closest('.input-group')
        .find('input');

    const eyeIcon = button.find('i');


    if (input.attr('type') === 'password') {

        input.attr('type', 'text');

        eyeIcon
            .removeClass('bi-eye')
            .addClass('bi-eye-slash');

    } else {

        input.attr('type', 'password');

        eyeIcon
            .removeClass('bi-eye-slash')
            .addClass('bi-eye');

    }

});
