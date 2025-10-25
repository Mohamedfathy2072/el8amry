@extends('layouts.app')

@section('title', 'Financing Request #' . $financingRequest->id)

@section('content')
    <div class="pagetitle">
        <h1>Financing Request #{{ $financingRequest->id }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.financing-requests.index') }}">Financing Requests</a></li>
                <li class="breadcrumb-item active">Request #{{ $financingRequest->id }}</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Request Details</h5>
                        <a href="{{ route('admin.financing-requests.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Applicant Information</h5>
                                <p><strong>Full Name:</strong> {{ $financingRequest->full_name }}</p>
                                <p><strong>Email:</strong> {{ $financingRequest->email }}</p>
                                <p><strong>Phone:</strong> {{ $financingRequest->phone }}</p>
                            </div>

                            <div class="col-md-6">
                                <h5>Financial Information</h5>
                                <p><strong>Preferred Down Payment:</strong> {{ number_format($financingRequest->perfered_down_payment, 2) }}</p>
                                <p><strong>Net Income:</strong> {{ number_format($financingRequest->net_income, 2) }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Car Information</h5>
                                @if($financingRequest->car)
                                    <p><strong>Brand:</strong> {{ $financingRequest->car->brand->name ?? 'N/A' }}</p>
                                    <p><strong>Model:</strong> {{ $financingRequest->car->carModel->name ?? 'N/A' }}</p>
                                    <p><strong>Year:</strong> {{ $financingRequest->car->model_year ?? 'N/A' }}</p>
                                    <p><strong>Price:</strong> {{ number_format($financingRequest->car->price, 2) }}</p>
                                    <p><strong>Location:</strong> {{ $financingRequest->car->location ?? 'N/A' }}</p>
                                    <a href="{{ route('admin.car.show', $financingRequest->car->id) }}"
                                       class="btn btn-primary mt-2">
                                        <i class="bi bi-eye"></i> View Car Details
                                    </a>
                                @else
                                    <p class="text-muted">No car linked to this request.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
