@extends('layouts.app')

@section('title', 'New post')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center">Add New Post</h3>
                        <form id="addNewPost">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input class="form-control" type="text" name="title" id="title"
                                    placeholder="Enter Your Title">
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" name="content" id="content" placeholder="Enter Your Content" rows="3"></textarea>
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="image" name="image">
                                <label class="input-group-text" for="image">Upload</label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Add New Post</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#addNewPost').submit(function(event) {
                event.preventDefault();

                let formData = new FormData();
                formData.append('title', $('#title').val());
                formData.append('content', $('#content').val());
                let image = $('#image')[0].files[0];

                if (!image) {
                    alert("Please select an image file.");
                    return;
                }

                formData.append('image', image); // Append image to FormData

                let token = localStorage.getItem('api_token');

                $.ajax({
                    url: '/api/posts',
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    processData: false, //  FormData
                    contentType: false, // Content-Type
                    data: formData,
                    success: function(response) {
                        // console.log('Post created:', response);
                        alert("Post created successfully!");
                        window.location.href = "/index"
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr.responseText);
                        alert("Failed to create post: " + xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
