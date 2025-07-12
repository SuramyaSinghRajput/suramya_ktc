@extends('admin.layout.default')

@section('product', 'active menu-item-open')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h3 class="mb-0">📤 Export Products</h3>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.export.products') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Fields:</label>
                            <div class="row">
                                @foreach($fields as $field)
                                    <div class="col-md-4">
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

                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-bold">Start Date:</label>
                                <input type="date" name="start_date" class="form-control" id="start_date">
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-bold">End Date:</label>
                                <input type="date" name="end_date" class="form-control" id="end_date">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-download"></i> Export Selected Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
