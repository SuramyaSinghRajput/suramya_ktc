@extends('admin.layout.default')

@section('warehouse', 'active menu-item-open')

@section('content')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f1f7fc; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { color: #1d3557; font-size: 28px; }
        .btn-add { background-color: #1d3557; color: white; padding: 10px 18px; border: none; border-radius: 6px; font-size: 14px; }
        .search-filter { display: flex; gap: 10px; margin-bottom: 20px; }
        .search-filter input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 8px; }
        table { width: 100%; background-color: white; border-radius: 10px; overflow: hidden; }
        th, td { padding: 14px; border-bottom: 1px solid #eee; text-align: left; }
        th { background-color: #f8f9fa; }
        .load-more-container { text-align: center; margin-top: 20px; }
        #load-more { padding: 10px 20px; background: #1d3557; color: white; border: none; border-radius: 6px; cursor: pointer; }
        #load-more:hover { background: #16324e; }
    </style>
</head>

<div class="header">
    <h1>Warehouse Bills</h1>
    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addBillModal">+ Add New Bill</button>
</div>

<div class="search-filter">
    <input type="text" id="search-box" placeholder="Search bills..." />
</div>

<table>
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Bill Number</th>
            <th>Bill Date</th>
            <th>Clear Date</th>
            <th>Payment</th>
            <th>Lot</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="bills-tbody">
        @forelse ($bills as $bill)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $bill->bill_number }}</td>
                <td>{{ $bill->bill_date }}</td>
                <td>{{ $bill->clear_date }}</td>
                <td>{{ $bill->payment_method }}</td>
                <td>
                    @php $lotIds = json_decode($bill->lot_id, true); @endphp
                    <ul>
                        @foreach ($lotIds as $lotId)
                            <li>{{ $lotId }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="editBillById({{ $bill->id }})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteBill({{ $bill->id }})">Delete</button> 
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No bills found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="load-more-container">
    <button id="load-more">Load More</button>
</div>

{{-- ==================== ADD BILL MODAL ==================== --}}
<div class="modal fade" id="addBillModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="addBillForm" method="post" action="{{ route('admin.warehouse.bills.store') }}" class="modal-content" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
            <div class="modal-header">
                <h5 class="modal-title">Add New Bill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label>Bill Number</label>
                    <input type="text" class="form-control" name="bill_number" required>
                </div>
                <div class="mb-2">
                    <label>Bill Date</label>
                    <input type="date" class="form-control" name="bill_date" required>
                </div>
                <div class="mb-2">
                    <label>Clear Date</label>
                    <input type="date" class="form-control" name="clear_date">
                </div>
                <div class="mb-2">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="">Select method</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                        <option value="upi">UPI</option>
                        <option value="cheque">Cheque</option>
                        <option value="online">Online</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Bill File</label>
                    <input type="file" class="form-control" name="bill_file" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="mb-2">
                    <label>Remark</label>
                    <textarea class="form-control" name="remark" rows="2"></textarea>
                </div>
                <div class="mb-2">
                    <label>Lots</label>
                    <select name="lot_id[]" class="form-control selectpicker" multiple data-live-search="true">
                        @foreach ($lots as $lot)
                            <option value="{{ $lot->id }}">{{ $lot->lot_number }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Add Bill</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== EDIT BILL MODAL (Single Instance) ==================== --}}
<div class="modal fade" id="editBillModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editBillForm" class="modal-content">
            @csrf
            <input type="hidden" id="edit_bill_id" name="bill_id">
            <div class="modal-header">
                <h5 class="modal-title">Edit Bill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label>Bill Number</label>
                    <input type="text" class="form-control" name="bill_number" required>
                </div>
                <div class="mb-2">
                    <label>Bill Date</label>
                    <input type="date" class="form-control" name="bill_date" required>
                </div>
                <div class="mb-2">
                    <label>Clear Date</label>
                    <input type="date" class="form-control" name="clear_date">
                </div>
                <div class="mb-2">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                        <option value="upi">UPI</option>
                        <option value="cheque">Cheque</option>
                        <option value="online">Online</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Remark</label>
                    <textarea class="form-control" name="remark" rows="2"></textarea>
                </div>
                <div class="mb-2">
                    <label>Lots</label>
                    <select name="lot_id[]" class="form-control selectpicker" multiple data-live-search="true">
                        @foreach ($lots as $lot)
                            <option value="{{ $lot->id }}">{{ $lot->lot_number }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Update</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== DELETE MODAL ==================== --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="deleteBillForm">
            @csrf
            <input type="hidden" name="bill_id" id="delete_bill_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Admin Password</label>
                        <input type="password" class="form-control" name="admin_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
    const baseUrl = "{{ url('/') }}";
    const warehouseId = @json($warehouse->id);
    let page = 1, search = "";

    $('#load-more').click(() => { page++; loadBills(); });
    $('#search-box').on('input', function () { search = $(this).val(); page = 1; loadBills(true); });

    function editBillById(id) {
        $.get(`${baseUrl}/admin/warehouse/bills/edit/${id}`, function(bill) {
            $('#editBillModal').modal('show');
            $('#edit_bill_id').val(bill.id);
            $('#editBillForm [name="bill_number"]').val(bill.bill_number);
            $('#editBillForm [name="bill_date"]').val(bill.bill_date);
            $('#editBillForm [name="clear_date"]').val(bill.clear_date);
            $('#editBillForm [name="payment_method"]').val(bill.payment_method);
            $('#editBillForm [name="remark"]').val(bill.remark);
            $('#editBillForm [name="lot_id[]"]').val(bill.lot_ids || []).selectpicker('refresh');
        });
    }

    function deleteBill(id) {
        $('#delete_bill_id').val(id);
        $('#confirmDeleteModal').modal('show');
    }

    $('#deleteBillForm').submit(function(e) {
        e.preventDefault();
        const id = $('#delete_bill_id').val();
        $.post(`${baseUrl}/admin/warehouse/bills/delete/${id}`, $(this).serialize())
            .done(res => {
                alert(res.message);
                $('#confirmDeleteModal').modal('hide');
                page = 1;
                loadBills(true);
            })
            .fail(xhr => {
                alert(xhr.responseJSON?.message || 'Delete failed.');
            });
    });

    $('#editBillForm').submit(function(e) {
        e.preventDefault();
        const id = $('#edit_bill_id').val();
        const formData = new FormData(this);
        formData.append('_method', 'POST');

        $.ajax({
            url: `${baseUrl}/admin/warehouse/bills/update/${id}`,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success(res) {
                alert(res.message);
                $('#editBillModal').modal('hide');
                page = 1;
                loadBills(true);
            },
            error(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let msg = 'Validation error:\n';
                    for (const key in errors) {
                        msg += `${errors[key][0]}\n`;
                    }
                    alert(msg);
                } else {
                    alert('Update failed.');
                }
            }
        });
    });

    $('#addBillForm').submit(function (e) {
        const billDate = new Date($('[name="bill_date"]').val());
        const clearDate = new Date($('[name="clear_date"]').val());
        if ($('[name="clear_date"]').val() !== '' && clearDate < billDate) {
            e.preventDefault();
            alert("Clear Date must be after or equal to Bill Date.");
        }

        if (!$('[name="payment_method"]').val()) {
            e.preventDefault();
            alert("Please select a payment method.");
        }
    });
</script>
@endsection
