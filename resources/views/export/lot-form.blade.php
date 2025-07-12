@extends('admin.layout.default')

@section('product', 'active menu-item-open')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h3 class="mb-0"><i class="bi bi-box-arrow-down me-2"></i> Export Lots</h3>
                </div>
                <div class="card-body p-4">

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="GET" action="{{ route('admin.lots.export') }}">
                        {{-- Fields Section --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Fields:</label>
                            <div class="row">
                                @foreach($fields as $field)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fields[]" value="{{ $field }}" id="field_{{ $field }}">
                                            <label class="form-check-label" for="field_{{ $field }}">
                                                {{ ucfirst(str_replace('_', ' ', $field)) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Warehouse & Product --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="warehouse_id" class="form-label fw-bold">Warehouse</label>
                                <select name="warehouse_id" class="form-control">
                                    <option value="">All</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->store }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="product_id" class="form-label fw-bold">Product</label>
                                <select name="product_id" class="form-control">
                                    <option value="">All</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->common_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Date Filters --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="from_date" class="form-label fw-bold">Start Date</label>
                                <input type="date" name="from_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="to_date" class="form-label fw-bold">End Date</label>
                                <input type="date" name="to_date" class="form-control" required>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-download me-2"></i> Export Selected Data
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
