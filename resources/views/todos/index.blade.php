<x-app-layout>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <label>Search By:</label>
                <input type="text" class="form-control mb-3" id="search-todo" placeholder="Search To-Do">
            </div>
        </div>
        <!-- Updated HTML form -->




        


        <div class="d-flex justify-content-end my-3">
            <!-- Updated HTML form -->
            <button type="button" class="btn btn-primary float-left" data-bs-toggle="modal"
                data-bs-target="#addTodoModal">
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
                        <form id="add-todo-form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="todo-name" class="form-label">To-Do Name:</label>
                                <input type="text" id="todo-name" name="name" placeholder="To-Do Name"
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="todo-description" class="form-label">To-Do Description:</label>
                                <textarea id="todo-description" name="description" placeholder="To-Do Description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="todo-image" class="form-label">Upload Image:</label>
                                <input type="file" id="todo-image" name="image" class="form-control">
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
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset($td->image) }}" alt="{{ $td->name }} Image" class="me-3"
                            style="max-width: 100px; max-height: 100px;">

                        <div>
                            <h5 class="mb-0">Name :{{ $td->name }}</h5>
                            <p class="mb-0">Description :{{ $td->description }}</p>
                        </div>
                    </div>

                    <div>
                        {{-- /  <button class="btn btn-success me-2 edit-todo" data-id="{{ $td->id }}"><i
                                class="fas fa-edit"></i></button> --}}
                        <!-- Example usage to open the edit modal for a specific Todo -->
                        <a href="{{ route('todos.edit', ['id' => $td->id]) }}" class="btn btn-success me-2 edit-todo"
                            data-bs-toggle="modal" data-bs-target="#editTodoModal" data-id="{{ $td->id }}"><i
                                class="fa-solid fa-edit"></i></a>

                        <button class="btn btn-danger delete-todo" data-id="{{ $td->id }}"><i
                                class="fas fa-trash-alt"></i></button>
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
                        <input type="text" id="todo-name" name="name" placeholder="To-Do Name"
                            class="form-control">
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

        
        function showSuccessToast(message) {
            toastr.success(message);
        }
        $('#add-todo-form').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);


            $.ajax({
                url: '/store',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#todo-list').append(
                        '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                        '<div class="d-flex align-items-center">' +
                        '<img src="' + response.todo.image + '" alt="' + response.todo
                        .name +
                        ' Image" class="me-3" style="max-width: 100px; max-height: 100px;">' +
                        '<div>' +
                        '<h5 class="mb-0">Name :' + response.todo.name + '</h5>' +
                        '<p class="mb-0">Description :' + response.todo.description +
                        '</p>' +
                        '</div>' +
                        '</div>' +
                        '<div>' +
                        '<button class="btn btn-success me-2 edit-todo" data-id="' +
                        response.todo.id + '"><i class="fas fa-edit"></i></button>' +
                        '<button class="btn btn-danger delete-todo" data-id="' +
                        response.todo.id +
                        '"><i class="fas fa-trash-alt"></i></button>' +
                        '</div>' +
                        '</li>'
                    );


                    $('#todo-title').val('');
                    $('#todo-description').val('');
                    $('#todo-image').val('');
                    $('#addTodoModal').modal('hide'); // Hide modal on success
                    toastr.success('Todo Added successfully');
                },
                error: function() {
                    toastr.error('Failed to add Todo.');
                }
            });
        });





        $(document).on('dblclick', '.todo-title', function() {
            var todoId = $(this).response('id');
            var todoTitle = $(this).text();

            $(this).html(`<input type="text" class="edit-todo" value="${todoTitle}">`);
            $('.edit-todo').focus().select();

            $('.edit-todo').on('blur', function() {
                var newTitle = $(this).val();

                $.ajax({
                    url: `/update/${todoId}`,
                    type: 'post',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        title: newTitle
                    },
                    success: function() {
                        $('.todo-title[data-id="' + todoId + '"]').text(newTitle);
                        toastr.success('Todo updated successfully!');

                    }
                });
            });
        });

        $(document).on('click', '.delete-todo', function() {
            var id = $(this).data('id');
            var listItem = $(this).closest('li');

            $.ajax({
                url: `/delete/${id}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    listItem.remove();
                    toastr.success('Todo deleted successfully!');
                }
            });
        });



        $('#search-todo').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();

            $.ajax({
                url: '/search',
                type: 'GET',
                data: {
                    search: searchText
                },
                success: function(data) {
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
    });
</script>
