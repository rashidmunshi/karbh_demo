<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;
use Illuminate\Support\Facades\Storage;

class TodoController extends Controller
{
    public function index()
    {
        $todos  = Todo::all();
        return view('todos.index', compact('todos'));
    }

    public function store(TodoRequest $todoRequest)
    {
        $todo = new Todo();
        $todo->name = $todoRequest->name;
        $todo->user_id = auth()->id();
        $todo->description = $todoRequest->description;

        if ($todoRequest->hasFile('image')) {
            $image = $todoRequest->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            Storage::putFileAs('public/images', $image, $imageName);
            $todo->image = 'storage/images/' . $imageName; // Modify this path as needed
        }
        $todo->save();
        return response()->json(['message' => 'Todo created successfully', 'todo' => $todo]);
    }

    public function edit($id){
        $todo = Todo::findOrFail($id);
        return view('todos.edit', compact('todo'));

    }


    public function update(Request $request, $id)
    {

        $todo = Todo::findOrFail($id);

        $todo->name = $request->input('name');
        $todo->description = $request->input('description');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $todo->image = 'images/' . $imageName;
        }

        $todo->update();

        return response()->json(['message' => 'Todo updated successfully', 'todo' => $todo]);
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
}


//task
//task form - Task name, Task Description , Task Image (crud)
//three type status =  pending (default) , in progress , completed
// add and edit popup form
//