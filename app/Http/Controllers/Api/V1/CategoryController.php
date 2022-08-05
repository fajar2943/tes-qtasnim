<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return response()->json([
            'message' => 'Success',
            'data' => $categories,
        ], Response::HTTP_OK);
    }

    public function store(Request $request){
        $this->_validation($request);
        $category = Category::create($request->all());
        return response()->json([
            'message' => 'Success, Create category data!',
            'data' => $category,
        ], Response::HTTP_CREATED);
    }

    public function show($id){
        $category = Category::find($id);
        return response()->json([
            'message' => 'Success, Show category data!',
            'data' => new CategoryResource($category)
        ], Response::HTTP_OK);
    }
    
    public function edit($id){
        $category = Category::find($id);
        return response()->json([
            'message' => 'Success, Get category data!',
            'data' => $category,
        ], Response::HTTP_OK);
    }

    public function update(Request $request){
        $this->_validation($request);
        $category = Category::find($request->id);
        $category->update(['name' => $request->name]);
        return response()->json([
            'message' => 'Success, Update category data!',
            'data' => $category,
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request){
        $category = Category::find($request->id);
        if(isset($category->products[0])){
            return response()->json([
                'message' => 'Cant be delete, Data is Used!'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $category->delete();
        return response()->json([
            'message' => 'Success, Category deleted!',
        ], Response::HTTP_OK);
    }

    private function _validation(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255'
        ]);
    }
}
