<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        $categories = Category::all();
        return view('product.index', compact('products', 'categories'));
    }

    public function store(Request $request){
        $this->_validation($request);
        $product = Product::create($request->all());
        return redirect()->back()->with('success', 'Data created successfully!');
    }

    public function edit($id){
        $product = Product::find($id);
        $categories = Category::all();
        return view('product.edit',compact('product', 'categories'));
    }

    public function update(Request $request){
        $this->_validation($request);
        $product = Product::find($request->id);
        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'stock' => $request->stock
        ]);
        return redirect()->back()->with('success', 'Data updated successfully!');
    }

    public function destroy(Request $request){
        $product = Product::find($request->id);
        if(isset($product->transactions[0])){
            return redirect()->back()->with('message', 'Cant be delete, Data is Used!');
        }
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted!');
    }

    private function _validation(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255'
        ]);
    }
}
