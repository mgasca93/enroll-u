@extends('layouts.dashboard-layout')
@section('title','Customers')
@section('content')
    <div class="pagetitle">
        <h1>Customers</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">View Customers</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="row">
        <!-- Create new customer -->
        <div class="col-12 mb-3">
            <button class="btn btn-dark rounded-pill {{ !isSuperAdmin() ? 'visually-hidden' : ''}}" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                <i class="bi bi-person-add"></i>
                Create Customer
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
                <h5 class="card-title">Customers<span> | Registered</span></h5>
                <table class="table table-borderless datatable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Last name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                        <tr>
                            <th scope="row">{{ $customer->id }}</th>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->paternal_surname }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->created_at }}</td>
                            <td>
                                <form class="d-inline-flex formCustomer"  method="POST" action="{{ route('customers.get', $customer->id) }}">
                                    @csrf
                                    <button class="btn btn-outline-dark btn-sm rounded-pill {{ !isSuperAdmin() ? 'visually-hidden' : ''}}" 
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-custom-class="custom-tooltip"
                                    data-bs-title="Edit Customer." type="sumit">
                                    <i class="bi bi-pen-fill"></i> Edit
                                    </button>
                                </form>
                                <form class="d-inline-flex" method="POST" action="{{ route('customers.delete') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" value="{{ $customer->id }}" name="customerID">
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
                <form class="row g-3" id="formaAddCustomer" method="POST" action="{{ route('customers.add') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create new customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <label for="inputName" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="inputName" name="name">
                        </div>
                        <div class="col-12">
                            <label for="inputLastname" class="form-label">Customer Lastname</label>
                            <input type="text" class="form-control" id="inputLastname" name="lastname">
                        </div>
                        <div class="col-12">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="inputEmail" name="email">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark rounded-pill">Save changes</button>
                    </div>
                </form><!-- Vertical Form -->
            </div>
        </div>
    </div><!-- End Vertically centered Modal-->
    <div class="modal fade" id="modalUpdateCustomer" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="row g-3" method="POST" action="{{ route('customers.update') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <label for="inputName" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="inputNameUpdate" name="nameUpdate">
                        </div>
                        <div class="col-12">
                            <label for="inputLastname" class="form-label">Customer Lastname</label>
                            <input type="text" class="form-control" id="inputLastnameUpdate" name="lastnameUpdate">
                        </div>
                        <div class="col-12">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="inputEmailUpdate" name="emailUpdate" disabled>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="customerId" name="customerId" value="">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark rounded-pill">Save changes</button>
                    </div>
                </form><!-- Vertical Form -->
            </div>
        </div>
    </div><!-- End Vertically centered Modal-->
    <script src="{{ asset('assets/js/customers.js') }}"></script>
@endsection