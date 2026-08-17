<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Posts</title>

    <!-- Bootstrap -->
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

            <a class="navbar-brand fw-bold" href="/dashboard">
                <i class="bi bi-speedometer2 me-2"></i>
                My Dashboard
            </a>

            <div class="d-flex align-items-center gap-2">

                <a href="/dashboard" class="btn btn-outline-light">
                    <i class="bi bi-house me-1"></i>
                    Dashboard
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


    <!-- ========================================== -->
    <!-- Main -->
    <!-- ========================================== -->

    <main class="container py-5">

        <!-- Header -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">
                    Posts
                </h2>

                <p class="text-muted mb-0">
                    Manage your posts.
                </p>

            </div>


            <a href="/posts/create" class="btn btn-primary">

                <i class="bi bi-plus-lg me-2"></i>

                Create Post

            </a>

        </div>


        <!-- Message -->

        <div id="postMessage"></div>


        <!-- Loading -->

        <div id="loading" class="text-center py-5">

            <div
                class="spinner-border"
                role="status">
            </div>

            <p class="text-muted mt-2">
                Loading posts...
            </p>

        </div>


        <!-- Posts -->

        <div
            id="postsContainer"
            class="row g-4">

        </div>


        <!-- No Posts -->

        <div
            id="noPosts"
            class="card border-0 shadow-sm text-center d-none">

            <div class="card-body py-5">

                <i
                    class="bi bi-file-earmark-text display-4 text-muted">
                </i>

                <h5 class="mt-3">
                    No posts found
                </h5>

                <p class="text-muted">
                    Create your first post.
                </p>

                <a
                    href="/posts/create"
                    class="btn btn-primary">

                    Create Post

                </a>

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
            // Token
            // ==========================================

            const token =
                localStorage.getItem('auth_token');


            // ==========================================
            // Check Login
            // ==========================================

            if (!token) {

                window.location.href = "/login";

                return;
            }


            // ==========================================
            // Load Posts
            // ==========================================

            loadPosts();


            function loadPosts() {

                $.ajax({

                    url: "{{ url('/api/posts') }}",

                    type: "GET",

                    headers: {

                        'Accept': 'application/json',

                        'Authorization': 'Bearer ' + token

                    },


                    // ==========================================
                    // Success
                    // ==========================================

                    success: function(response) {

                        console.log(
                            'Posts:',
                            response
                        );


                        $('#loading').addClass('d-none');


                        let posts =
                            response.data.data || [];


                        if (posts.length === 0) {

                            $('#noPosts')
                                .removeClass('d-none');

                            return;
                        }


                        let html = '';


                        $.each(posts, function(index, post) {

                            html += `

                                <div class="col-md-6 col-lg-4">

                                    <div class="card shadow-sm border-0 h-100">

                                       <img
                                            src="${post.image_url}"
                                            class="card-img-top"
                                            alt="${escapeHtml(post.title)}"
                                            style="width: 100%; height: 230px; object-fit: cover;"
                                        >
                                        <div class="card-body">

                                            <h5 class="card-title">
                                                ${escapeHtml(post.title)}
                                            </h5>

                                            <p class="card-text text-muted">
                                                ${escapeHtml(post.description ?? '')}
                                            </p>

                                        </div>

                                        <div class="card-footer bg-white border-0">

                                            <div class="d-flex gap-2">

                                                <a
                                                    href="/posts/${post.id}/edit"
                                                    class="btn btn-sm btn-warning">

                                                    <i class="bi bi-pencil"></i>
                                                    Edit

                                                </a>

                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-danger deletePost"
                                                    data-id="${post.id}">

                                                    <i class="bi bi-trash"></i>
                                                    Delete

                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            `;

                        });


                        $('#postsContainer')
                            .html(html);

                    },


                    // ==========================================
                    // Error
                    // ==========================================

                    error: function(xhr) {

                        console.log(
                            'Posts Error:',
                            xhr
                        );


                        $('#loading').addClass('d-none');


                        if (xhr.status === 401) {

                            localStorage.removeItem(
                                'auth_token'
                            );

                            localStorage.removeItem(
                                'user'
                            );

                            window.location.href =
                                "/login";

                            return;
                        }


                        $('#postMessage').html(`

                            <div class="alert alert-danger">
                                Unable to load posts.
                            </div>

                        `);

                    }

                });

            }


            // ==========================================
            // Delete Post
            // ==========================================

            $(document).on(
                'click',
                '.deletePost',
                function() {

                    const postId =
                        $(this).data('id');


                    if (!confirm(
                            'Are you sure you want to delete this post?'
                        )) {

                        return;
                    }


                    $.ajax({

                        url: "{{ url('/api/posts') }}/" +
                            postId,

                        type: "DELETE",

                        headers: {

                            'Accept': 'application/json',

                            'Authorization': 'Bearer ' + token

                        },


                        success: function(response) {

                            $('#postMessage').html(`

                                <div class="alert alert-success">
                                    ${response.message}
                                </div>

                            `);


                            loadPosts();

                        },


                        error: function(xhr) {

                            console.log(
                                'Delete Error:',
                                xhr
                            );

                            $('#postMessage').html(`

                                <div class="alert alert-danger">
                                    Unable to delete post.
                                </div>

                            `);

                        }

                    });

                }
            );


            // ==========================================
            // Logout
            // ==========================================

            $('#logoutButton').click(function() {

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
            // Escape HTML
            // ==========================================

            function escapeHtml(value) {

                return $('<div>')
                    .text(value ?? '')
                    .html();

            }

        });
    </script>

</body>

</html>