<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css"
        rel="stylesheet">
</head>

<body class="bg-light">

    <!-- ========================================== -->
    <!-- Navbar -->
    <!-- ========================================== -->

    <nav class="navbar navbar-dark bg-dark shadow-sm">

        <div class="container">

            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-speedometer2 me-2"></i>
                My Dashboard
            </a>



            <div class="d-flex align-items-center gap-3">


                <span id="navbarUserName" class="text-white">
                    Loading...
                </span>

                <a href="/posts" class="btn btn-primary">
                    <i class="bi bi-file-post me-2"></i>
                    Posts
                </a>

                <button
                    type="button"
                    id="logoutButton"
                    class="btn btn-danger">

                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout

                </button>

            </div>

        </div>

    </nav>


    <!-- ========================================== -->
    <!-- Main Content -->
    <!-- ========================================== -->

    <main class="container py-5">

        <!-- Message -->
        <div id="dashboardMessage"></div>


        <!-- Welcome Card -->
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body p-4">

                <h2 class="mb-2">
                    Welcome, <span id="userName">User</span> 👋
                </h2>

                <p class="text-muted mb-0">
                    You are successfully logged in.
                </p>

            </div>

        </div>


        <!-- User Information -->
        <div class="row g-4">

            <!-- User Card -->
            <div class="col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body p-4">

                        <h5 class="card-title mb-4">
                            <i class="bi bi-person-circle me-2"></i>
                            User Information
                        </h5>


                        <div class="mb-3">

                            <label class="text-muted small">
                                Name
                            </label>

                            <div id="userNameDetail" class="fw-semibold">
                                Loading...
                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="text-muted small">
                                Email
                            </label>

                            <div id="userEmail" class="fw-semibold">
                                Loading...
                            </div>

                        </div>


                        <div>

                            <label class="text-muted small">
                                User ID
                            </label>

                            <div id="userId" class="fw-semibold">
                                Loading...
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Authentication Card -->
            <div class="col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body p-4">

                        <h5 class="card-title mb-4">
                            <i class="bi bi-shield-check me-2"></i>
                            Authentication
                        </h5>


                        <div class="d-flex align-items-center mb-3">

                            <span class="badge bg-success me-2">
                                <i class="bi bi-check-circle me-1"></i>
                                Authenticated
                            </span>

                        </div>


                        <p class="text-muted mb-0">
                            Your Sanctum authentication token is active.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </main>


    <!-- ========================================== -->
    <!-- jQuery -->
    <!-- ========================================== -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <!-- ========================================== -->
    <!-- JavaScript -->
    <!-- ========================================== -->

    <script>
        $(document).ready(function() {

            // ==========================================
            // Get Token
            // ==========================================

            const token = localStorage.getItem('auth_token');

            const user = localStorage.getItem('user');


            // ==========================================
            // Check Authentication
            // ==========================================

            if (!token) {

                window.location.href = "/login";

                return;
            }


            // ==========================================
            // Load User
            // ==========================================

            if (user) {

                try {

                    const userData = JSON.parse(user);

                    // console.log('Logged in user:', userData);


                    // User name
                    $('#userName').text(
                        userData.name || 'User'
                    );


                    // Navbar name
                    $('#navbarUserName').text(
                        userData.name || 'User'
                    );


                    // User name detail
                    $('#userNameDetail').text(
                        userData.name || 'N/A'
                    );


                    // Email
                    $('#userEmail').text(
                        userData.email || 'N/A'
                    );


                    // ID
                    $('#userId').text(
                        userData.id || 'N/A'
                    );

                } catch (error) {

                    console.error(
                        'Invalid user data:',
                        error
                    );

                    logoutLocal();

                }

            } else {

                logoutLocal();

            }


            // ==========================================
            // Logout
            // ==========================================

            $('#logoutButton').on('click', function() {

                const button = $(this);

                button.prop('disabled', true);

                button.html(`
                    <span
                        class="spinner-border spinner-border-sm me-2"
                        role="status">
                    </span>

                    Logging Out...
                `);


                $.ajax({

                    url: "{{ url('/api/logout') }}",

                    type: "POST",

                    headers: {

                        'Accept': 'application/json',

                        'Authorization': 'Bearer ' +
                            localStorage.getItem('auth_token')

                    },


                    // ==========================================
                    // Success
                    // ==========================================

                    success: function(response) {

                        // console.log(
                        //     'Logout Response:',
                        //     response
                        // );


                        // Remove local authentication data
                        localStorage.removeItem(
                            'auth_token'
                        );

                        localStorage.removeItem(
                            'user'
                        );


                        // Redirect
                        window.location.href = "/login";

                    },


                    // ==========================================
                    // Error
                    // ==========================================

                    error: function(xhr) {

                        // console.log(
                        //     'Logout Error:',
                        //     xhr
                        // );


                        /*
                         * Even if the API returns an error,
                         * remove the local token.
                         *
                         * This prevents the user from being
                         * stuck on the dashboard.
                         */

                        localStorage.removeItem(
                            'auth_token'
                        );

                        localStorage.removeItem(
                            'user'
                        );


                        window.location.href = "/login";

                    }

                });

            });


            // ==========================================
            // Local Logout
            // ==========================================

            function logoutLocal() {

                localStorage.removeItem(
                    'auth_token'
                );

                localStorage.removeItem(
                    'user'
                );

                window.location.href = "/login";
            }

        });
    </script>

</body>

</html>