@extends('layouts.app')

@section('title', 'view')

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class=" button-group mb-3">
                    <a href="{{ route('addPost') }}" class="btn btn-success"> Add New Post</a>
                    <button id="logoutButton" class="btn btn-primary">Logout</button>
                </div>
                <div id="posts">

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Single Post -->
    <div class="modal fade" id="singlePostModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="singlePostLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="singlePostLabel">View Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="singlePost"></div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <!-- Update Post Modal -->

    <div class="modal fade" id="updatePostModel" tabindex="-1" aria-labelledby="updatePostModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePostModelLabel">Update Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updatePostForm">
                        <input type="hidden" id="postId">

                        <div class="mb-3">
                            <label for="updateTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="updateTitle">
                        </div>
                        <div class="mb-3">
                            <label for="updateContent" class="form-label">Content</label>
                            <textarea class="form-control" id="updateContent"></textarea>
                        </div>

                        <div class="mb-3">
                            <img id="imagePreview" alt="Old Image" width="100px">
                        </div>

                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="updateImage" name="image">
                            <label class="input-group-text" for="updateImage">Upload</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        document.querySelector('#logoutButton').addEventListener('click', function() {
            const token = localStorage.getItem('api_token');

            if (!token) {
                console.warn('No token found, redirecting...');
                window.location.href = "http://localhost:8000/";
                return;
            }

            fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Logout failed');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Logout successful:', data);
                    localStorage.removeItem('api_token'); //token remove
                    window.location.href = "http://localhost:8000/";
                })

        });

        //////////////////////////////////

        // data load
        function loadData() {
            const token = localStorage.getItem('api_token');
            fetch('api/posts', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                    }
                })
                // response json data get
                .then(response => response.json())
                .then(data => {
                    // console.log(data.posts);
                    // all posts get
                    var allPost = data.posts;
                    // data show
                    const postController = document.querySelector('#posts');
                    // table start
                    let table = ` <table class=" table table-striped table-hover">
                        <tr>
                          <td>title</td>
                            <td class= "col-4">content</td>
                            <td class= "col-3">image</td>
                            <td>action</td>
                            </tr>
                            `;
                    allPost.forEach(post => {
                        table += `
                        <tr>
                            <td>${post.title}</td>
                            <td>${post.content}</td>
                            <td>

                                 <img src="images/${post.image}" alt="image"
                         onerror="this.onerror=null; this.src='default-image.jpg';"
                         width="100">
                                </td>
                            <td>
                                <button class ="btn btn-primary" data-bs-post="${post.id}"  data-bs-toggle="modal" data-bs-target="#singlePostModel" >view</button>

                                <button class="btn btn-success" data-bs-post="${post.id}" data-bs-toggle="modal" data-bs-target="#updatePostModel">Update</button>

                                <button class="btn btn-danger" id="deletePost" data-bs-post="${post.id}">Delete</button>

                                </td>
                         </tr>`;
                    });
                    // table end
                    table += `</table>`;
                    // table show
                    postController.innerHTML = table;
                })
                // error catch
                .catch(error => {
                    console.error('Error:', error);

                });
        }

        loadData();
        //////////////////////





        //// single post view

        let singlePostModel = document.querySelector('#singlePostModel');

        singlePostModel.addEventListener('show.bs.modal', function(event) {
            const id = event.relatedTarget.getAttribute('data-bs-post');
            const token = localStorage.getItem('api_token');

            fetch(`/api/posts/${id}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.post);
                    const post = data.post;
                    const modalBody = singlePostModel.querySelector('.modal-body');

                    modalBody.innerHTML = `
            <h5>${post.title}</h5>
            <p>${post.content}</p>
            <img src="images/${post.image}" alt="image"
                 onerror="this.onerror=null; this.src='default-image.jpg'" width="150">
        `;
                })
                .catch(error => console.error('Error fetching post:', error));
        });
        // end single post view




        ///////////////////


        let updatePostModel = document.querySelector('#updatePostModel');

        updatePostModel.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-bs-post');
            const token = localStorage.getItem('api_token');

            fetch(`/api/posts/${id}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token, // Fixed Authorization header
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const post = data.post; // Fixed typo

                    // Update modal fields
                    document.querySelector('#postId').value = post.id;
                    document.querySelector('#updateTitle').value = post.title;
                    document.querySelector('#updateContent').value = post.content;
                    document.querySelector('#imagePreview').src = `images/${post.image}`;

                })

        });

        ///////////////////////

        // update post .. jquery code

        $(document).ready(function() {
            $('#updatePostForm').submit(function(event) {
                event.preventDefault();

                let formData = new FormData();
                formData.append('title', $('#updateTitle').val());
                formData.append('content', $('#updateContent').val());


                let image = $('#updateImage')[0].files[0];
                if (image) {
                    formData.append('image', image);
                }

                let postId = $('#postId').val();
                let token = localStorage.getItem('api_token');

                $.ajax({
                    // url: '/api/posts/' + postId, // ababe o hoy

                    url: `/api/posts/${postId}`, // Update API with post ID

                    method: 'POST', // Laravel PUT override করে
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'X-HTTP-Method-Override': 'PUT' // PUT override
                    },
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response) {
                        alert("Post updated successfully!");
                        $('#updatePostModel').modal('hide'); // Modal hide after update
                        loadData(); // Reload data
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr.error);
                        alert("Failed to update post: " + xhr.error);

                    }
                });
            });
        });

        //// delete post .. jquery code


        $(document).on('click', '#deletePost', function() {
            let postId = $(this).attr('data-bs-post');
            let token = localStorage.getItem('api_token');

            $.ajax({
                url: '/api/posts/' + postId,
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json',
                },
                success: function(response) {
                    alert("Post Delete Successful");
                    loadData(); // Assuming this reloads the posts
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert("Failed to delete post: " + xhr.responseText);
                }
            });
        });
    </script>



@endsection
