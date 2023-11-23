<x-app-layout>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <label>Search By:</label>
                <input type="text" class="form-control mb-3" id="search-todo" placeholder="Search To-Do">
            </div>
        </div>
        <!-- Updated HTML form -->

        <button type="button" class="btn btn-primary float-left" data-bs-toggle="modal" data-bs-target="#addTodoModal">
            Add To-Do
        </button>

       <!-- Bootstrap Modal -->
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


        <ul class="list-group" id="todo-list">
            @foreach ($todos as $todo)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <span class="ms-2 todo-title" data-id="{{ $todo->id }}">{{ $todo->title }}</span>
                    </div>
                    <button class="btn btn-danger delete-todo" data-id="{{ $todo->id }}"><i
                            class="fa-solid fa-trash"></i></button>
                </li>
            @endforeach
        </ul>

    </div>


</x-app-layout>

<script>
    $(document).ready(function() {

        function showSuccessToast(message) {
            toastr.success(message);
        }
        $('#add-todo-form').submit(function(event) {
    event.preventDefault();

    var formData = new FormData($(this)[0]);
    $.ajax({
        url: '/store',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            $('#todo-list').append(`
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <span class="ms-2 todo-title" data-id="${data.id}">${data.title}</span>
                    </div>
                    <button class="btn btn-danger delete-todo" data-id="${data.id}"><i class="fa-solid fa-trash"></i></button>
                </li>
            `);
            $('#todo-title').val('');
            $('#todo-description').val('');
            $('#todo-image').val('');
            toastr.success('To-Do added successfully');
        },
        error: function() {
            toastr.error('Failed to add To-Do.');
        }
    });
});


        $(document).on('dblclick', '.todo-title', function() {
            var todoId = $(this).data('id');
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
                 <div>
                   
                    <span class="ms-2 todo-title" data-id="${todo.id}">${todo.title}</span>
                </div>
                <button class="btn btn-danger delete-todo" data-id="${todo.id}"><i class="fa-solid fa-trash"></i></button>
            </li>
                    `);
                    });
                }
            });
        });
    })
</script>
