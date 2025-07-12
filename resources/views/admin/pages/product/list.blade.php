@extends('admin.layout.default')

@section('product', 'active menu-item-open')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f1f7fc;
        margin: 0;
        padding: 20px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .header h1 {
        color: #1d3557;
        font-size: 28px;
        display: flex;
        align-items: center;
    }

    .header h1::before {
        content: '🌿';
        margin-right: 10px;
        font-size: 26px;
    }

    .btn-add {
        background-color: #1d3557;
        color: white;
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        margin-left: 10px;
    }

    .search-filter {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-filter input[type="text"] {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
    }

    .search-filter button {
        padding: 10px 16px;
        border: 1px solid #ccc;
        background-color: white;
        border-radius: 8px;
        cursor: pointer;
    }

    .subtitle {
        font-size: 12px;
        color: #6c757d;
        margin-top: -10px;
        margin-bottom: 20px;
    }

    .card {
        background: linear-gradient(to right, #2a9d8f, #457b9d);
        padding: 14px;
        color: white;
        font-weight: bold;
        border-radius: 10px 10px 0 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        border-radius: 0 0 10px 10px;
        overflow: hidden;
    }

    th, td {
        padding: 14px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    td.status {
        text-align: center;
    }

    .badge {
        background-color: #d1f4e2;
        color: #1b9c72;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
    }

    .load-more-container {
        text-align: center;
        margin-top: 20px;
    }

    #load-more {
        padding: 10px 20px;
        background-color: #1d3557;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
    }

    /* Custom Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .modal-content {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        width: 90%;
        margin: auto;
        margin-top: 12px;
        max-width: 400px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .modal-content h3 {
        margin-bottom: 10px;
    }

    .modal-content input {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        margin-bottom: 20px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .modal-buttons {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .modal-buttons button {
        flex: 1;
        padding: 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .modal-buttons button:first-child {
        background-color: #d90429;
        color: white;
    }

    .modal-buttons button:last-child {
        background-color: #ccc;
    }

    @media (max-width: 768px) {
        .container
        {
            margin-top:15px;
        }
        .header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .btn-add {
            font-size: 14px;
            padding: 10px;
        }

        .search-filter {
            flex-direction: column;
            gap: 10px;
        }

        .search-filter input[type="text"],
        .search-filter button {
            width: 100%;
            font-size: 14px;
            padding: 10px;
        }

        table {
            font-size: 13px;
            overflow-x: auto;
            display: block;
            width: 100%;
        }

        thead th,
        tbody td {
            padding: 10px;
            white-space: nowrap;
        }

        .card {
            font-size: 16px;
            padding: 10px;
        }

        #load-more {
            width: 100%;
            font-size: 14px;
            padding: 12px;
        }
    }
</style>

<div class="header">
    <h1>Product List</h1>
    <div class="d-flex">
       @if(in_array($permission['export'] ?? 0, [1,2,3,4,5]))
            <a href="{{ route('admin.export.form') }}" class="btn-add">Export</a>
        @endif
        @if(in_array($permission['product'] ?? 0, [3,4,5]))
            <a href="{{ url('/admin/product/add') }}" class="btn-add">+ Add New Product</a>
        @endif
    </div>
</div>

<div class="search-filter">
    <input type="text" id="search-box" placeholder="Search products..." />
    <button>🔽 Filter</button>
</div>
<div class="subtitle">Fuzzy search enabled - spelling mistakes allowed</div>

<div class="card">Medicinal Plant Products</div>
<table class="sortable-table">
    <thead>
       <tr>
            <th>#</th>
            <th>SKU</th>
            <th>Name</th>
            <th>Category</th>
            <th>Common Names</th>
            <th>Stock</th>
            <th>Track Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="product-tbody">
        <!-- Loaded by AJAX -->
    </tbody>
</table>

<div class="load-more-container">
    <button id="load-more">Load More</button>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <h3>Confirm Deletion</h3>
        <p>Please enter your password to delete this product:</p>
        <input type="password" id="delete-password" placeholder="Enter password">
        <input type="hidden" id="delete-product-id">
        <div class="modal-buttons">
            <button onclick="confirmDelete()">Delete</button>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const baseUrl = "{{ url('/') }}";
    const permissionProduct = {{ $permission['product'] ?? 0 }};
    const permissionLot = {{ $permission['lots'] ?? 0 }};
    let page = 1;
    let serial = 1;
    let search = "";
 

    $(document).ready(function() {
        loadProducts();

        $('#load-more').on('click', function () {
            page++;
            loadProducts();
        });

        $('#search-box').on('input', function () {
            search = $(this).val();
            page = 1;
            loadProducts(true);
        });
    });

    function deleteProduct(id) {
        $('#delete-product-id').val(id);
        $('#delete-password').val('');
        $('#deleteModal').fadeIn();
    }

    function closeModal() {
        $('#deleteModal').fadeOut();
    }

    function confirmDelete() {
        const id = $('#delete-product-id').val();
        const password = $('#delete-password').val().trim();

        if (!password) {
            alert('Password is required.');
            return;
        }

        $.ajax({
            url: `${baseUrl}/admin/product/delete/${id}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE',
                password: password
            },
            success: function(response) {
                alert(response.message || 'Product deleted successfully!');
                closeModal();
                page = 1;
                loadProducts(true);
            },
            error: function(xhr) {
                if (xhr.status === 403 || xhr.status === 422) {
                    alert(xhr.responseJSON.message || 'Password incorrect!');
                } else {
                    alert('Delete failed!');
                }
            }
        });
    }

    function loadProducts(reset = false) {
        $.ajax({
            url: "{{ route('admin.product.index') }}",
            method: "GET",
            data: { page, search },
            success: function (res) {
                if (reset) {
                    $('#product-tbody').html('');
                    serial = 1;
                    $('#load-more').show();
                }

                if (res.data.length === 0) {
                    $('#load-more').hide();
                    return;
                }

                let html = '';
                res.data.forEach((item) => {
                   html += `
                        <tr ${permissionLot > 0 ? `onclick="window.location='${baseUrl}/admin/product/lot/list/${item.id}'"` : ''} style="cursor:${permissionLot > 0 ? 'pointer' : 'default'};">
                            <td>${serial++}</td>
                            <td>${item.sku}</td>
                            <td>${item.name ?? '-'}</td>
                            <td>${item.category ?? '-'}</td>
                            <td>${item.common_names ?? '-'}</td>
                            <td>${item.stock_quantity ?? 0}</td>
                            <td>${item.track_price ? '✅' : '❌'}</td>
                            <td>
                                ${[4, 5].includes(permissionProduct) ? `
                                    <a href="${baseUrl}/admin/product/edit/${item.id}" class="btn btn-sm btn-outline-primary me-1" title="Edit" onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                ` : ''}

                                ${permissionProduct == 5 ? `
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-danger" title="Delete" onclick="event.stopPropagation(); deleteProduct(${item.id});">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                ` : ''}
                            </td>
                        </tr>`;
                    });


                $('#product-tbody').append(html);

                if (!res.next_page_url) {
                    $('#load-more').hide();
                }
            },
            error: function () {
                alert('Failed to load data');
            }
        });
    }
</script>

@endsection
