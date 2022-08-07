@extends('layout.master')
@push('content')
@if (Session::has('success'))
<div class="alert alert-success" role="alert">
    {{Session::get('success')}}
</div>
@endif
@if (Session::has('message'))
    <div class="alert alert-warning" role="alert">
        {{Session::get('message')}}
    </div>
@endif
<div class="p-3 mb-4 bg-light rounded-3">
    <div class="container-fluid">
        <form action="" id="form-filter">
            <div class="row">
                <div class="col-md-4">
                    <small>Searching</small>
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Enter Key">
                        <select class="btn btn-outline-secondary" name="type" id="type">
                            <option value="">Filter by</option>
                            <option value="total_sales">Total Sales</option>
                            <option value="product_name">Product Name</option>
                            <option value="date">Date</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <small>Shorting From Date - Until Date</small>
                    <div class="input-group">
                        <input type="date" name="from" id="from" class="form-control" placeholder="From Date">
                        <input type="date" name="until" id="until" class="form-control" placeholder="Until Date">
                        <select class="btn btn-outline-secondary" name="short" id="short">
                            <option value="">Short by</option>
                            <option value="desc">Highest Sales</option>
                            <option value="asc">Lowest Sales</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <br>
                    <a href="/transactions" class="btn btn-secondary">Reset Filter</a>
                </div>
            </div>
        </form>
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
                        <th>Category</th>
                        <th>Total Sales</th>
                        <th>Transaction Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{$transaction->product->name}}</td>
                        <td>{{$transaction->product->category->name}}</td>
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
                    <option value="{{$product->id}}">{{$product->name}} ({{$product->category->name}})</option>
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
        editTransaction(id)
    })

    $('.btn-delete').on('click', function(){
        let id = $(this).data('id');
        deleteTransaction(id)
    })

    function editTransaction(id){
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
    }
    function deleteTransaction(id){
        $('#form-delete').attr('action', `/transactions/destroy`);
        $('#id_delete').val(id);
        $('#modal-delete').modal('show');
    }

    $('#search').keyup(function(){
        filter()
    })
    $('#search').on('change',function(){
        filter()
    })
    $('#type').on('change',function(){
        filter()
    })
    $('#from').on('change',function(){
        filter()
    })
    $('#until').on('change',function(){
        filter()
    })
    $('#short').on('change',function(){
        filter()
    })

    function filter(){
        let search = ($('#search').val()) ?  $('#search').val() : '';
        let type = ($('#type').val()) ?  $('#type').val() : '';
        let from = ($('#from').val()) ?  $('#from').val() : '';
        let until = ($('#until').val()) ?  $('#until').val() : '';
        let short = ($('#short').val()) ?  $('#short').val() : '';

        if((search != '' && type != '') || (from != '' && until != '') || short != ''){
            if(from > until && from != '' && until != ''){
                alert('from date must be earlier than until date')
            }
            $.ajax({
                url: `/transactions/filters?search=${search}&type=${type}&from=${from}&until=${until}&short=${short}`,
                method: "GET",
                success: function (data) {
                    $('#tbody').empty();
                    $.each(data, function (key, value) {
                        $('#tbody').append(`
                        <tr>
                            <td>${key += 1}</td>
                            <td>${value.product_name}</td>
                            <td>${value.category_name}</td>
                            <td>${value.total_sales}</td>
                            <td>${value.date_convert}</td>
                            <td>
                                <a class="btn btn-edit badge rounded-pill bg-light text-dark" onclick="editTransaction(${value.id})"
                                    data-id="${value.id}">Edit</a>
                                <a class="btn btn-delete badge rounded-pill bg-danger text-light" onclick="deleteTransaction(${value.id})" data-id="${value.id}">Delete</a>
                            </td>
                        </tr>
                        `);
                    });
                },
                error: function (error) {
                    alert('Something wrong!')
                }
            })
        }
    }

</script>
@endpush
