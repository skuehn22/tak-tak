<?php
namespace App\Http\Controllers;

use App\TodoItem;
use Illuminate\Http\Request;

class TestController extends Controller{
    public function index(){
        return view('todos');
    }

    public function todos(Request $request)
    {
        return response()->json(TodoItem::all());
    }

    public function store(Request $request){

        $this->validate($request, [
            'name' => [ 'required',  'string', 'min:5', 'max:50', 'unique:todo_items,name' ]
        ]);

        $todoItem = new TodoItem();
        $todoItem->name = $request->name;
        $todoItem->save();

        return $todoItem;
    }

    public function update(Request $request, TodoItem $todoItem){

        $this->validate($request, [
            'name' => [ 'required', 'string', 'min:5', 'max:50', 'unique:todo_items,name,'.$todoItem->id ]
        ]);

        $todoItem->name = $request->name;
        $todoItem->save();

        return $todoItem;
    }

    public function destroy(Request $request, TodoItem $todoItem)
    {
        $todoItem->delete();
        return response()->json(null, 204);
    }
}
