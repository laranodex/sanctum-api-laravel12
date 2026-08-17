<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Post</title>

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

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">

            <a href="/posts" class="navbar-brand">
                <i class="bi bi-speedometer2 me-2"></i>
                My Dashboard
            </a>

            <div>
                <a href="/posts" class="btn btn-outline-light me-2">
                    <i class="bi bi-arrow-left me-1"></i>
                    Posts
                </a>

                <button
                    type="button"
                    id="logoutButton"
                    class="btn btn-danger">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Logout
                </button>
            </div>

        </div>
    </nav>


    <!-- Content -->
    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-md-8 col-lg-7">

                <div class="card shadow-sm border-0">

                    <div class="card-body p-4">

                        <h3 class="mb-1">
                            <i class="bi bi-plus-circle me-2"></i>
                            Create Post
                        </h3>

                        <p class="text-muted mb-4">
                            Create a new post.
                        </p>


                        <div id="postMessage"></div>


                        <form id="createPostForm">

                            <!-- Title -->
                            <div class="mb-3">

                                <label
                                    for="title"
                                    class="form-label">
                                    Title
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="title"
                                    name="title"
                                    placeholder="Enter post title"
                                    required>

                            </div>


                            <!-- Description -->
                            <div class="mb-3">

                                <label
                                    for="description"
                                    class="form-label">
                                    Description
                                </label>

                                <textarea
                                    class="form-control"
                                    id="description"
                                    name="description"
                                    rows="5"
                                    placeholder="Enter post description"
                                    required></textarea>

                            </div>


                            <!-- Image -->
                            <div class="mb-4">

                                <label
                                    for="image"
                                    class="form-label">
                                    Image
                                </label>

                                <input
                                    type="file"
                                    class="form-control"
                                    id="image"
                                    name="image"
                                    accept=".png,.jpg,.jpeg,.gif"
                                    required>

                                <small class="text-muted">
                                    PNG, JPG, JPEG or GIF. Maximum 2 MB.
                                </small>

                            </div>


                            <!-- Buttons -->
                            <div class="d-flex gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    id="createPostButton">

                                    <i class="bi bi-plus-circle me-1"></i>
                                    Create Post

                                </button>

                                <a
                                    href="/posts"
                                    class="btn btn-secondary">

                                    Cancel

                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <script>
        $(document).ready(function() {

            const token = localStorage.getItem('auth_token');


            // Check login
            if (!token) {

                window.location.href = "/login";

                return;
            }


            // ==========================================
            // Create Post
            // ==========================================

            $('#createPostForm').on('submit', function(e) {

                e.preventDefault();


                const button = $('#createPostButton');


                $('#postMessage').html('');


                const formData = new FormData(this);


                button.prop('disabled', true);

                button.html(`
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Creating...
                `);


                $.ajax({

                    url: "{{ url('/api/posts') }}",

                    type: "POST",

                    headers: {

                        'Accept': 'application/json',

                        'Authorization': 'Bearer ' + token

                    },

                    data: formData,

                    processData: false,

                    contentType: false,


                    success: function(response) {

                        $('#postMessage').html(`
                            <div class="alert alert-success">
                                ${response.message}
                            </div>
                        `);


                        $('#createPostForm')[0].reset();


                        setTimeout(function() {

                            window.location.href = "/posts";

                        }, 1000);

                    },


                    error: function(xhr) {

                        console.log(
                            'Create Post Error:',
                            xhr
                        );


                        showErrors(xhr);

                    },


                    complete: function() {

                        button.prop('disabled', false);

                        button.html(`
                            <i class="bi bi-plus-circle me-1"></i>
                            Create Post
                        `);

                    }

                });

            });


            // ==========================================
            // Logout
            // ==========================================

            $('#logoutButton').on('click', function() {

                $.ajax({

                    url: "{{ url('/api/logout') }}",

                    type: "POST",

                    headers: {

                        'Accept': 'application/json',

                        'Authorization': 'Bearer ' + token

                    },

                    complete: function() {

                        localStorage.removeItem(
                            'auth_token'
                        );

                        localStorage.removeItem(
                            'user'
                        );

                        window.location.href =
                            "/login";

                    }

                });

            });


            // ==========================================
            // Show Errors
            // ==========================================

            function showErrors(xhr) {

                let message =
                    'Something went wrong.';


                if (xhr.responseJSON) {

                    message =
                        xhr.responseJSON.message ||
                        message;


                    if (xhr.responseJSON.errors) {

                        message = '<ul class="mb-0">';

                        $.each(
                            xhr.responseJSON.errors,
                            function(field, messages) {

                                $.each(
                                    messages,
                                    function(index, error) {

                                        message +=
                                            `<li>${error}</li>`;

                                    }
                                );

                            }
                        );

                        message += '</ul>';

                    }

                }


                $('#postMessage').html(`
                    <div class="alert alert-danger">
                        ${message}
                    </div>
                `);

            }

        });
    </script>

</body>

</html>
