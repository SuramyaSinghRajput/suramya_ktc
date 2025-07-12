@extends('admin.layout.default')

@section('warehouse', 'active menu-item-open')
@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f1f7fc;
        margin: 0;
        padding: 20px;
    }
    a{
        text-decoration:none!important;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header h1 {
        color: #1d3557;
        font-size: 28px;
        display: flex;
        align-items: center;
    }

    .header h1::before {
        content: '🏭';
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
        text-decoration: none;
    }

    .search-filter {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
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

    .card-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .warehouse-card {
        width: 240px;
        border: 1px solid #ddd;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 20px;
        background-color: #fff;
        position: relative;
    }

    .warehouse-card h2 {
        font-size: 20px;
        margin: 0 0 10px;
        color: #333;
    }

    .warehouse-card .location {
        color: #666;
        font-size: 14px;
        margin-bottom: 16px;
    }

    .warehouse-card .details {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .warehouse-card .details div {
        text-align: center;
    }

    .warehouse-card .details div b {
        display: block;
        font-size: 16px;
        color: #2a9d8f;
    }

    .warehouse-card .actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .warehouse-card .actions a {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
    }

    .btn-view {
        background-color: #e0fff5;
        color: #106f52;
    }

    .btn-edit {
        background-color: #f1f3f5;
        color: #333;
    }

    .btn-delete {
        background-color: #ffe5e5;
        color: #d90429;
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

    /* Modal */
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
    body {
        padding: 10px;
        background-color: #f6faff;
    }

    .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 20px;
        margin-top:20px;
    }

    .header h1 {
        font-size: 20px;
        justify-content: center;
        width: 100%;
        text-align: center;
    }

    .btn-add {
        width: 100%;
        margin-top: 8px;
        font-size: 15px;
        padding: 12px;
        border-radius: 10px;
        background-color: #2a9d8f;
        transition: 0.2s ease;
    }

    .search-filter {
        flex-direction: column;
        gap: 8px;
        margin-bottom: 15px;
    }

    .search-filter input,
    .search-filter button {
        width: 100%;
        padding: 12px;
        font-size: 14px;
        border-radius: 10px;
    }

    .subtitle {
        font-size: 11px;
        text-align: center;
        color: #999;
        margin-bottom: 15px;
    }

    .card-list {
        flex-direction: column;
        gap: 16px;
    }

    .warehouse-card {
        width: 100%;
        padding: 16px;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease;
    }

    .warehouse-card h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1d3557;
    }

    .warehouse-card .location {
        font-size: 13px;
        margin-bottom: 12px;
        color: #6c757d;
    }

    /* ✅ Arrange details in one line */
    .warehouse-card .details {
        flex-direction: row !important;
        justify-content: space-between;
        align-items: center;
        text-align: center;
        gap: 0;
        padding: 0 10px;
    }

    .warehouse-card .details div {
        flex: 1;
    }

    .warehouse-card .details div b {
        display: block;
        font-size: 13px;
        color: #2a9d8f;
        margin-bottom: 4px;
    }

    .warehouse-card .details div span {
        font-size: 14px;
        font-weight: 600;
        color: #1d3557;
    }

    .warehouse-card .actions {
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .warehouse-card .actions a {
        padding: 10px 12px;
        font-size: 13px;
        border-radius: 10px;
        width: 100%;
        text-align: center;
    }

    .modal-content {
        width: 90%;
        padding: 20px;
        font-size: 14px;
        border-radius: 12px;
    }

    .modal-buttons {
        flex-direction: column;
        gap: 10px;
    }

    .modal-buttons button {
        font-size: 14px;
        padding: 12px;
    }

    #load-more {
        width: 100%;
        font-size: 15px;
        padding: 12px;
        margin-top: 10px;
        border-radius: 10px;
    }
}

</style>

<div class="header">
    <h1>Warehouse List</h1>
    <a href="{{ url('/admin/warehouse/add') }}" class="btn-add">+ Add New warehouse</a>
</div>

<div class="search-filter">
    <input type="text" id="search-box" placeholder="Search warehouses..." />
    <button>🔽 Filter</button>
</div>
<div class="subtitle">Fuzzy search enabled - spelling mistakes allowed</div>

<div class="card-list" id="warehouse-cards"></div>

<div class="load-more-container">
    <button id="load-more">Load More</button>
</div>

<!-- Modal for Password Confirmation -->
<div id="passwordModal" class="modal-overlay">
    <div class="modal-content">
        <h3>Confirm Deletion</h3>
        <p>Please enter your password to delete this warehouse:</p>
        <input type="password" id="passwordInput" placeholder="Enter password">
        <div class="modal-buttons">
            <button onclick="confirmDelete()">Delete</button>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const baseUrl = "{{ url('/') }}";
    let page = 1;
    let search = "";
    let deleteId = null;

    function loadwarehouses(reset = false) {
        $.ajax({
            url: "{{ route('admin.warehouse.index') }}",
            method: "GET",
            data: { page, search },
            success: function(res) {
                if (reset) {
                    $('#warehouse-cards').html('');
                    $('#load-more').show();
                }

                if (res.data.length === 0) {
                    $('#load-more').hide();
                    return;
                }

                let html = '';
                res.data.forEach((item) => {
                    html += `
                    <a href="${baseUrl}/admin/lots/list/warehouse/${item.id}">
                        <div class="warehouse-card">
                            <h2>${item.store}</h2>
                            <div class="location"><i class="fas fa-map-marker-alt"></i> ${item.location}</div>
                            <div class="details">
                                <div><b>Products</b><br>${item.products_count}</div>
                                <div><b>Lots</b><br>${item.lots_count}</div>
                                <div><b>B. Qty.</b><br>${item.bag_quantity ?? 0}</div>
                            </div>
                            <div class="actions">
                                <a class="btn-edit" href="${baseUrl}/admin/warehouse/edit/${item.id}">Edit</a>
                                <a class="btn-delete" href="javascript:void(0);" onclick="showDeleteModal(${item.id})">Delete</a>
                                <a class="btn btn-info" href="${baseUrl}/admin/warehouse/bills/${item.id}">Bill</a>
                            </div>
                        </div>
                    </a>`;
                });

                $('#warehouse-cards').append(html);

                if (!res.next_page_url) {
                    $('#load-more').hide();
                }
            },
            error: function() {
                alert('Failed to load data');
            }
        });
    }

    $('#load-more').on('click', function() {
        page++;
        loadwarehouses();
    });

    $('#search-box').on('input', function() {
        search = $(this).val();
        page = 1;
        loadwarehouses(true);
    });

    $(document).ready(function() {
        loadwarehouses();
    });

    function showDeleteModal(id) {
        deleteId = id;
        $('#passwordInput').val('');
        $('#passwordModal').fadeIn();
    }

    function closeModal() {
        deleteId = null;
        $('#passwordModal').fadeOut();
    }

    function confirmDelete() {
        const password = $('#passwordInput').val().trim();
        if (password === '') {
            alert('Please enter your password.');
            return;
        }

        $.ajax({
            url: `${baseUrl}/admin/warehouse/delete/${deleteId}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE',
                password: password
            },
            success: function(response) {
                alert(response.message || 'Warehouse deleted successfully!');
                closeModal();
                page = 1;
                loadwarehouses(true);
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
</script>
@endsection
