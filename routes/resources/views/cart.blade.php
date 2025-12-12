@extends('layouts.app')
@section('content')
<h2 class="text-center text-secondary mb-4">Your Cart</h2>
@if(empty($cart))
<p class="text-center">Your cart is empty.</p>
@else
<form action="/cart/update" method="POST">@csrf
<div class="table-responsive">
<table class="table table-bordered align-middle text-center">
<tr class="table-light">
<th>Product</th><th>Qty</th><th>Price</th><th>Total</th>
</tr>
@php $sum=0; @endphp
@foreach($cart as $item)
@php $total=$item['price']*$item['qty']; $sum+=$total; @endphp
<tr>
<td>{{ $item['name'] }}</td>
<td style="width:100px;">
    <input type="number" name="qty[{{ $item['id'] }}]" value="{{ $item['qty'] }}" class="form-control text-center">
</td>
<td>${{ $item['price'] }}</td>
<td>${{ $total }}</td>
</tr>
@endforeach
<tr class="table-secondary">
<td colspan="3" class="text-end fw-bold">Grand Total</td>
<td class="fw-bold">${{ $sum }}</td>
</tr>
</table>
</div>
<div class="d-flex justify-content-center gap-3 flex-wrap">
    <button class="btn btn-primary">Update Cart</button>
    <a href="/checkout" class="btn btn-success">Checkout</a>
    <a href="/cart/clear" class="btn btn-danger">Clear Cart</a>
</div>
</form>
@endif
@endsection
