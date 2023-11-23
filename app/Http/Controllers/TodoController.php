<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;

class TodoController extends Controller
{
    public function index()
    {
        $todos  = Todo::all();
        return view('todos.index', compact('todos'));
    }

    public function store(TodoRequest $todoRequest)
    {

        dd($todoRequest->toArray());
        $imagePath = $todoRequest->file('image')->store('todo_images');

        $todo = $todoRequest->validated();
        $todo['user_id'] = auth()->id();
        $todo ['image'] = $imagePath;
        
        $todos = Todo::create($todo);
        return response()->json($todos);
    }
    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
        ]);
    
        $todo->title = $request->input('title');
        $todo->update();


        return response()->json(['message' => 'Todo title updated']);
    }

    public function destroy($id)
    {
        Todo::findorfail($id)->delete();

        return response()->json(['message' => 'Todo deleted']);
    }

    public function search(Request $request)
    {
        $searchText = $request->input('search');

        $todos = Todo::where('title', 'like', '%' . $searchText . '%')->get();

        return response()->json(['todos' => $todos]);
    }
}


//task
//task form - Task name, Task Description , Task Image (crud)
//three type status =  pending (default) , in progress , completed
// add and edit popup form
//