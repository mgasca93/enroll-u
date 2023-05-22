@extends('layouts.dashboard-layout')
@section('title','Customers')
@section('content')
    <div class="pagetitle">
        <h1>Payments</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">View Payments</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="row">
        <!-- Create new customer -->
        <div class="col-12 mb-3">
            <button class="btn btn-dark rounded-pill {{ !isSuperAdmin() ? 'visually-hidden' : ''}}" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                <i class="bi bi-person-add"></i>
                Create Payment
            </button>
        </div>
        @if( $errors->any() )
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <i class="bi bi-exclamation-octagon me-1"></i>
                    {{ $error }}!<br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif
        <!-- Recent Sales -->
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

              <div class="card-body">
                <h5 class="card-title">Payments By<span> | Customers</span></h5>
                <table class="table table-borderless datatable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total Cost</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                        <tr>
                            <th scope="row">{{ $payment->id }}</th>
                            <td>{{ $payment->product_name }}</td>
                            <td>${{ number_format(( $payment->amount / $payment->quantity ), 2) }}</td>
                            <td>{{ $payment->quantity }}</td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->name }} {{ $payment->paternal_surname}}</td>
                            <td>
                                <form class="d-inline-flex formPayment"  method="POST" action="{{ route('payments.get', $payment->id) }}">
                                    @csrf
                                    <button class="btn btn-outline-dark btn-sm rounded-pill {{ !isSuperAdmin() ? 'visually-hidden' : ''}}" 
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-custom-class="custom-tooltip"
                                    data-bs-title="Edit Customer." type="sumit">
                                    <i class="bi bi-pen-fill"></i> Edit
                                    </button>
                                </form>
                                <form class="d-inline-flex" method="POST" action="{{ route('payments.delete') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" value="{{ $payment->id }}" name="paymentID">
                                    <button class="btn btn-outline-danger btn-sm rounded-pill {{ !isSuperAdmin() ? 'visually-hidden' : ''}}" 
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="custom-tooltip"
                                        data-bs-title="Delete Customer."
                                        type="submit">
                                        <i class="bi bi-trash-fill"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Don´t have customers registered.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

              </div>

            </div>
        </div><!-- End Recent Sales -->
    </div>
    <div class="modal fade" id="verticalycentered" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="row g-3" id="formaAddCustomer" method="POST" action="{{ route('payments.add') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create new payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <label for="selectProduct" class="form-label">Customer</label>
                            <select name="customer_id" id="selectProduct" class="form-control">
                                <option value="" selected>Select Customer</option> 
                                @forelse ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->paternal_surname }}</option>
                                @empty 
                                @endforelse
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="inputProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="inputProductName" name="productName">
                        </div>
                        <div class="col-12">
                            <label for="inputUnitPrice" class="form-label">Unit Price</label>
                            <input type="text" class="form-control" id="inputUnitPrice" name="unitPrice">
                        </div>
                        <div class="col-12">
                            <label for="inputQuantity" class="form-label">Quantity</label>
                            <input type="text" class="form-control" id="inputQuantity" name="quantity">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark rounded-pill">Create Payment</button>
                    </div>
                </form><!-- Vertical Form -->
            </div>
        </div>
    </div><!-- End Vertically centered Modal-->

    <div class="modal fade" id="modalpaymentUpdate" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="row g-3" id="formaAddCustomer" method="POST" action="{{ route('payments.update') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create new payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <label for="selectProduct" class="form-label">Customer</label>
                            <select name="customer_id" id="selectProductUpdate" class="form-control">
                                @forelse ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->paternal_surname }}</option>
                                @empty 
                                @endforelse
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="inputProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="inputProductNameUpdate" name="productName">
                        </div>
                        <div class="col-12">
                            <label for="inputUnitPrice" class="form-label">Unit Price</label>
                            <input type="text" class="form-control" id="inputUnitPriceUpdate" name="unitPrice">
                        </div>
                        <div class="col-12">
                            <label for="inputQuantity" class="form-label">Quantity</label>
                            <input type="text" class="form-control" id="inputQuantityUpdate" name="quantity">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="paymentID" id="paymentID" value="">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark rounded-pill">Update Payment</button>
                    </div>
                </form><!-- Vertical Form -->
            </div>
        </div>
    </div><!-- End Vertically centered Modal-->
    <script src="{{ asset('assets/js/payments.js') }}"></script>
@endsection