<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;

class TodoController extends Controller
{
    public function index()
    {
        $todos  = Todo::latest()->get();
        return view('todos.index', compact('todos'));
    }

    public function store(TodoRequest $todoRequest)
    {
        $validatedData = $todoRequest->validated();

        $todo = new Todo();
        $todo->fill([
            'name' => $validatedData['name'],
            'user_id' => auth()->id(),
            'description' => $validatedData['description'],
        ]);

        if ($todoRequest->hasFile('image')) {
            $todo->image = $this->uploadImage($todoRequest->file('image'));
        }

        $todo->save();
        return response()->json(['message' => 'Todo created successfully', 'todo' => $todo]);
    }

    public function edit($id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['error' => 'Todo not found'], 404);
        }

        return response()->json($todo);
    }


    public function update(Request $request, $id)
    {

        $todo = Todo::findOrFail($id);

        $validatedData = $request->toArray();

        $todo->fill([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
        ]);

        if ($request->hasFile('image')) {
            $todo->image = $this->uploadImage($request->file('image'));
        }

        $todo->save();

        return response()->json(['message' => 'Todo updated successfully', 'todo' => $todo]);
    }


    private function uploadImage($image)
    {
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('public/images', $imageName);
        return 'storage/' . str_replace('public/', '', $path);
    }

    public function destroy($id)
    {
        Todo::findorfail($id)->delete();

        return response()->json(['message' => 'Todo deleted']);
    }

    public function search(Request $request)
    {
        $searchText = $request->input('search');

        $todos = Todo::where('name', 'like', '%' . $searchText . '%')->get();

        return response()->json(['todos' => $todos]);
    }

    public function changeStatus()
    {
        $todo = Todo::findOrFail(request()->todo_id);
        $todo->todo_status = request()->status;
        $todo->save();
    
        return response()->json(['message' => 'Status updated successfully']);
    }
}


//task
//task form - Task name, Task Description , Task Image (crud)
//three type status =  pending (default) , in progress , completed
// add and edit popup form
//