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
<div class="row align-items-md-stretch">
    <div class="col-md-8">
        <div class="h-100 p-5 text-white bg-dark rounded-3">
            <h2>Products</h2>
            <table class="table table-sm text-light">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Category Name</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{$product->name}}</td>
                        <td>{{$product->category->name}}</td>
                        <td>{{$product->stock}}</td>
                        <td>
                            <a class="btn btn-edit badge rounded-pill bg-light text-dark"
                                data-id="{{$product->id}}">Edit</a>
                            <a class="btn btn-delete badge rounded-pill bg-danger text-light" data-id="{{$product->id}}">Delete</a>
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
            <form action="/products" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" id="name"
                        placeholder="Enter product name">
                    @if ($errors->has('name'))
                    <span class="help-block text-danger">{{$errors->first('name')}}</span>
                    @endif
                </div>
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select mb-3" name="category_id" id="category_id" aria-label="Default select example">
                    @foreach ($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" id="stock"
                        placeholder="Enter Stock">
                    @if ($errors->has('stock'))
                    <span class="help-block text-danger">{{$errors->first('stock')}}</span>
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
    $('.btn-edit').on('click', function () {
        let id = $(this).data('id');
        $('#form-edit').attr('action', '/products/update');
        $.ajax({
            url: `/products/${id}/edit`,
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
        $('#form-delete').attr('action', `/products/destroy`);
        $('#id_delete').val(id);
        $('#modal-delete').modal('show');
    })

</script>
@endpush
