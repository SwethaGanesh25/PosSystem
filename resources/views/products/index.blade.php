@extends('layouts.app')

@section('content')
<div class="card shadow">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-primary">Product Inventory</h6>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add Product
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td><img src="{{ $product->image ? asset('images/'.$product->image) : 'https://placehold.co/50x50' }}" class="rounded" width="50"></td>
                        <td class="fw-bold">{{ $product->name }}</td>
                        <td><span class="badge bg-info">{{ $product->category->name ?? 'N/A' }}</span></td>
                        <td>
                            @if($product->stock < 5)
                                <span class="badge bg-danger">{{ $product->stock }}</span>
                            @else
                                {{ $product->stock }}
                            @endif
                        </td>
                        <td class="text-success">${{ number_format($product->price, 2) }}</td>
                        <td class="text-center">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info text-white me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Delete this item?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection