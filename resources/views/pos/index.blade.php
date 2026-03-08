@extends('layouts.app')

@section('content')
<div class="row g-4">
    
    <!-- Left: Products -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <span>Available Products</span>
                <small class="text-muted">Click to Add</small>
            </div>
            <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                <div class="row g-3">
                    @foreach($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <a href="{{ route('pos.add', $product->id) }}" class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm product-card">
                                <img src="{{ $product->image ? asset('images/'.$product->image) : 'https://placehold.co/300x200' }}" 
                                     class="card-img-top" style="height: 120px; object-fit: cover;">
                                <div class="card-body p-2 text-center">
                                    <h6 class="card-title mb-1 text-truncate">{{ $product->name }}</h6>
                                    <span class="text-success fw-bold">${{ number_format($product->price, 2) }}</span>
                                    <div class="mt-1">
                                        <span class="badge bg-light text-dark">Stock: {{ $product->stock }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Cart -->
    <div class="col-lg-4">
        <div class="card shadow-sm sticky-top" style="top: 90px; z-index: 10;">
            <div class="card-header bg-success text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-shopping-cart me-2"></i>Current Cart</h6>
            </div>
            
            <div class="card-body p-0" style="max-height: 40vh; overflow-y: auto;">
                @php $total = 0; @endphp
                @if(session('cart'))
                    <ul class="list-group list-group-flush">
                    @foreach(session('cart') as $id => $details)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <strong>{{ $details['name'] }}</strong>
                                <div class="text-muted small">{{ $details['quantity'] }} x ${{ number_format($details['price'], 2) }}</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-success fw-bold me-2">${{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                <a href="{{ route('pos.remove', $id) }}" class="btn btn-sm btn-outline-danger rounded-circle">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </li>
                        @php $total += $details['price'] * $details['quantity']; @endphp
                    @endforeach
                    </ul>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-cart-plus fa-2x mb-3"></i>
                        <p class="mb-0">Cart is empty</p>
                    </div>
                @endif
            </div>

            <div class="card-footer bg-white p-3">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="text-gray-700">Total:</h5>
                    <h4 class="text-success fw-bold">${{ number_format($total, 2) }}</h4>
                </div>
                
                <form action="{{ route('pos.checkout') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="customer_name" class="form-control" placeholder="Customer Name">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100 shadow {{ $total == 0 ? 'disabled' : '' }}">
                        PROCESS PAYMENT
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
{{-- Receipt Modal --}}
@if(session('order_id'))
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Order Success</h5>
                </div>
                <div class="modal-body p-0" id="printableArea">
                    <div class="p-4 text-center" style="font-family: 'Courier New', monospace;">
                        <h3 class="text-uppercase fw-bold mb-0">FlexPOS</h3>
                        <p class="mb-1 small">Official Receipt</p>
                        <hr class="my-2">
                        
                        <div class="text-start small">
                            <p class="mb-1"><strong>Receipt #:</strong> {{ session('order_id') }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ date('Y-m-d H:i') }}</p>
                            <p class="mb-1"><strong>Cashier:</strong> {{ auth()->user()->name }}</p>
                            <hr class="my-2">
                            
                            <table width="100%" class="mb-2">
                                <thead>
                                    <tr>
                                        <td style="width:50%">Item</td>
                                        <td class="text-center">Qty</td>
                                        <td class="text-end">Price</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $order = \App\Models\Order::with('items.product')->find(session('order_id')); 
                                        $total = 0;
                                    @endphp
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @php $total += $item->price * $item->quantity; @endphp
                                    @endforeach
                                </tbody>
                            </table>

                            <hr class="my-2">
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>TOTAL</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <hr class="my-2">
                            <p class="text-center mb-0">** Thank You **</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="printReceipt()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Styles for Clean Printing --}}
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printableArea, #printableArea * {
        visibility: visible;
    }
    #printableArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    /* Hide buttons when printing */
    .modal-footer {
        display: none !important;
    }
}
</style>

{{-- ... previous code ... --}}

{{-- SCRIPTS SECTION: This makes the modal popup work --}}
@push('scripts')
<script>
    // Check if we have an order ID from the controller
    @if(session('order_id'))
        // Wait for the page to load, then show the modal
        document.addEventListener('DOMContentLoaded', function() {
            var receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
            receiptModal.show();
        });
    @endif

    function printReceipt() {
        window.print();
    }
</script>
@endpush