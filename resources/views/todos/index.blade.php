<x-app-layout>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <label>Search By:</label>
                <input type="text" class="form-control mb-3" id="search-todo" placeholder="Search To-Do">
            </div>
        </div>

        <form class="mt-4" id="add-todo-form">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control mb-3" id="todo-title" placeholder="Add new todo">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-info">Add</button>
                </div>
            </div>
        </form>

        <ul class="list-group" id="todo-list">
            @foreach ($todos as $todo)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <span class="ms-2 todo-title" data-id="{{ $todo->id }}">{{ $todo->title }}</span>
                    </div>
                    <button class="btn btn-danger delete-todo" data-id="{{ $todo->id }}"><i class="fa-solid fa-trash"></i></button>
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
            var title = $('#todo-title').val();

            $.ajax({
                url: '/store',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    title: title
                },
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
                    toastr.success('Todo Added successfully');
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
