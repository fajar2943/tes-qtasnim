<?php

namespace App\Http\Controllers;

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
            if($request->has('from') && $request->has('until') && $request->from != null && $request->until != null && $request->short != null){
                $transactions = Transaction::whereBetween('date', [$request->from, $request->until])->orderBy('total_sales', $request->short)->get();
            }elseif($request->has('from') && $request->has('until') && $request->from != null && $request->until != null && $request->short == null){
                $transactions = Transaction::whereBetween('date', [$request->from, $request->until])->get();
            }else{
                $transactions = Transaction::orderBy('total_sales', $request->short)->get();
            }
        }else{
            $transactions = Transaction::all();
        }
        $products = Product::all();
        return view('transaction.index', compact('transactions','products'));
    }
    
    public function store(Request $request){
        $this->_validation($request);
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
        $transaction->update([
            'product_id' => $request->product_id,
            'total_sales' => $request->total_sales,
            'date' => $request->date,
        ]);
        return redirect()->back()->with('success', 'Data updated successfully!');
    }

    public function destroy(Request $request){
        $transaction = Transaction::find($request->id);
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
