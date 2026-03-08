@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header"><h4>Edit Product</h4></div>
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Important: Tells Laravel this is an UPDATE -->

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>
                
                <div class="mb-3">
                    <label>Category</label>
                    <select name="category_id" class="form-control">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Price ($)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Stock</label>
                        <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>SKU</label>
                        <input type="text" name="sku" class="form-control" value="{{ $product->sku }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Current Image</label><br>
                    @if($product->image)
                        <img src="{{ asset('images/'.$product->image) }}" width="100" class="mb-2">
                    @else
                        <span class="text-muted">No image</span><br>
                    @endif
                    <label>Change Image (Optional)</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection