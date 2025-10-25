@extends('layouts.app')

@section('title', 'Edit Car')

@section('content')
    <div class="pagetitle">
        <h1>Welcome to admin dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Edit Car</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Car</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>There were some problems with your input:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <strong>There were some problems try again later or call support:</strong>
                            <ul class="mb-0 mt-2">
                                <li>{{ session('error') }}</li>
                            </ul>
                        </div>
                    @endif

                    <form class="row g-3" action="{{ route('admin.car.update', $car['id']) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <div class="accordion" id="accordionExample">

                            <!-- Part 1 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Part 1
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row g-3">

                                            <!-- Brand -->
                                            <div class="col-md-4">
                                                <label class="form-label">Brand</label>
                                                <select class="form-select" id="inputBrand" name="brand">
                                                    <option value="">Choose...</option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand['id'] }}"
                                                            {{ old('brand', data_get($car, 'brand.id')) == $brand['id'] ? 'selected' : '' }}>
                                                            {{ $brand['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Model -->
                                            <div class="col-md-4">
                                                <label class="form-label">Model</label>
                                                <select class="form-select" id="inputModel" name="model">
                                                    <option value="">Choose...</option>
                                                    @foreach ($carModels as $model)
                                                        <option value="{{ $model['id'] }}"
                                                            {{ old('model', data_get($car, 'model.id')) == $model['id'] ? 'selected' : '' }}>
                                                            {{ $model['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Model Year -->
                                            <div class="col-md-4">
                                                <label class="form-label">Model Year</label>
                                                <input type="number" class="form-control" name="model_year"
                                                       value="{{ old('model_year', data_get($car, 'model_year', '')) }}"
                                                       placeholder="year">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="inputName" class="form-label">Car Name</label>
                                                <input type="text"
                                                       class="form-control"
                                                       id="inputName"
                                                       name="name"
                                                       placeholder="Enter car name"
                                                       value="{{ old('name', data_get($car, 'name', '')) }}">
                                                @error('name')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>


                                        </div>

                                        <div class="row g-3 mt-2">

                                            <!-- License Expiry Date -->
                                            <div class="col-md-4">
                                                <label class="form-label">License Expiry Date</label>
                                                <input type="date" class="form-control" name="license_expire_date"
                                                       value="{{ old('license_valid_until', data_get($car, 'license_valid_until', '')) }}">
                                            </div>

                                            <!-- Body Style -->
                                            <div class="col-md-4">
                                                <label class="form-label">Body Style</label>
                                                <select class="form-select" name="body_style">
                                                    <option value="">Choose...</option>
                                                    @foreach ($bodyStyles as $item)
                                                        <option value="{{ $item['id'] }}"
                                                            {{ data_get($car, 'specifications.body_style.id') == $item['id'] ? 'selected' : '' }}>
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Type -->
                                            <div class="col-md-4">
                                                <label class="form-label">Type</label>
                                                <select class="form-select" name="type">
                                                    <option value="">Choose...</option>
                                                    @foreach ($types as $item)
                                                        <option value="{{ $item['id'] }}"
                                                            {{ data_get($car, 'specifications.type.id') == $item['id'] ? 'selected' : '' }}>
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-2">
                                            @if (!empty(data_get($car, 'performance.fuel_economy.id')))
                                                <input type="hidden" name="fuel_economy_id"
                                                       value="{{ data_get($car, 'performance.fuel_economy.id') }}">
                                            @endif

                                            <!-- Min Fuel Economy -->
                                            <div class="col-md-4">
                                                <label class="form-label">Min Fuel Economy</label>
                                                <input type="number" class="form-control" name="min_fuel_economy"
                                                       value="{{ old('min_fuel_economy', data_get($car, 'performance.fuel_economy.min', '')) }}">
                                            </div>

                                            <!-- Max Fuel Economy -->
                                            <div class="col-md-4">
                                                <label class="form-label">Max Fuel Economy</label>
                                                <input type="number" class="form-control" name="max_fuel_economy"
                                                       value="{{ old('max_fuel_economy', data_get($car, 'performance.fuel_economy.max', '')) }}">
                                            </div>

                                            <!-- Colors -->
                                            <div class="col-md-4">
                                                <label class="form-label">Color (AR)</label>
                                                <input type="text" class="form-control" name="color_ar"
                                                       value="{{ old('color_ar', data_get($car, 'appearance.color_ar', '')) }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Color (EN)</label>
                                                <input type="text" class="form-control" name="color_en"
                                                       value="{{ old('color_en', data_get($car, 'appearance.color_en', '')) }}">
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-2">
                                            @if (!empty(data_get($car, 'appearance.size.id')))
                                                <input type="hidden" name="size_id"
                                                       value="{{ data_get($car, 'appearance.size.id') }}">
                                            @endif

                                            <!-- Dimensions -->
                                            <div class="col-md-4">
                                                <label class="form-label">Length</label>
                                                <input type="number" class="form-control" name="length"
                                                       value="{{ old('length', data_get($car, 'appearance.size.length', '')) }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Width</label>
                                                <input type="number" class="form-control" name="width"
                                                       value="{{ old('width', data_get($car, 'appearance.size.width', '')) }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Height</label>
                                                <input type="number" class="form-control" name="height"
                                                       value="{{ old('height', data_get($car, 'appearance.size.height', '')) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Part 2 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEngineType">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseEngineType" aria-expanded="false"
                                            aria-controls="collapseEngineType">
                                        Part 2
                                    </button>
                                </h2>
                                <div id="collapseEngineType" class="accordion-collapse collapse"
                                     aria-labelledby="headingEngineType" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row g-3">

                                            <!-- Engine Type -->
                                            <div class="col-md-4">
                                                <label class="form-label">Engine Type</label>
                                                <select class="form-select" name="engine_type">
                                                    <option value="">Choose...</option>
                                                    @foreach ($engineTypes as $item)
                                                        <option value="{{ $item['id'] }}"
                                                            {{ data_get($car, 'performance.engine_type.id') == $item['id'] ? 'selected' : '' }}>
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Mileage -->
                                            <div class="col-md-4">
                                                <label class="form-label">Mileage</label>
                                                <input type="number" class="form-control" name="mileage"
                                                       value="{{ old('mileage', data_get($car, 'performance.mileage.value', '')) }}">
                                            </div>

                                            <!-- Vehicle Status -->
                                            <div class="col-md-4">
                                                <label class="form-label">Vehicle Status</label>
                                                <select class="form-select" name="vehicle_status">
                                                    <option value="">Choose...</option>
                                                    @foreach ($vehicleStatuses as $item)
                                                        <option value="{{ $item['id'] }}"
                                                            {{ data_get($car, 'performance.vehicle_status.id') == $item['id'] ? 'selected' : '' }}>
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-2">

                                            <!-- Refurbishment -->
                                            <div class="col-md-4">
                                                <label class="form-label">Refurbishment Status</label>
                                                <select class="form-select" name="refurbishment_status">
                                                    <option value="">Choose...</option>
                                                    @foreach ($refurbishmentStatuses as $item)
                                                        <option value="{{ $item->value }}"
                                                            {{ data_get($car, 'performance.refurbishment_status_en') == $item->value ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Transmission Type -->
                                            <div class="col-md-4">
                                                <label class="form-label">Transmission Type</label>
                                                <select class="form-select" name="transmission_type">
                                                    <option value="">Choose...</option>
                                                    @foreach ($transmissionTypes as $item)
                                                        <option value="{{ $item['id'] }}"
                                                            {{ data_get($car, 'specifications.transmission_type.id') == $item['id'] ? 'selected' : '' }}>
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Drive Type -->
                                            <div class="col-md-4">
                                                <label class="form-label">Drive Type</label>
                                                <select class="form-select" name="drive_type">
                                                    <option value="">Choose...</option>
                                                    @foreach ($driveTypes as $item)
                                                        <option value="{{ $item['id'] }}"
                                                            {{ data_get($car, 'specifications.drive_type.id') == $item['id'] ? 'selected' : '' }}>
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Engine Capacity -->
                                            <div class="col-md-4">
                                                <label class="form-label">Engine Capacity (cc)</label>
                                                <input type="number" class="form-control" name="engine_capacity"
                                                       value="{{ old('engine_capacity', data_get($car, 'performance.engine_capacity_cc', '')) }}">
                                            </div>

                                            @if (!empty(data_get($car, 'performance.horsepower.id')))
                                                <input type="hidden" name="horsepower_id"
                                                       value="{{ data_get($car, 'performance.horsepower.id') }}">
                                            @endif

                                            <!-- Horse Power -->
                                            <div class="col-md-4">
                                                <label class="form-label">Min Horse Power</label>
                                                <input type="number" class="form-control" name="min_horse_power"
                                                       value="{{ old('min_horse_power', data_get($car, 'performance.horsepower.min', '')) }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Max Horse Power</label>
                                                <input type="number" class="form-control" name="max_horse_power"
                                                       value="{{ old('max_horse_power', data_get($car, 'performance.horsepower.max', '')) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Part 3 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTrim">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTrim" aria-expanded="false"
                                            aria-controls="collapseTrim">
                                        Part 3
                                    </button>
                                </h2>
                                <div id="collapseTrim" class="accordion-collapse collapse" aria-labelledby="headingTrim"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row g-3">

                                            <!-- Trim -->
                                            <div class="col-md-4">
                                                <label class="form-label">Trim</label>
                                                <select class="form-select" name="trim">
                                                    <option value="">Choose...</option>
                                                    @foreach ($trim as $item)
                                                        <option value="{{ $item['id'] }}"
                                                            {{ data_get($car, 'trim.id') == $item['id'] ? 'selected' : '' }}>
                                                            {{ $item['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Images Upload -->
                                            <div class="col-md-4">
                                                <label class="form-label">Upload Images</label>
                                                <input class="form-control" type="file" name="images[]" multiple>
                                            </div>

                                            <!-- Current Images -->
                                            <div class="col-12 mt-3">
                                                <label class="form-label">Current Images</label>
                                                <div class="row">
                                                    @forelse (data_get($car, 'images', []) as $index => $image)
                                                        <div class="col-md-3 text-center mb-3">
                                                            <img src="{{ asset('storage/' . $image['location']) }}" class="img-thumbnail mb-2" style="max-height:150px;">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="delete_images[]" value="{{ $image['id'] }}"
                                                                       id="deleteImage{{ $index }}">
                                                                <label class="form-check-label" for="deleteImage{{ $index }}">Delete</label>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p class="text-muted">No images uploaded yet.</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="inputNotes" class="form-label">Notes</label>
                                                <textarea
                                                    class="form-control"
                                                    id="inputNotes"
                                                    name="notes"
                                                    rows="3"
                                                    placeholder="Enter any notes about the car">{{ old('notes', data_get($car, 'notes', '')) }}</textarea>
                                                @error('notes')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const brandSelect = document.getElementById('inputBrand');
            const modelSelect = document.getElementById('inputModel');

            brandSelect.addEventListener('change', function () {
                const brandId = this.value;

                modelSelect.innerHTML = '<option value="">Loading...</option>';
                modelSelect.disabled = true;

                if (brandId) {
                    fetch(`{{ url('admin/cars/get-models') }}/${brandId}`)
                        .then(response => response.json())
                        .then(data => {
                            modelSelect.innerHTML = '<option value="">Choose...</option>';
                            data.forEach(model => {
                                const option = document.createElement('option');
                                option.value = model.id;
                                option.textContent = model.name.en;
                                modelSelect.appendChild(option);
                            });
                            modelSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error fetching models:', error);
                            modelSelect.innerHTML = '<option value="">Error loading models</option>';
                        });
                } else {
                    modelSelect.innerHTML = '<option value="">Select Brand First...</option>';
                    modelSelect.disabled = true;
                }
            });
        });
    </script>

@endsection
