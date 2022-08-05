<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductCollection;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index(){
        $products = new ProductCollection(Product::all());
        return response()->json([
            'message' => 'Success',
            'data' => $products,
        ], Response::HTTP_OK);
    }

    public function store(Request $request){
        $this->_validation($request);
        $product = Product::create($request->all());
        return response()->json([
            'message' => 'Success, Create product data!',
            'data' => $product,
        ], Response::HTTP_CREATED);
    }

    public function show($id){
        $product = Product::find($id);
        return response()->json([
            'message' => 'Success, Show product data!',
            'data' => new ProductResource($product)
        ], Response::HTTP_OK);
    }

    public function edit($id){
        $product = Product::find($id);
        return response()->json([
            'message' => 'Success, Get product data!',
            'data' => new ProductResource($product),
        ], Response::HTTP_OK);
    }

    public function update(Request $request){
        $this->_validation($request);
        $product = Product::find($request->id);
        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'stock' => $request->stock
        ]);
        return response()->json([
            'message' => 'Success, Update product data!',
            'data' => $product,
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request){
        $product = Product::find($request->id);
        if(isset($product->transactions[0])){
            return response()->json([
                'message' => 'Cant be delete, Data is Used!'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $product->delete();
        return response()->json([
            'message' => 'Success, Product deleted!'
        ], Response::HTTP_OK);
    }

    private function _validation(Request $request){
        $this->validate($request, [
            'category_id' => 'required|max:255',
            'name' => 'required|max:255',
            'stock' => 'required|numeric'
        ]);
    }
}
