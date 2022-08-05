@extends('layout.master')
@push('content')
@if (Session::has('success'))
<div class="alert alert-success" role="alert">
    {{Session::get('success')}}
</div>
@endif
<div class="p-3 mb-4 bg-light rounded-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <form action="/transactions" method="get">
                    <small>Searching</small>
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Enter Key">
                        <select class="btn btn-outline-secondary" name="type" id="type">
                            <option value="">Filter by</option>
                            <option value="total_sales">Total Sales</option>
                            <option value="product_name">Product Name</option>
                            <option value="date">Date</option>
                        </select>
                        <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"
                                aria-hidden="true"></i> </button>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form action="/transactions" method="get">
                    <small>Shorting</small>
                    <div class="input-group">
                        <input type="date" name="from" class="form-control" placeholder="From Date">
                        <input type="date" name="until" class="form-control" placeholder="Until Date">
                        <select class="btn btn-outline-secondary" name="short">
                            <option value="">Short by</option>
                            <option value="desc">Highest Sales</option>
                            <option value="asc">Lowest Sales</option>
                        </select>
                        <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"
                                aria-hidden="true"></i> </button>
                    </div>
                </form>
            </div>
            <div class="col-md-2 text-end">
                <br>
                <a href="/transactions" class="btn btn-secondary">Reset Filter</a>
            </div>
        </div>
    </div>
</div>
<div class="row align-items-md-stretch">
    <div class="col-md-8">
        <div class="h-100 p-5 text-white bg-dark rounded-3">
            <h2>Transactions</h2>
            <table class="table table-sm text-light">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Total Sales</th>
                        <th>Transaction Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{$transaction->product->name}}</td>
                        <td>{{$transaction->total_sales}}</td>
                        <td>{{date('d M Y', strtotime($transaction->date))}}</td>
                        <td>
                            <a class="btn btn-edit badge rounded-pill bg-light text-dark"
                                data-id="{{$transaction->id}}">Edit</a>
                            <a class="btn btn-delete badge rounded-pill bg-danger text-light" data-id="{{$transaction->id}}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        <div class="h-100 p-5 bg-light border rounded-3">
            <h4>Add Data</h4>
            <form action="/transactions" method="post">
                @csrf
                <label for="product_id" class="form-label">Product</label>
                <select class="form-select mb-3" name="product_id" id="product_id" aria-label="Default select example">
                    @foreach ($products as $product)
                    <option value="{{$product->id}}">{{$product->name}}</option>
                    @endforeach
                </select>
                <div class="mb-3">
                    <label for="total_sales" class="form-label">Total Sales</label>
                    <input type="number" name="total_sales" class="form-control" id="total_sales"
                        placeholder="Total Sales">
                    @if ($errors->has('total_sales'))
                    <span class="help-block text-danger">{{$errors->first('total_sales')}}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date Sales</label>
                    <input type="date" name="date" class="form-control" id="date">
                    @if ($errors->has('date'))
                    <span class="help-block text-danger">{{$errors->first('date')}}</span>
                    @endif
                </div>
                <button type="submit" class="btn btn-secondary">Submit</button>
            </form>
        </div>
    </div>
</div>

@endpush

@push('js')
<script>
    $('#type').on('change', function () {
        let type = $(this).val();
        if (type == 'total_sales') {
            $('#search').attr('type', 'number')
        } else if (type == 'date') {
            $('#search').attr('type', 'date')
        } else {
            $('#search').attr('type', 'text')
        }
    })

    $('.btn-edit').on('click', function () {
        let id = $(this).data('id');
        $('#form-edit').attr('action', '/transactions/update');
        $.ajax({
            url: `/transactions/${id}/edit`,
            method: "GET",
            success: function (data) {
                $('#modal-edit').find('.modal-body').html(data);
                $('#modal-edit').modal('show');
            },
            error: function (error) {
                alert('Something wrong!')
            }
        })
    })

    $('.btn-delete').on('click', function(){
        let id = $(this).data('id');
        $('#form-delete').attr('action', `/transactions/destroy`);
        $('#id_delete').val(id);
        $('#modal-delete').modal('show');
    })

</script>
@endpush
