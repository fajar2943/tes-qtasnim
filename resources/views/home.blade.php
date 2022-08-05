@extends('layout.master')
@push('content')

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid">
        <h1 class="display-5 fw-bold mb-4">Transactions</h1>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Product Id</th>
                    <th>Total Sales</th>
                    <th>Date</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{$transaction->id}}</td>
                        <td>{{$transaction->product_id}}</td>
                        <td>{{$transaction->total_sales}}</td>
                        <td>{{$transaction->date}}</td>
                        <td>{{$transaction->created_at}}</td>
                        <td>{{$transaction->updated_at}}</td>
                    </tr>                    
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row align-items-md-stretch">
    <div class="col-md-5">
        <div class="h-100 p-5 text-white bg-dark rounded-3">
            <h2>Categories</h2>
            <table class="table table-sm text-light">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{$category->id}}</td>
                            <td>{{$category->name}}</td>
                            <td>{{$category->created_at}}</td>
                            <td>{{$category->updated_at}}</td>
                        </tr>                        
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-7">
        <div class="h-100 p-5 bg-light border rounded-3">
            <h2>Products</h2>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Category Id</th>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{$product->id}}</td>
                            <td>{{$product->category_id}}</td>
                            <td>{{$product->name}}</td>
                            <td>{{$product->stock}}</td>
                            <td>{{$product->created_at}}</td>
                            <td>{{$product->updated_at}}</td>
                        </tr>                        
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endpush
