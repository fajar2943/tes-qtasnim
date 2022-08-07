<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TransactionCollection;
use App\Http\Resources\Api\V1\TransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function index(Request $request){
        if($request->has('search') && $request->search != null){
            if($request->type == 'total_sales'){
                $transactions = Transaction::where('total_sales',$request->search)->get();
            }elseif($request->type == 'date'){
                $transactions = Transaction::whereDate('date', $request->search)->get();
            }else{
                $key = $request->search;
                $transactions = Transaction::whereHas('product', function($productQuery) use ($key){
                    $productQuery->where('name', 'LIKE', '%'.$key.'%' );
                })->get();
            }
        }elseif($request->has('short')){
            if($request->has('from') && $request->has('until') && $request->from != null && $request->until != null){
                $transactions = Transaction::whereBetween('date', [$request->from, $request->until])->orderBy('total_sales', $request->short)->get();
            }else{
                $transactions = Transaction::orderBy('total_sales', $request->short)->get();
            }
        }else{
            $transactions = Transaction::all();
        }
        return response()->json([
            'message' => 'Success',
            'data' => new TransactionCollection($transactions),
        ], Response::HTTP_OK);
    }

    public function store(Request $request){
        $this->_validation($request);
        $product = Product::find($request->product_id);
        if($product->stock < $request->total_sales){
            return redirect()->back()->with('message', 'sorry the remaining stock is '.$product->stock. ', Data uncreated!');          
        }
        $product->update(['stock' => $product->stock - $request->total_sales]);
        $transaction = Transaction::create($request->all());
        return response()->json([
            'message' => 'Success, Create transaction data!',
            'data' => $transaction,
        ], Response::HTTP_CREATED);
    }

    public function show($id){
        $transaction = Transaction::find($id);
        return response()->json([
            'message' => 'Success, Show transaction data!',
            'data' => new TransactionResource($transaction)
        ], Response::HTTP_OK);
    }

    public function edit($id){
        $transaction = Transaction::find($id);
        return response()->json([
            'message' => 'Success, Get transaction data!',
            'data' => $transaction,
        ], Response::HTTP_OK);
    }

    public function update(Request $request){
        $this->_validation($request);
        $transaction = Transaction::find($request->id);
        $product = Product::find($request->product_id);
        if($request->product_id == $transaction->product_id){
            $stock = $transaction->total_sales - $request->total_sales + $product->stock;
            if($stock < 0){
                return redirect()->back()->with('message', 'sorry the remaining stock is '.$product->stock. ', Data uncreated!');
            }
        }else{
            $stock = $product->stock - $request->total_sales;
            if($stock < 0){
                return redirect()->back()->with('message', 'sorry the remaining stock is '.$product->stock. ', Data uncreated!');
            }
            $oldProduct = Product::find($transaction->product_id);
            $oldProduct->update(['stock' => $oldProduct->stock + $transaction->total_sales]);
        }
        $product->update(['stock' => $stock]);
        $transaction->update([
            'product_id' => $request->product_id,
            'total_sales' => $request->total_sales,
            'date' => $request->date,
        ]);
        return response()->json([
            'message' => 'Success, Update transaction data!',
            'data' => $transaction,
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request){
        $transaction = Transaction::find($request->id);
        $product = Product::find($transaction->product_id);
        $product->update(['stock' => $product->stock + $transaction->total_sales]);
        $transaction->delete();
        return response()->json([
            'message' => 'Success, Transaction deleted!'
        ], Response::HTTP_OK);
    }

    private function _validation(Request $request){
        $this->validate($request, [
            'product_id' => 'required|max:255',
            'total_sales' => 'required|numeric|max:255',
            'date' => 'required'
        ]);
    }
}
