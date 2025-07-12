@extends('admin.layout.default')

@section('warehouse', 'active menu-item-open')

@section('content')

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f1f7fc;
            padding: 20px;
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
        }

        .btn-add {
            background-color: #1d3557;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-filter input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        table {
            width: 100%;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .load-more-container {
            text-align: center;
            margin-top: 20px;
        }

        #load-more {
            padding: 10px 20px;
            background: #1d3557;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        #load-more:hover {
            background: #16324e;
        }

        .lot-tag {
            padding: 6px 12px;
            background: #0d6efd;
            color: white;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
        }

        .lot-tag .btn-close {
            font-size: 0.8rem;
            margin-left: 8px;
        }
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
        <tr onclick="viewBill({{ $bill->id }})" style="cursor: pointer;">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $bill->bill_number }}</td>
            <td>{{ $bill->bill_date }}</td>
            <td>{{ $bill->clear_date }}</td>
            <td>{{ $bill->payment_method }}</td>
            <td>
                @php
                $lotIds = json_decode($bill->lot_id, true);
                $lotStrings = [];

                foreach ($lotIds as $id) {
                $lot = $lots->firstWhere('id', $id); // Assuming $lots is passed to the view
                if ($lot) {
                $lotStrings[] = $lot->lot_number . ' (' . $lot->item . ')';
                } else {
                $lotStrings[] = $id; // fallback if lot not found
                }
                }
                @endphp
                {{ implode(', ', $lotStrings) }}
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

