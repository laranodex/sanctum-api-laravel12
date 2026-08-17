<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

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

            <div id="loginMessage"></div>

            <!-- Login Form -->
            <form id="loginForm">

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email Address
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">

                    <label for="password" class="form-label">
                        Password
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required>

                        <button
                            type="button"
                            class="input-group-text toggle-password"
                            style="cursor: pointer;">

                            <i class="bi bi-eye"></i>

                        </button>

                    </div>

                </div>

                <!-- Login Button -->
                <button
                    type="submit"
                    class="btn btn-login w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Sign In
                </button>

                <!-- Login Link -->
                <div class="text-center mt-3">
                    <span class="text-muted">
                        Don't have an account?
                    </span>

                    <a href="{{ url('/register') }}" class="login-link">
                        Sign Up
                    </a>
                </div>

            </form>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    <script>
        $(document).ready(function() {

            const token = localStorage.getItem('auth_token');

            if (token) {
                window.location.href = "/dashboard";
                return;
            }

            // ==========================================
            // Login
            // ==========================================
            $('#loginForm').on('submit', function(e) {

                e.preventDefault();

                const email = $('#email').val().trim();
                const password = $('#password').val();

                const loginButton = $('.btn-login');

                // Clear previous message
                $('#loginMessage').html('');

                // Disable button
                loginButton.prop('disabled', true);

                loginButton.html(`
                <span class="spinner-border spinner-border-sm me-2"></span>
                Signing In...
            `);

                $.ajax({

                    url: "{{ url('/api/signin') }}",

                    type: "POST",

                    contentType: "application/json",

                    headers: {
                        'Accept': 'application/json'
                    },

                    data: JSON.stringify({
                        email: email,
                        password: password
                    }),

                    // ==========================================
                    // Success
                    // ==========================================
                    success: function(response) {

                        console.log('Login Response:', response);

                        $('#loginMessage').html(`
                        <div class="alert alert-success">
                            ${response.message}
                        </div>
                    `);

                        // Save Sanctum token
                        localStorage.setItem(
                            'auth_token',
                            response.data.token
                        );


                        // Save user
                        localStorage.setItem(
                            'user',
                            JSON.stringify(response.data.user)
                        );


                        // Redirect
                        setTimeout(function() {

                            window.location.href = "/dashboard";

                        }, 1000);
                    },


                    // ==========================================
                    // Error
                    // ==========================================
                    error: function(xhr) {

                        console.log('Login Error:', xhr);

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


                        $('#loginMessage').html(`
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