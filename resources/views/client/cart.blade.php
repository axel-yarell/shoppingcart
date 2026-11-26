@extends('client_layout.master')

@section('title')
    
    Cart

@endsection

@section('content')

@php
    $topcart = Session::get('topcart', []);
    $subTotal = 0;
    foreach ($topcart as $product) {
        $subTotal += $product['product_price'] * $product['qty'];
    }

    $discount = $subTotal * 0.1;
    $taxRate = 0.05;
    $tax = $subTotal * $taxRate;
    $couponDiscount = 0;
    $grandTotal = ($subTotal - $discount) + $tax - $couponDiscount;
@endphp

 <!-- Start All Title Box -->
    <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Cart</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/shop')}}">Shop</a></li>
                        <li class="breadcrumb-item active">Cart</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->

    <!-- Start Cart  -->
    <div class="cart-box-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-main table-responsive">
                        @if (Session::has('status'))
                            <div class="alert alert-success">
                                {{ Session::get('status') }}
                            </div>
                            
                        @endif
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>frontend/images</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (Session::has('topcart'))
                                    @foreach (Session::get('topcart') as $key => $product)
                                        <tr>
                                            <td class="thumbnail-img">
                                                <a href="#">
                                                    <img class="img-fluid" src="{{ asset('product_images/' . $product['product_image']) }}" alt="" />
                                                </a>
                                            </td>
                                            <td class="name-pr">
                                                <a href="#">
                                                    {{ $product['product_name'] }}
                                                </a>
                                            </td>
                                            <td class="price-pr">
                                                <p>${{ number_format($product['product_price'], 2) }}</p>
                                            </td>
                                            <td class="quantity-box">
                                                <form action="{{ url('/cart/updateqty/' . $key) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="qty" size="4" value="{{ $product['qty'] }}" min="0" step="1" class="c-input-text qty text">
                                                    <br>
                                                    <input type="submit" value="Update" class="btn btn-sm btn-dark mt-2">
                                                </form>
                                            </td>
                                            <td class="total-pr">
                                                <p>${{ number_format($product['product_price'] * $product['qty'], 2) }}</p>
                                            </td>
                                            <td class="remove-pr">
                                                <form action="{{ url('/cart/removeitem/' . $key) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-dark btn-sm" onclick="return confirm('Are you sure you want to remove this item?');">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row my-5">
                <div class="col-lg-6 col-sm-6">
                    <div class="coupon-box">
                        <div class="input-group input-group-sm">
                            <input class="form-control" placeholder="Enter your coupon code" aria-label="Coupon code" type="text">
                            <div class="input-group-append">
                                <button class="btn btn-theme" type="button">Apply Coupon</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="update-box">
                        <input value="Update Cart" type="submit">
                    </div>
                </div>
            </div>

            <div class="row my-5">
                <div class="col-lg-8 col-sm-12"></div>
                <div class="col-lg-4 col-sm-12">
                    <div class="order-box">
                        <h3>Order summary</h3>
                        <div class="d-flex">
                            <h4>Sub Total</h4>
                            <div class="ml-auto font-weight-bold"> $ {{ number_format($subTotal, 2) }} </div>
                        </div>
                        <div class="d-flex">
                            <h4>Discount</h4>
                            <div class="ml-auto font-weight-bold"> $ {{ number_format($discount, 2) }} </div>
                        </div>
                        <hr class="my-1">
                        <div class="d-flex">
                            <h4>Coupon Discount</h4>
                            <div class="ml-auto font-weight-bold"> $ {{ number_format($couponDiscount, 2) }} </div>
                        </div>
                        <div class="d-flex">
                            <h4>Tax</h4>
                            <div class="ml-auto font-weight-bold"> $ {{ number_format($tax, 2) }} </div>
                        </div>
                        <div class="d-flex">
                            <h4>Shipping Cost</h4>
                            <div class="ml-auto font-weight-bold"> Free </div>
                        </div>
                        <hr>
                        <div class="d-flex gr-total">
                            <h5>Grand Total</h5>
                            <div class="ml-auto h5"> $ {{ number_format($grandTotal, 2) }} </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="col-12 d-flex shopping-box"><a href="{{url('/checkout')}}" class="ml-auto btn hvr-hover">Checkout</a> </div>
            </div>

        </div>
    </div>
    <!-- End Cart -->
@endsection