{{-- ==================== VIEW BILL MODAL ==================== --}}
<div class="modal fade" id="viewBillModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: 50%; margin:auto;">
            <div class="modal-header">
                <h5 class="modal-title">Bill Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Bill Number</dt>
                    <dd class="col-sm-8" id="view-bill-number"></dd>

                    <dt class="col-sm-4">Bill Date</dt>
                    <dd class="col-sm-8" id="view-bill-date"></dd>

                    <dt class="col-sm-4">Clear Date</dt>
                    <dd class="col-sm-8" id="view-clear-date"></dd>

                    <dt class="col-sm-4">Payment Method</dt>
                    <dd class="col-sm-8" id="view-payment-method"></dd>

                    <dt class="col-sm-4">Remark</dt>
                    <dd class="col-sm-8" id="view-remark"></dd>

                    <dt class="col-sm-4">Lots</dt>
                    <dd class="col-sm-8" id="view-lots"></dd>

                    <dt class="col-sm-4">Bill File</dt>
                    <dd class="col-sm-8" id="view-bill-file"></dd>
                </dl>
            </div>
        </div>
    </div>
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
                    <input type="text" class="form-control" name="bill_number" value="{{ old('bill_number') }}" required>
                    @error('bill_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-2">
                    <label>Bill Date</label>
                    <input type="date" class="form-control" name="bill_date" value="{{ old('bill_date') }}" required>
                    @error('bill_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-2">
                    <label>Clear Date</label>
                    <input type="date" class="form-control" name="clear_date" value="{{ old('clear_date') }}">
                    @error('clear_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-2">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="">Select method</option>
                        @foreach(['cash', 'bank', 'upi', 'cheque', 'online'] as $method)
                        <option value="{{ $method }}" {{ old('payment_method') == $method ? 'selected' : '' }}>
                            {{ ucfirst($method) }}
                        </option>
                        @endforeach
                    </select>
                    @error('payment_method') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-2">
                    <label>Bill File</label>
                    <input type="file" class="form-control" name="bill_file" accept=".pdf,.jpg,.jpeg,.png">
                    @error('bill_file') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-2">
                    <label>Remark</label>
                    <textarea class="form-control" name="remark" rows="2">{{ old('remark') }}</textarea>
                    @error('remark') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-2">
                    <label>Lots</label>
                    <div class="input-group">
                        <select id="lot-selector" class="form-select">
                            <option value="">-- Select a lot --</option>
                            @foreach ($lots as $lot)
                            <option value="{{ $lot->id }}" data-remaining="{{ $lot->remaining_quantity_bags_after_deduction }}">
                                {{ $lot->lot_number }} - {{$lot->item}}
                            </option>
                            @endforeach
                        </select>

                        <button type="button" class="btn btn-secondary" id="add-lot-btn">+ Add Lot</button>
                    </div>
                    <div id="selected-lots" class="mt-2 d-flex flex-wrap gap-2"></div>
                    <div id="lot-hidden-inputs"></div>
                    <div id="lot-error" class="text-danger mt-1" style="display: none;"></div>
                    @error('lot_id') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Add Bill</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>


{{-- ==================== EDIT BILL MODAL ==================== --}}
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
                    <div id="selected-lots" class="d-flex flex-wrap gap-2"></div>
                    <div id="lot-hidden-inputs"></div>
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
<script>
    $(document).on('click', '#add-lot-btn', function() {
        const select = $('#lot-selector');
        const id = select.val();
        const name = select.find('option:selected').text();
        const remaining = parseFloat(select.find('option:selected').data('remaining'));

        if (!id) return;

        if (remaining !== 0) {
            $('#lot-error')
                .text(`Lot "${name}" has non-zero remaining quantity (${remaining}). Cannot add.`)
                .show();
            return;
        }

        $('#lot-error').hide(); // Clear error if valid

        createLotTag(id, name);
        select.val('');
    });
    $('#lot-selector').on('change', function() {
        $('#lot-error').hide();
    });
</script>
<script>
    const baseUrl = "{{ url('/') }}";
    const warehouseId = @json($warehouse -> id);
    let page = 1,
        search = "";

    $('#load-more').click(() => {
        page++;
        loadBills();
    });
    $('#search-box').on('input', function() {
        search = $(this).val();
        page = 1;
        loadBills(true);
    });

    function editBillById(id) {
        $.get(`${baseUrl}/admin/warehouse/bills/edit/${id}`, function(bill) {
            $('#editBillModal').modal('show');
            $('#edit_bill_id').val(bill.id);
            $('#editBillForm [name="bill_number"]').val(bill.bill_number);
            $('#editBillForm [name="bill_date"]').val(bill.bill_date);
            $('#editBillForm [name="clear_date"]').val(bill.clear_date);
            $('#editBillForm [name="payment_method"]').val(bill.payment_method);
            $('#editBillForm [name="remark"]').val(bill.remark);

            $('#selected-lots').empty();
            $('#lot-hidden-inputs').empty();

            if (bill.lot_ids && bill.lot_names) {
                bill.lot_ids.forEach((id, i) => {
                    createLotTag(id, bill.lot_names[i]);
                });
            }
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

    $('#addBillForm').submit(function(e) {
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

    function createLotTag(id, name) {
        if ($(`#selected-lots .lot-tag[data-id="${id}"]`).length) return;
        const tagHtml = `
            <span class="lot-tag" data-id="${id}">
                ${name}
                <button type="button" class="btn-close btn-close-white btn-sm ms-1 remove-lot-btn" aria-label="Remove"></button>
            </span>
        `;
        $('#selected-lots').append(tagHtml);
        $('#lot-hidden-inputs').append(`<input type="hidden" name="lot_id[]" value="${id}" id="lot-hidden-${id}">`);
    }

    $(document).on('click', '#add-lot-btn', function() {
        const select = $('#lot-selector');
        const selectedId = select.val();
        const selectedName = select.find('option:selected').text();
        if (selectedId) {
            createLotTag(selectedId, selectedName);
            select.val('');
        }
    });

    $(document).on('click', '.remove-lot-btn', function() {
        const tag = $(this).closest('.lot-tag');
        const id = tag.data('id');
        tag.remove();
        $(`#lot-hidden-${id}`).remove();
    });
</script>
<script>
    function viewBill(id) {
        $.get(`${baseUrl}/admin/warehouse/bills/view/${id}`, function(bill) {
            $('#view-bill-number').text(bill.bill_number);
            $('#view-bill-date').text(bill.bill_date);
            $('#view-clear-date').text(bill.clear_date ?? '-');
            $('#view-payment-method').text(bill.payment_method ?? '-');
            $('#view-remark').text(bill.remark ?? '-');

           if (bill.lot_names && bill.lot_names.length > 0) {
                const lotsWithItems = bill.lot_names.map((name, i) => `${name} (${bill.items[i] ?? '-'})`);
                $('#view-lots').text(lotsWithItems.join(', '));
            } else {
                $('#view-lots').text('-');
            }


            if (bill.bill_file_url) {
                $('#view-bill-file').html(`<a href="${bill.bill_file_url}" target="_blank">View File</a>`);
            } else {
                $('#view-bill-file').text('No file uploaded');
            }

            $('#viewBillModal').modal('show');
        });
    }
</script>
@endsection