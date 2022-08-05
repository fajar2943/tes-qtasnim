<input type="hidden" name="id" value="{{$transaction->id}}">
<label for="product_id" class="form-label">Product</label>
<select class="form-select mb-3" name="product_id" id="product_id" aria-label="Default select example">
    @foreach ($products as $product)
    <option value="{{$product->id}}" @if($product->id == $transaction->product_id)selected @endif>{{$product->name}}</option>
    @endforeach
</select>
<div class="mb-3">
    <label for="total_sales" class="form-label">Total Sales</label>
    <input type="number" name="total_sales" class="form-control" id="total_sales" value="{{$transaction->total_sales}}" placeholder="Total Sales">
</div>
<div class="mb-3">
    <label for="date" class="form-label">Date Sales</label>
    <input type="date" name="date" class="form-control" id="date" value="{{date('Y-m-d', strtotime($transaction->date))}}">
</div>
