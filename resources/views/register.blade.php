<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

</head>

<body>

    <div class="login-wrapper">

        <div class="login-card">

            <div id="registerMessage"></div>

            <!-- Login Form -->
            <form id="registerForm">

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label"> Name </label>

                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>

                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label"> Email Address </label>

                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>

                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">

                    <label for="password" class="form-label"> Password </label>

                    <div class="input-group">
                        <span class="input-group-text"> <i class="bi bi-lock"></i> </span>

                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">

                        <button type="button" class="input-group-text toggle-password" style="cursor: pointer;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                </div>


                <!-- Password Confirmation -->
                <div class="mb-3">

                    <label for="password_confirmation" class="form-label"> Password Confirmation </label>

                    <div class="input-group">

                        <span class="input-group-text"> <i class="bi bi-lock"></i> </span>

                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="Enter your password confirmation">

                        <button type="button" class="input-group-text toggle-password" style="cursor: pointer;">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                </div>

                <!-- Login Button -->
                <!-- <button type="submit" class="btn btn-login w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                </button> -->

                <!-- Register Button -->
                <button type="submit" class="btn btn-login w-100">
                    <i class="bi bi-person-plus me-2"></i>
                    Sign Up
                </button>

                <!-- Login Link -->
                <div class="text-center mt-3">
                    <span class="text-muted">
                        Already have an account?
                    </span>

                    <a href="{{ url('/login') }}" class="login-link">
                        Sign In
                    </a>
                </div>

            </form>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    <script>
        $(document).ready(function() {

            // ==========================================
            // Register
            // ==========================================
            $('#registerForm').on('submit', function(e) {

                e.preventDefault();

                const name = $('#name').val().trim();
                const email = $('#email').val().trim();
                const password = $('#password').val();
                const password_confirmation = $('#password_confirmation').val();

                const loginButton = $('.btn-login');

                // Clear previous message
                $('#registerMessage').html('');

                // Disable button
                loginButton.prop('disabled', true);

                loginButton.html(`
                <span class="spinner-border spinner-border-sm me-2"></span>
                Signing Up...
            `);


                $.ajax({

                    url: "{{ url('/api/signup') }}",

                    type: "POST",

                    contentType: "application/json",

                    headers: {
                        'Accept': 'application/json'
                    },

                    data: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation
                    }),


                    // ==========================================
                    // Success
                    // ==========================================
                    success: function(response) {

                        // console.log('Register Response:', response);

                        $('#registerMessage').html(`
                        <div class="alert alert-success">
                            ${response.message}
                        </div>
                    `);

                        // Redirect
                        setTimeout(function() {

                            window.location.href = "/login";

                        }, 1000);
                    },


                    // ==========================================
                    // Error
                    // ==========================================
                    error: function(xhr) {

                        // console.log('Register Error:', xhr);

                        let message = 'Something went wrong.';


                        if (xhr.responseJSON) {

                            message = xhr.responseJSON.message ||
                                'Something went wrong.';


                            // Validation errors
                            if (xhr.responseJSON.errors) {

                                let errors = xhr.responseJSON.errors;

                                message = '<ul class="mb-0">';

                                $.each(errors, function(field, messages) {

                                    $.each(messages, function(index, error) {

                                        message += `
                                        <li>${error}</li>
                                    `;

                                    });

                                });

                                message += '</ul>';
                            }
                        }


                        $('#registerMessage').html(`
                        <div class="alert alert-danger">
                            ${message}
                        </div>
                    `);
                    },


                    // ==========================================
                    // Always execute
                    // ==========================================
                    complete: function() {

                        loginButton.prop('disabled', false);

                        loginButton.html(`
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In
                    `);
                    }

                });

            });

        });
    </script>
</body>

</html>