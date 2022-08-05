<input type="hidden" name="id" value="{{$product->id}}">
<div class="mb-3">
    <label for="name" class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control" id="name" value="{{$product->name}}" placeholder="Enter product name">
</div>
<label for="category_id" class="form-label">category</label>
<select class="form-select mb-3" name="category_id" id="category_id" aria-label="Default select example">
    @foreach ($categories as $category)
    <option value="{{$category->id}}" @if($category->id == $product->category_id)selected @endif>{{$category->name}}</option>
    @endforeach
</select>
<div class="mb-3">
    <label for="stock" class="form-label">Stock</label>
    <input type="number" name="stock" class="form-control" id="stock" value="{{$product->stock}}" placeholder="Enter stock">
</div>
