<x-app-layout>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <label>Search By:</label>
                <input type="text" class="form-control mb-3" id="search-todo" placeholder="Search To-Do">
            </div>
        </div>
        <!-- Updated HTML form -->

        <div class="modal fade" id="editTodoModal" tabindex="-1" aria-labelledby="editTodoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTodoModalLabel">Edit To-Do</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-todo-form" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="editTodoId">
                            <div class="mb-3">
                                <label for="edit-todo-name" class="form-label">To-Do Name:</label>
                                <input type="text" id="edit-todo-name" name="name" placeholder="To-Do Name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="edit-todo-description" class="form-label">To-Do Description:</label>
                                <textarea id="edit-todo-description" name="description" placeholder="To-Do Description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit-todo-image" class="form-label">Upload Image:</label>
                                <input type="file" id="edit-todo-image" name="image" class="form-control">
                                <div class="mb-3">
                                    <img id="edit-todo-image-preview" src="#" alt="Preview" style="max-width: 100px; max-height: 100px;">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="edit-todo-form" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex justify-content-end my-3">
            <!-- Updated HTML form -->
            <button type="button" class="btn btn-primary float-left" data-bs-toggle="modal" data-bs-target="#addTodoModal">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <!-- Bootstrap Modal -->
        <div class="modal fade" id="addTodoModal" tabindex="-1" aria-labelledby="addTodoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTodoModalLabel">Add To-Do</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="add-todo-form" method="post" enctype="multipart/form-data" data-parsley-validate>
                            @csrf
                            <div class="mb-3">
                                <label for="todo-name" class="form-label">To-Do Name:</label>
                                <input type="text" id="todo-name" name="name" placeholder="To-Do Name" class="form-control" required data-parsley-trigger="change" data-parsley-required-message="Please enter the To-Do Name">
                            </div>
                            <div class="mb-3">
                                <label for="todo-description" class="form-label">To-Do Description:</label>
                                <textarea id="todo-description" name="description" placeholder="To-Do Description" class="form-control" required data-parsley-trigger="change" data-parsley-required-message="Please enter the To-Do Description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="todo-image" class="form-label">Upload Image:</label>
                                <input type="file" id="todo-image" name="image" class="form-control" required data-parsley-trigger="change" data-parsley-required-message="Please upload an image">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="add-todo-form" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <ul class="list-group" id="todo-list">
            @foreach ($todos as $td)
            <li class="list-group-item d-flex justify-content-between align-items-center" id="todo-li" data-id="{{ $td->id }}">
                <div class="d-flex align-items-center">
                    <img src="{{ asset($td->image) }}" alt="{{ $td->name }} Image" class="me-3" style="max-width: 100px; max-height: 100px;">

                    <div>
                        <h5 class="mb-0">Name :{{ $td->name }}</h5>
                        <p class="mb-0">Description :{{ $td->description }}</p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="status-chip mt-2 me-3">
                        @if ($td->todo_status == 'pending')
                        <button class="badge bg-warning text-dark change-status" data-id="{{ $td->id }}" data-status="in_progress">Pending</button>
                        @elseif($td->todo_status == 'in_progress')
                        <button class="badge bg-primary text-white change-status" data-id="{{ $td->id }}" data-status="completed">In Progress</button>
                        @elseif($td->todo_status == 'completed')
                        <button class="badge bg-success change-status" data-id="{{ $td->id }}" data-status="pending">Completed</button>
                        @endif
                    </div>

                    <button class="btn btn-success me-2 edit-todo" data-id="{{ $td->id }}"><i class="fas fa-edit"></i></button>

                    <button class="btn btn-danger delete-todo" data-id="{{ $td->id }}"><i class="fas fa-trash-alt"></i></button>
                </div>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="modal fade" id="addTodoModal" tabindex="-1" aria-labelledby="addTodoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTodoModalLabel">Add To-Do</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-todo-form" method="post">
                        @csrf
                        <input type="text" id="todo-name" name="name" placeholder="To-Do Name" class="form-control">
                        <textarea id="todo-description" name="description" placeholder="To-Do Description" class="form-control"></textarea>
                        <input type="file" id="todo-image" name="image">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="add-todo-form" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    $(document).ready(function() {

        $(document).ready(function() {
            $('#add-todo-form').parsley();
        });

        function showSuccessToast(message) {
            toastr.success(message);
        }
        $('#add-todo-form').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: '/store'
                , type: 'POST'
                , data: formData
                , contentType: false
                , processData: false
                , success: function(response) {
                    var imagePath = '{{ asset("storage/todo-images/") }}' + '/' + response.todo.image.basename;

                    var newItemHtml =
                        '<li class="list-group-item d-flex justify-content-between align-items-center" id="todo-li" data-id="' +
                        response.todo.id + '">' +
                        '<div class="d-flex align-items-center">' +
                        '<img src="' + imagePath + '" alt="' + response.todo.name +
                        ' Image" class="me-3" style="max-width: 100px; max-height: 100px;">' +
                        '<div>' +
                        '<h5 class="mb-0">Name : ' + response.todo.name + '</h5>' +
                        '<p class="mb-0">Description : ' + response.todo.description + '</p>' +
                        '</div>' +
                        '</div>' +
                        '<div class="d-flex">' +
                        '<div class="status-chip mt-2 me-3">';

                    // Check and add the appropriate status badge based on todo_status
                    if (response.todo.todo_status == 'pending') {
                        newItemHtml += '<button class="badge bg-warning text-dark change-status" data-id="' +
                            response.todo.id + '" data-status="in_progress">Pending</button>';
                    } else if (response.todo.todo_status == 'in_progress') {
                        newItemHtml += '<button class="badge bg-primary text-white change-status" data-id="' +
                            response.todo.id + '" data-status="completed">In Progress</button>';
                    } else if (response.todo.todo_status == 'completed') {
                        newItemHtml += '<button class="badge bg-success change-status" data-id="' +
                            response.todo.id + '" data-status="pending">Completed</button>';
                    }
                    newItemHtml +=
                        '</div>' +
                        '<button class="btn btn-success me-2 edit-todo" data-id="' + response.todo.id +
                        '"><i class="fas fa-edit"></i></button>' +
                        '<button class="btn btn-danger delete-todo" data-id="' + response.todo.id +
                        '"><i class="fas fa-trash-alt"></i></button>' +
                        '</div>' +
                        '</li>';

                    $('#todo-list').prepend(newItemHtml);
                    $('#todo-title').val('');
                    $('#todo-description').val('');
                    $('#todo-image').val('');
                    $('#addTodoModal').modal('hide');
                    toastr.success('Todo Added successfully');
                }
                , error: function() {
                    toastr.error('Failed to add Todo.');
                }
            });
        });

        $('.edit-todo').click(function() {
            var todoId = $(this).data('id');

            // AJAX request to fetch the todo data
            $.ajax({
                url: '/edit/' + todoId
                , type: 'GET'
                , success: function(response) {

                    $('#edit-todo-name').val(response.name);
                    $('#edit-todo-description').val(response.description);


                    $('#edit-todo-image-preview').attr('src', response.image);


                    $('#edit-todo-name').addClass('form-control-updated');
                    $('#edit-todo-description').addClass('form-control-updated');
                    $('#editTodoId').val(response.id)

                    // Show the modal
                    $('#editTodoModal').modal('show');
                }
            , });
        });


        $('#edit-todo-form').submit(function(e) {
            e.preventDefault();
            var todoId = $('#editTodoId').val();

            var formData = new FormData(this);

            $.ajax({
                url: '/update/' + todoId
                , type: 'POST'
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , data: formData
                , contentType: false
                , processData: false
                , success: function(response) {
                    console.log(response);
                    $('#editTodoModal').modal('hide');
                    var listItem = $('#todo-li[data-id="' + todoId + '"]');
                    listItem.find('h5').text('Name: ' + response.todo.name);
                    listItem.find('p').text('Description: ' + response.todo.description);


                    if (response.todo.image) {
                        listItem.find('img').attr('src', response.todo.image);
                    }
                    toastr.success('Todo Updated successfully');
                }
                , error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });


        $(document).on('click', '.delete-todo', function() {
            var id = $(this).data('id');
            var listItem = $(this).closest('li');

            $.ajax({
                url: `/delete/${id}`
                , type: 'DELETE'
                , data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
                , success: function() {
                    listItem.remove();
                    toastr.success('Todo deleted successfully!');
                }
            });
        });



        $('#search-todo').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();

            $.ajax({
                url: '/search'
                , type: 'GET'
                , data: {
                    search: searchText
                }
                , success: function(data) {
                    var filteredTodos = data.todos;

                    var todoList = $('#todo-list');
                    todoList.empty();


                    filteredTodos.forEach(function(todo) {
                        todoList.append(`
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="${todo.image}" alt="${todo.name} Image" class="me-3" style="max-width: 100px; max-height: 100px;">
                            <div>
                                <h5 class="mb-0">Name: ${todo.name}</h5>
                                <p class="mb-0">Description: ${todo.description}</p>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-success me-2 edit-todo" data-id="${todo.id}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger delete-todo" data-id="${todo.id}"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </li>
                `);
                    });
                }
            });
        });


        $('.change-status').click(function() {
            var button = $(this);
            var todoId = $(this).data('id');
            var newStatus = $(this).data('status');
            console.log(status);


            $.ajax({
                url: "{{ route('update-status') }}"
                , type: 'POST'
                , data: {
                    todo_id: todoId
                    , status: newStatus
                    , _token: $('meta[name="csrf-token"]').attr('content')
                }
                , success: function(response) {
                    console.log(response);
                    if (newStatus === 'in_progress') {
                        button.removeClass('bg-warning text-dark').addClass(
                            'bg-primary text-white').text('In Progress');
                        button.data('status', 'completed');
                    } else if (newStatus === 'completed') {
                        button.removeClass('bg-primary text-white').addClass('bg-success')
                            .text('Completed');
                        button.data('status', 'pending');
                    } else {
                        button.removeClass('bg-success').addClass('bg-warning text-dark')
                            .text('Pending');
                        button.data('status', 'in_progress');
                    }
                }
            })
        })
    });

</script>
