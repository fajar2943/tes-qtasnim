<?php

namespace App\Http\Controllers;

use App\Http\Resources\Api\V1\TransactionCollection;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function filter(Request $request){
        if($request->search != null && $request->type != null && $request->from != null && $request->until != null && $request->short != null){
            if($request->type == 'total_sales'){
                $transactions = Transaction::where('total_sales',$request->search)->whereBetween('date', [$request->from, $request->until])->orderBy('total_sales', $request->short)->get();
            }elseif($request->type == 'product_name'){
                $key = $request->search;
                $transactions = Transaction::whereBetween('date', [$request->from, $request->until])->whereHas('product', function($productQuery) use ($key){
                    $productQuery->where('name', 'LIKE', '%'.$key.'%' );
                })->orderBy('total_sales', $request->short)->get();
            }elseif($request->type == 'date'){
                $transactions = Transaction::whereBetween('date', [$request->from, $request->until])->orWhereDate('date', $request->search)->orderBy('total_sales', $request->short)->get();
            }
        }elseif($request->search != null && $request->type != null && $request->from != null && $request->until != null && $request->short == null){
            if($request->type == 'total_sales'){
                $transactions = Transaction::where('total_sales',$request->search)->whereBetween('date', [$request->from, $request->until])->get();
            }elseif($request->type == 'product_name'){
                $key = $request->search;
                $transactions = Transaction::whereBetween('date', [$request->from, $request->until])->whereHas('product', function($productQuery) use ($key){
                    $productQuery->where('name', 'LIKE', '%'.$key.'%' );
                })->get();
            }elseif($request->type == 'date'){
                $transactions = Transaction::whereBetween('date', [$request->from, $request->until])->orWhereDate('date', $request->search)->get();
            }
        }elseif($request->search != null && $request->type != null && ($request->from == null or $request->until == null) && $request->short != null){
            if($request->type == 'total_sales'){
                $transactions = Transaction::where('total_sales',$request->search)->orderBy('total_sales', $request->short)->get();
            }elseif($request->type == 'product_name'){
                $key = $request->search;
                $transactions = Transaction::whereHas('product', function($productQuery) use ($key){
                    $productQuery->where('name', 'LIKE', '%'.$key.'%' );
                })->orderBy('total_sales', $request->short)->get();
            }elseif($request->type == 'date'){
                $transactions = Transaction::whereDate('date', $request->search)->orderBy('total_sales', $request->short)->get();
            }
        }elseif(($request->search == null or $request->type == null) && $request->from != null && $request->until != null && $request->short != null){
            $transactions = Transaction::whereBetween('date', [$request->from, $request->until])->orderBy('total_sales', $request->short)->get();
        }elseif($request->search == null && $request->type == null && $request->from == null && $request->until == null && $request->short != null){
            $transactions = Transaction::orderBy('total_sales', $request->short)->get();
        }elseif($request->search == null && $request->type == null && $request->from != null && $request->until != null && $request->short == null){
            $transactions = Transaction::whereBetween('date', [$request->from, $request->until])->get();
        }elseif($request->search != null && $request->type != null && ($request->from == null or $request->until == null) && $request->short == null){
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
        }else{
            $transactions = Transaction::all();
        }
        return response()->json(new TransactionCollection($transactions));
    }

    public function index(Request $request){
        $transactions = Transaction::all();
        $products = Product::all();
        return view('transaction.index', compact('transactions','products'));
    }
    
    public function store(Request $request){
        $this->_validation($request);
        $product = Product::find($request->product_id);
        if($product->stock < $request->total_sales){
            return redirect()->back()->with('message', 'sorry the remaining stock is '.$product->stock. ', Data uncreated!');          
        }
        $product->update(['stock' => $product->stock - $request->total_sales]);
        $transaction = Transaction::create($request->all());
        return redirect()->back()->with('success', 'Data created successfully!');
    }

    public function edit($id){
        $transaction = Transaction::find($id);
        $products = Product::all();
        return view('transaction.edit',compact('transaction','products'));
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
        return redirect()->back()->with('success', 'Data updated successfully!');
    }

    public function destroy(Request $request){
        $transaction = Transaction::find($request->id);
        $product = Product::find($transaction->product_id);
        $product->update(['stock' => $product->stock + $transaction->total_sales]);
        $transaction->delete();
        return redirect()->back()->with('success', 'Transaction deleted!');
    }

    private function _validation(Request $request){
        $this->validate($request, [
            'product_id' => 'required|max:255',
            'total_sales' => 'required|numeric|max:255',
            'date' => 'required'
        ]);
    }
}
