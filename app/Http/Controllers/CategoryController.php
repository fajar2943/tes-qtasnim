<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view('category.index', compact('categories'));
    }

    public function store(Request $request){
        $this->_validation($request);
        $category = Category::create($request->all());
        return redirect()->back()->with('success', 'Data created successfully!');
    }
    
    public function edit($id){
        $category = Category::find($id);
        return view('category.edit',compact('category'));
    }

    public function update(Request $request){
        $this->_validation($request);
        $category = Category::find($request->id);
        $category->update(['name' => $request->name]);
        return redirect()->back()->with('success', 'Data updated successfully!');
    }

    public function destroy(Request $request){
        $category = Category::find($request->id);
        if(isset($category->products[0])){
            return redirect()->back()->with('message', 'Cant be delete, Data is Used!');
        }
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted!');
    }

    private function _validation(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255'
        ]);
    }
}
