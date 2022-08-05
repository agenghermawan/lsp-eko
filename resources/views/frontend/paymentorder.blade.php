@extends('frontend.include.app')

@section('title')
    Store Cart Page
@endsection

@section('content')
    <div class="page-content page-cart">
        <section class="store-breadcrumbs" data-aos="fade-down" data-aos-delay="100">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Cart
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <section class="store-cart">
            <div class="container">
                <div class="row" data-aos="fade-up" data-aos-delay="100">
                    <div class="col-12 table-responsive">
                        <table class="table table-borderless table-cart" aria-describedby="Cart">
                            <thead>
                                <tr>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name &amp; Seller</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalPrice = 0 @endphp
                                @foreach ($carts as $cart)
                                    <tr>
                                        <td style="width: 20%;">
                                            @if ($cart->product->galleries)
                                                <img src="{{ Storage::url($cart->product->galleries->first()->Photos) }}"
                                                    alt="" class="cart-image" />
                                            @endif
                                        </td>
                                        <td style="width: 35%;">
                                            <div class="product-title">{{ $cart->product->ProductName }}</div>
                                        </td>
                                        <td style="width: 35%;">
                                            <div class="product-title">{{ number_format($cart->product->Price) }}</div>
                                            <div class="product-subtitle"></div>
                                        </td>
                                        <td style="width: 35%;">
                                            <div class="product-title">{{ $cart->Quantity }}</div>
                                            <div class="product-subtitle"></div>
                                        </td>
                                    </tr>

                                    @php $totalPrice += $cart->product->Price @endphp
                                    @php $allprice = $totalPrice * $cart -> Quantity @endphp
                                @endforeach
                                @php
                                    $priceWithOngkir = $allprice + $priceOngkir;
                                @endphp
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" data-aos="fade-up" data-aos-delay="150">
                    <div class="col-12">
                        <hr />
                    </div>
                    <div class="col-12">
                        <h3 class="mb-4">Details Checkout</h3>
                    </div>
                </div>
                <form action="{{ route('checkout') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="form-control" name="name" value="{{ $name }}" />
                    <input type="hidden" class="form-control" name="email" value="{{ $email }} " />
                    <input type="hidden" class="form-control" name="notes" value="{{ $notes }} " />
                    <input type="hidden" class="form-control" name="no_pesanan" value="{{ $no_pesanan }} " />
                    <input type="hidden" class="form-control" name="total_price" value="{{ $allprice }} " />
                    <div class="row mb-2" data-aos="fade-up" data-aos-delay="200" id="locations">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_one">Name </label>
                                <h5> {{ $name }}
                                </h5>
                            </div>
                        </div>
                        <div class=" col-md-6">
                            <div class="form-group">
                                <label for="address_one">Mail </label>
                                <h5> {{ $email }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_number">No Pesanan</label>
                                <h5> {{ $no_pesanan }}
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row" data-aos="fade-up" data-aos-delay="150">
                        <div class="col-12">
                            <hr />
                            <h3> Notes </h3>
                            <h5> {{ $notes }}</h5>
                        </div>
                        <div class="col-12">
                            <hr>
                            <h3 class="">Payment Informations</h3>
                        </div>
                    </div>
                    <div class="row" data-aos="fade-up" data-aos-delay="200">
                        <div class="col-4 col-md-2">
                            <div class="product-title"> <img src="{{ asset('frontend/images/ic_bank.png') }}"
                                    alt="">
                            </div>
                            <div class="product-subtitle">Transfer Bank , Gopay, dan manual</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="product-title text-success">Rp.
                                {{ number_format($totalPrice * $cart->Quantity) }}</div>
                            <div class="product-subtitle"><strong>Total </strong></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <button type="button" class="btn btn-success mt-4 px-4 btn-block" data-toggle="modal"
                                data-target=".bd-example-modal-lg"> Pembayaran Disini</button>
                            <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Pembayaran</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container">
                                                <div class="row justify-content-between">
                                                    <p class="font-weight-bold"> Name </p>
                                                    <p> {{ $name }}</p>
                                                </div>
                                                <div class="row justify-content-between">
                                                    <p class="font-weight-bold"> Email </p>
                                                    <p> {{ $email }}</p>
                                                </div>
                                                <div class="row justify-content-between">
                                                    <p class="font-weight-bold"> No Pesanan </p>
                                                    <p> {{ $no_pesanan }}</p>
                                                </div>
                                                <div class="row justify-content-between">
                                                    <p class="font-weight-bold"> Total Price </p>
                                                    <p> {{ number_format($allprice) }}</p>
                                                </div>
                                                <hr>
                                                <h3> Transfer pembayaran </h3>
                                                <img src="{{ asset('frontend/images/ic-bca.png') }}" width="100px"
                                                    height="75px" style="objec-fit:cover" alt="">
                                                <h3> 776261232 </h3>
                                                <p style="color: #979797"> A/N Vinnotinto Rizky Anugrah S </p>
                                                <p class="alert text-danger"> Tambahkan Bukti
                                                    Pembayaran dibawah sini </p>
                                                <div class="upload mt-4 text-center">
                                                    <label htmlFor="avatar">
                                                        <svg width="120" height="120" viewBox="0 0 120 120"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <circle cx="60" cy="60" r="60"
                                                                fill="#E7EAF5" />
                                                            <path fillRule="evenodd" clipRule="evenodd"
                                                                d="M60 52.5C57.0641 52.5 54.6239 54.2954 53.7502 56.711C53.559 57.2397 53.0883 57.617 52.5307 57.6886C49.915 58.0244 48 60.135 48 62.5714C48 65.2253 50.2807 67.5 53.25 67.5C54.0784 67.5 54.75 68.1716 54.75 69C54.75 69.8284 54.0784 70.5 53.25 70.5C48.7635 70.5 45 67.0184 45 62.5714C45 58.7996 47.7137 55.7171 51.2715 54.8729C52.7994 51.6764 56.1564 49.5 60 49.5C64.7615 49.5 68.8073 52.8602 69.4965 57.3516C72.5948 57.9685 75 60.5965 75 63.8571C75 67.594 71.841 70.5 68.1 70.5C67.2716 70.5 66.6 69.8284 66.6 69C66.6 68.1716 67.2716 67.5 68.1 67.5C70.3237 67.5 72 65.8009 72 63.8571C72 61.9134 70.3237 60.2143 68.1 60.2143C67.2716 60.2143 66.6 59.5427 66.6 58.7143C66.6 55.3504 63.7149 52.5 60 52.5Z"
                                                                fill="#0C145A" />
                                                            <path fillRule="evenodd" clipRule="evenodd"
                                                                d="M60 70.5C59.1716 70.5 58.5 69.8284 58.5 69V60C58.5 59.1716 59.1716 58.5 60 58.5C60.8284 58.5 61.5 59.1716 61.5 60V69C61.5 69.8284 60.8284 70.5 60 70.5Z"
                                                                fill="#0C145A" />
                                                            <path fillRule="evenodd" clipRule="evenodd"
                                                                d="M55.9393 64.0607C55.3536 63.4749 55.3536 62.5251 55.9393 61.9393L58.9393 58.9393C59.5251 58.3536 60.4749 58.3536 61.0607 58.9393C61.6464 59.5251 61.6464 60.4749 61.0607 61.0607L58.0607 64.0607C57.4749 64.6464 56.5251 64.6464 55.9393 64.0607Z"
                                                                fill="#0C145A" />
                                                            <path fillRule="evenodd" clipRule="evenodd"
                                                                d="M58.9393 58.9393C59.5251 58.3536 60.4749 58.3536 61.0607 58.9393L64.0607 61.9393C64.6464 62.5251 64.6464 63.4749 64.0607 64.0607C63.4749 64.6464 62.5251 64.6464 61.9393 64.0607L58.9393 61.0607C58.3536 60.4749 58.3536 59.5251 58.9393 58.9393Z"
                                                                fill="#0C145A" />
                                                        </svg>
                                                        <input id="buktiPembayaran" type="file" name="evidence"
                                                            accept="image/png, image/jpeg"  />
                                                        @error('buktiPembayaran')
                                                            <div class="text text-danger">
                                                                {{ $message }}</div>
                                                        @enderror
                                                        <img src="" id="perview" alt=""
                                                            class="img-fluid shadow mb-3"
                                                            style="border-radius: 10px 20px;">
                                                    </label>
                                                </div>
                                                <button type="submit" value="NonTunai" name="payment"
                                                    style="border-radius: 10px"
                                                    class="btn btn-success mt-4 px-4 btn-block">
                                                    Upload Bukti Pembayaran Jika Non Tunai
                                                </button>
                                                <button type="submit" value="Tunai" name="payment" class="btn btn-success mt-4 px-4 btn-block text-white"> Bayar Tunai Disini </button>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <a href="{{ route('cart.index') }}" class="btn btn-success mt-4 px-4 btn-block text-white">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('addon-script')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    $('#perview').attr('src', e.target.result);
                    $('#perview').attr('width', '100%');
                    $('#perview').attr('height', '100%');
                }

                reader.readAsDataURL(input.files[0])
            }
        }

        $('#buktiPembayaran').change(function() {
            readURL(this)
        })
    </script>
@endpush
