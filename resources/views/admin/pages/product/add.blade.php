@extends('admin.layout.default')

@section('product','active menu-item-open')

@section('content')

<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f1f7fc;
    margin: 0;
    padding: 20px;
  }

  .form-wrapper {
    background-color: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: auto;
  }

  .form-wrapper h2 {
    color: #1d3557;
    margin-bottom: 20px;
  }

  .form-group {
    margin-bottom: 16px;
  }

  .form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #333;
  }

  .form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
  }

  .btn-primary {
    background-color: #1d3557;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    margin-top: 10px;
  }

  .alert {
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 16px;
    display: none;
  }

  .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
  }

  @media (max-width: 600px) {
    .form-wrapper {
      padding: 18px;
      margin-top:50px;
    }

    .btn-primary {
      width: 100%;
    }
  }
</style>

<div class="form-wrapper">
  <h2>➕ Add New Product</h2>

  <div id="alert-box" class="alert alert-danger"></div>

  <form id="addProductForm">
    @csrf

    <!-- Basic Fields -->
    <div class="form-group">
      <label>Product Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="form-group">
      <label>SKU</label>
      <input type="text" name="sku" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Category</label>
      <select name="category" class="form-control" required>
        <option value="">-- Select Category --</option>
        <option value="Root">Root</option>
        <option value="Stem">Stem</option>
        <option value="Seed">Seed</option>
        <option value="Flower">Flower</option>
        <option value="Other">Other</option>
      </select>
    </div>

    <!-- Advanced Section -->
    <h4 style="margin-top: 30px; color: #1d3557;">Advanced (optional)</h4>

    <div class="form-group">
      <label>English Name</label>
      <input type="text" name="english_name" class="form-control">
    </div>

    <div class="form-group">
      <label>Common Names (comma separated)</label>
      <input type="text" name="common_names" class="form-control">
    </div>

    <div class="form-group">
      <label>Botanical Name</label>
      <input type="text" name="botanical_name" class="form-control">
    </div>

    <div class="form-group">
      <label>Harvest Season</label>
      <input type="text" name="harvest_season" class="form-control">
    </div>

    <div class="form-group">
      <label>Location Found</label>
      <input type="text" name="location_found" class="form-control">
    </div>

    <div class="form-group">
      <label>Suppliers</label>
      <input type="text" name="suppliers" class="form-control">
    </div>

    <div class="form-group">
      <label>Volume</label>
      <input type="text" name="volume" class="form-control">
    </div>

    <div class="form-group">
      <label>
        <input type="checkbox" name="track_price" value="1">
        Track Price
      </label>
    </div>

    <button type="submit" class="btn-primary">Submit</button>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('#addProductForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
      url: "{{ route('admin.product.store') }}",
      type: "POST",
      data: $(this).serialize(),
      success: function(response) {
        window.location.href = "{{ url('admin/product/list') }}";
        $('#addProductForm')[0].reset();
      },
      error: function(xhr) {
        let errors = xhr.responseJSON.errors;
        let errorHtml = '<ul>';
        $.each(errors, function(key, value) {
          errorHtml += `<li>${value[0]}</li>`;
        });
        errorHtml += '</ul>';

        $('#alert-box')
          .html(errorHtml)
          .show();
      }
    });
  });
</script>

@endsection
