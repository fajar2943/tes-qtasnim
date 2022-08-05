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
            <h2>Categories</h2>
            <table class="table table-sm text-light">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{$category->name}}</td>
                        <td>
                            <a class="btn btn-edit badge rounded-pill bg-light text-dark"
                                data-id="{{$category->id}}">Edit</a>
                            <a class="btn btn-delete badge rounded-pill bg-danger text-light" data-id="{{$category->id}}">Delete</a>
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
            <form action="/categories" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" id="name"
                        placeholder="Enter category name">
                    @if ($errors->has('name'))
                    <span class="help-block text-danger">{{$errors->first('name')}}</span>
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
        $('#form-edit').attr('action', '/categories/update');
        $.ajax({
            url: `/categories/${id}/edit`,
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
        $('#form-delete').attr('action', `/categories/destroy`);
        $('#id_delete').val(id);
        $('#modal-delete').modal('show');
    })

</script>
@endpush
