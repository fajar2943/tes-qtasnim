<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $transactions = Transaction::all();
        $categories = Category::all();
        $products = Product::all();
        return view('home', compact('transactions', 'categories', 'products'));
    }
}
