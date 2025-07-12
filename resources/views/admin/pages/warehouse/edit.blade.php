@extends('admin.layout.default')

@section('warehouse','active menu-item-open')
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

  .alert-success {
    background-color: #d4edda;
    color: #155724;
  }

  .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
  }

  @media (max-width: 600px) {
    .form-wrapper {
      padding: 18px;
      margin-top: 50px;
    }

    .btn-primary {
      width: 100%;
    }
  }
</style>

<div class="form-wrapper">
  <h2>✏️ Edit Warehouse</h2>

  <div id="alert-box" class="alert"></div>

  <form id="editWarehouseForm">
    @csrf

    <div class="form-group">
      <label>Store Name</label>
      <input type="text" name="store" class="form-control" value="{{ $warehouse->store }}">
    </div>

    <div class="form-group">
      <label>About</label>
      <input type="text" name="about" class="form-control" value="{{ $warehouse->about }}">
    </div>

    <div class="form-group">
      <label>Location</label>
      <input type="text" name="location" class="form-control" value="{{ $warehouse->location }}">
    </div>
    <button type="submit" class="btn-primary">Update</button>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('#editWarehouseForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
      url: "{{ route('admin.warehouse.update', $warehouse->id) }}",
      type: "POST",
      data: $(this).serialize(),
      success: function(response) {
        window.location.href = "{{ url('admin/warehouse/list') }}";
      },
      error: function(xhr) {
        let errors = xhr.responseJSON.errors;
        let errorHtml = '<ul>';
        $.each(errors, function(key, value) {
          errorHtml += `<li>${value[0]}</li>`;
        });
        errorHtml += '</ul>';

        $('#alert-box')
          .removeClass()
          .addClass('alert alert-danger')
          .html(errorHtml)
          .show();
      }
    });
  });
</script>

@endsection
