<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos  = Todo::all();
        return view('todos.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $todo = Todo::create([
            'user_id' => auth()->id(),
            'title' => $request->input('title'),
        ]);

        return response()->json($todo);
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
