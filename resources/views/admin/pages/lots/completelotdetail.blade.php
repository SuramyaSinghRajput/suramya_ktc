@extends('admin.layout.default')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    .card {
        background: white;
        overflow: scroll;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .btn-edit {
        background: #e7f1ff;
        color: #004085;
        margin-right: 10px;
    }

    .btn-back {
        background: #f0c93d;
        color: #333;
        text-decoration: none;
    }

    .tabs {
        display: flex;
        border-bottom: 2px solid #ccc;
        margin-bottom: 20px;
    }

    .tabs button {
        background: none;
        border: none;
        font-weight: bold;
        padding: 10px 15px;
        cursor: pointer;
        color: #003366;
    }

    .tabs button.active {
        border-bottom: 3px solid #0b3d91;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .info-block strong {
        display: block;
        color: #666;
    }

    .status-badge {
        background: #d4f1ff;
        color: #007bff;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 5px;
        display: inline-block;
    }

    .section-title {
        font-weight: bold;
        margin-top: 25px;
        margin-bottom: 10px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 5px;
        color: #003366;
    }

    .description,
    .images,
    .history {
        padding: 10px;
        background: #f9fbfd;
        border-radius: 5px;
        margin-bottom: 15px;
        color: #333;
    }

    .no-image {
        color: #999;
        font-style: italic;
    }

    .history-timeline {
        border-left: 3px solid #0b3d91;
        padding-left: 10px;
    }

    .history-entry strong {
        display: block;
        color: #003366;
    }

    .complete-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        display: block;
        margin: 20px auto 0;
        text-decoration: none;
    }

    .complete-btn.btn-secondary {
        background-color: #6c757d;
        pointer-events: none;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        margin-bottom: 20px;
    }

    th,
    td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: left;
    }

    th {
        background-color: #f0f2f5;
        color: #333;
    }

    .action-buttons i {
        margin-right: 8px;
        cursor: pointer;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 100;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background: rgba(0, 0, 0, 0.4);
    }

    div#lotFormModal {
        width: 50%;
        margin-left: 25%;
    }

    .modal-content {
        background: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 10px;
        width: 50%;
        position: relative;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .existing-media img,
    .existing-media video {
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    a.active-btn.btn.btn-success {
        width: 100%;
    }
</style>
@endsection

@section('lots', 'active menu-item-open')
@section('content')

@if (!$lots)
<div class="alert alert-warning" role="alert">
    No active lot found.
</div>
<a href="javascript:history.back()" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Back
</a>
@else
<div class="card">
    <div class="header">
        <h2>Lot Details - {{ $lots->lot_number }}</h2>
        <div>
            @if(in_array($permission['lots'], [4, 5]))
                <button type="button" class="btn btn-sm btn-primary edit-lot" data-id="{{ $lots->id }}"
                    onclick="openLotForm()">
                    <i class="fas fa-edit"></i> Edit Lot
                </button>
            @endif
              <!-- Modal for Editing Lot -->
            <div id="lotFormModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeLotForm()">&times;</span>
                    <form id="lotForm" action="{{ url('/admin/lots/details/edit/' . $lots->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="lot_id" value="{{ $lots->id }}">
                        <input type="hidden" name="warehouse_id" value="{{ $lots->warehouse_id }}">

                        <h3 style="text-align: center;">✏️ Edit Lot</h3>
                        <div class="row">

                            <!-- Lot Number -->
                            <div class="col-md-6 mb-3">
                                <label>Lot Number</label>
                                <input type="text" name="lot_number" value="{{ $lots->lot_number }}" class="form-control" required>
                            </div>

                            <!-- Product -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product</label>
                                <select name="product_id" class="form-select" required>
                                    <option disabled>-- Select Product --</option>
                                    @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ $product->id == $lots->product_id ? 'selected' : '' }}>
                                       {{ $product->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Labour Rate -->
                            <div class="col-md-6 mb-3">
                                <label>Labour Rate</label>
                                <input type="number" value="{{ $lots->labour_rate }}" step="0.01" name="labour_rate" class="form-control" required>
                            </div>

                            <!-- Rent -->
                            <div class="col-md-6 mb-3">
                                <label>Rent</label>
                                <input type="number" step="0.01" value="{{ $lots->warehouse_rate }}" name="rent" class="form-control" required>
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-6 mb-3">
                                <label>Quantity</label>
                                <input type="number" name="qty" value="{{ $lots->quantity_bags }}" class="form-control" required>
                            </div>

                            <!-- Date -->
                            <div class="col-md-6 mb-3">
                                <label>Date</label>
                                <input type="date" value="{{ $lots->date }}" name="date" class="form-control" required>
                            </div>

                            <!-- Each Bag Weight -->
                            <div class="col-md-6 mb-3">
                                <label>Each Bag Weight (optional)</label>
                                <input type="number" value="{{ $lots->each_bag_weight }}" step="0.01" name="each_bag_weight" class="form-control">
                            </div>

                            <!-- Media Upload -->
                            <div class="col-md-6 mb-3">
                                <label>Media (Images/Videos)</label>
                                <div id="media-dropzone" class="p-4 text-center position-relative" style="cursor:pointer;">
                                    <input type="file" name="media[]" id="mediaInput" accept="image/*,video/*" multiple hidden>
                                    <div class="d-flex flex-column align-items-center justify-content-center" style="height: 150px;">
                                        <i class="fa fa-camera" style="font-size: 2rem; color: #999;"></i>
                                        <span class="mt-2 text-muted">Add Photos/Videos</span>
                                    </div>
                                </div>
                            </div>

                            <!-- New Media Preview -->
                            <div id="mediaPreview" class="col-md-12 mb-3 d-flex flex-wrap gap-2 mt-3"></div>

                            <!-- Existing Media -->
                            @if ($lots->media->count())
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Existing Media</label>
                                <div class="existing-media d-flex flex-wrap gap-2">
                                    @foreach ($lots->media as $media)
                                    @if(Str::startsWith($media->media_type, 'image/'))
                                    <img src="{{ url('storage/app/public/' . $media->media_URL) }}" alt="Image" width="100" class="rounded border">
                                    @elseif(Str::startsWith($media->media_type, 'video/'))
                                    <video width="120" height="80" controls class="rounded border">
                                        <source src="{{ url('storage/app/public/' . $media->media_URL) }}" type="{{ $media->media_type }}">
                                    </video>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Quality Description -->
                            <div class="col-md-12 mb-3">
                                <label>Quality Description</label>
                                <textarea name="quality_description" class="form-control" rows="2">{{ $lots->quality_description }}</textarea>
                            </div>

                            <!-- Packaging Remark -->
                            <div class="col-md-12 mb-3">
                                <label>Packaging Remark</label>
                                <input type="text" value="{{ $lots->packaging_remark }}" name="packaging_remark" class="form-control">
                            </div>

                            <!-- Submit -->
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success w-100">💾 Save Lot</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back to
                List</a>
        </div>
    </div>
    <div class="tabs">
        <button class="tab-btn active" data-tab="tab1"><i class="fas fa-info-circle"></i> Basic
            Information</button>
        <button class="tab-btn" data-tab="tab2"><i class="fas fa-list"></i> Deduct Quantity List</button>
    </div>
    <div id="tab1" class="tab-content active">
        <div class="row">
            <div class="col-md-3 info-block"><strong>Lot Number</strong> {{ $lots->lot_number }}</div>
            <div class="col-md-3 info-block"><strong>Item</strong> {{ $lots->item }}</div>
            <div class="col-md-3 info-block"><strong>Initial Quantity (Bags)</strong> {{ $lots->quantity_bags }}
            </div>
            <div class="col-md-3 info-block"><strong>Current Balance (Bags)</strong>
                {{ $lots->remaining_quantity_bags_after_deduction ?? 'N/A' }}
            </div>
            <div class="col-md-3 info-block"><strong>Date Added/Completed</strong>
                {{ date('d-m-Y', strtotime($lots->date)) }}
            </div>
            <div class="col-md-3 info-block"><strong>Quality</strong> {{ $lots->quality_description }}</div>
        </div>
        <div class="section-title">Expenses</div>
       <div class="row">
            <div class="col-md-3 info-block"><strong>Labour Rate</strong> ₹{{ $lots->labour_rate }}/Bag</div>
            <div class="col-md-3 info-block"><strong>Labour Amount</strong>
                ₹{{ $lots->quantity_bags * $lots->labour_rate }}</div>
            <div class="col-md-3 info-block"><strong>Rent</strong>
                {{ $lots->warehouse_rate ?? 'N/A' }}
            </div>
            <div class="col-md-3 info-block"><strong>Rent Amount</strong>
                {{ $totalRent ?? 'N/A' }}
            </div>
        </div>
        <div class="section-title">Notes/Description</div>
        <div class="description">{{ $lots->packaging_remark }}</div>
        <div class="section-title">Lot Images</div>
        @if ($lotImages && count($lotImages) > 0)
        <div class="images d-flex flex-wrap gap-2">
            @foreach ($lotImages as $image)
            @if (Str::startsWith($image->media_type, 'image/'))
            <img src="{{ URL('storage/app/public/' . $image->media_URL) }}" alt="Lot Image"
                class="img-thumbnail" style="max-width: 150px; height: auto; cursor: zoom-in;"
                onclick="zoomImage('{{ URL('storage/app/public/' . $image->media_URL) }}')">
            @elseif (Str::startsWith($image->media_type, 'video/'))
            <video controls class="img-thumbnail" style="max-width: 150px; height: auto;">
                <source src="{{ URL('storage/app/public/' . $image->media_URL) }}"
                    type="{{ $image->media_type }}">
                Your browser does not support the video tag.
            </video>
            @endif
            @endforeach
        </div>
        @else
        <div class="images no-image">No images uploaded for this lot.</div>
        @endif
        <div class="section-title">Transaction History</div>
        <div class="history">
            <div class="history-timeline">
                <div class="history-entry">
                    <span>{{ date('d-m-Y H:i A', strtotime($lots->created_at)) }}</span>
                    <strong>Lot Created:</strong> Received {{ $lots->quantity_bags }} Bags.
                </div>
                @foreach ($deductionList as $deduction)
                <div class="history-entry">
                    <span>{{ date('d-m-Y H:i A', strtotime($deduction->deduction_date)) }}</span>
                    <strong>Quantity Deducted:</strong> {{ $deduction->qty_bag }} Bags (Gate Pass:
                    {{ $deduction->gate_pass }}).
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div id="tab2" class="tab-content">
        @if ($lots->remaining_quantity_bags_after_deduction != 0)
        <div class="section-title">➕ Add New Deduction</div>
        <form action="{{ route('deduction.store') }}" method="POST" class="card p-3">
            @csrf
            <input type="hidden" name="lot_id" value="{{ $lots->id }}">
            <h5 class="mb-3"><i class="fas fa-plus-circle"></i> Add New Deduction</h5>
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="deduct_qty" class="form-label">Quantity (Bags)</label>
                    <input type="number" name="deduct_qty" id="deduct_qty" class="form-control"
                        placeholder="e.g., 50" max="{{ $lots->quantity_bags }}" required>
                </div>
                @php
                $minDate = \Carbon\Carbon::parse($lots->date)->addDay()->format('d-m-Y');
                @endphp

                <div class="col-md-3">
                    <label for="ddd" class="form-label">Deduction Date</label>
                    <input type="date" name="ddd" id="ddd" class="form-control"
                        min="{{ $minDate }}">
                </div>

                <div class="col-md-3">
                    <label for="gate_pass" class="form-label">Gate Pass #</label>
                    <input type="text" name="gate_pass" id="gate_pass" class="form-control"
                        placeholder="e.g., GP12345" required>
                </div>
                <div class="col-md-3"> {{-- Button at the end of the row --}}
                    <button type="submit" class="btn btn-warning">
                        ➕ Add Deduction
                    </button>
                </div>
            </div>
        </form>
        @else
        <div class="alert alert-info mt-3">
            ✅ All bags are deducted. No more deductions allowed.
        </div>
        @endif
        <h5 class="mt-4">Recorded Deductions</h5>
        <table class="table table-bordered table-striped sortable-table" data-default-sort-index="1" data-default-sort-order="asc"> {{-- Added Bootstrap table classes --}}
            <thead class="table-light"> {{-- Changed to table-light for Bootstrap 5 --}}
                <tr>
                    <th>S.NO.</th>
                    <th>Qty (Bags)</th>
                    <th>Gate Pass #</th>
                    <th>Warehouse Rent</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            @php $totalRent = 0; @endphp

            <tbody id="deduct-list-body">
                @forelse ($deductionList as $deduction)
                @php
                $lot = App\Models\Lots::find($deduction->lot_id);
                $lot_date = new \DateTime($lot->date);
                $deduction_date = new \DateTime($deduction->deduction_date);

                $months =
                ($deduction_date->format('Y') - $lot_date->format('Y')) * 12 +
                ($deduction_date->format('m') - $lot_date->format('m'));

                if ($deduction_date->format('d') >= $lot_date->format('d')) {
                $months += 1;
                }

                $rent = $months * $lot->warehouse_rate * $deduction->qty_bag;
                $totalRent += $rent;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $deduction->qty_bag }}</td>
                    <td>{{ $deduction->gate_pass }}</td>
                    <td>{{ $rent }}</td>
                    <td>{{ date('d-m-Y', strtotime($deduction->deduction_date)) }}</td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm openEditModal"
                            data-id="{{ $deduction->id }}" data-qty="{{ $deduction->qty_bag }}"
                            data-gate="{{ $deduction->gate_pass }}"
                            data-date="{{ $deduction->deduction_date }}"
                            data-lot-id="{{ $deduction->lot_id }}">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        |
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal{{ $deduction->id }}">
                            <i class="fas fa-trash"></i> Delete
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="confirmDeleteModal{{ $deduction->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form method="POST" action="{{ route('admin.lots.deduction.destroy', $deduction->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Password</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Please enter your password to confirm deletion:</p>
                                            <input type="password" name="admin_password" class="form-control" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </td>
                    <div class="modal fade" id="editDeductionModal" tabindex="-1"
                        aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST"
                                action="{{ route('admin.lots.deduction.update', $deduction->id) }}">
                                @csrf
                                <input type="hidden" name="deduction_id" id="edit_deduction_id">
                                <input type="hidden" name="lot_id" id="edit_lot_id">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Deduction</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="edit_gate_pass" class="form-label">Gate Pass</label>
                                            <input type="text" name="gate_pass" id="edit_gate_pass"
                                                class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_qty_bag" class="form-label">Qty Bag</label>
                                            <input type="number" name="deduct_qty" id="edit_qty_bag"
                                                class="form-control" required>
                                        </div>
                                        @php
                                        $minDate = \Carbon\Carbon::parse($lots->date)
                                        ->addDay()
                                        ->format('d-m-Y');
                                        @endphp
                                        <div class="mb-3">
                                            <label for="edit_date" class="form-label">Deduction Date</label>
                                            <input type="date" name="ddd" id="edit_date"
                                                class="form-control" min={{$minDate}} required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </tr>
                @empty
                <tr>
                    <td colspan="6">No deductions found for this lot.</td>
                </tr>
                @endforelse
                <tr>
                    <td colspan="6">
                        Total Bags: {{ $deductionList->sum('qty_bag') }}<br>
                        <strong>Total Rent: ₹{{ $totalRent }}</strong>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
</div>

<!-- Zoom Image Modal -->
<div class="modal fade" id="imageZoomModal" tabindex="-1" aria-labelledby="zoomLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-center p-3">
            <img id="zoomedImage" src="" class="img-fluid rounded" alt="Zoomed Lot Image">
            <div class="mt-3">
                <button id="shareImageBtn" class="btn btn-primary me-2">
                    <i class="fas fa-share-alt"></i> Share
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-HoA2eAEkGQ6vK/fnCm3jXtF7KYrLPxX5tAnC6h2vI8M+OAgZdkJ66GdQh3uvlFt9" crossorigin="anonymous">
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        let deductTabLoaded = false;

        const savedTabId = localStorage.getItem('activeTabId');
        if (savedTabId) {
            tabButtons.forEach(b => b.classList.remove('active'));
            tabContents.forEach(tab => tab.classList.remove('active'));

            const savedBtn = document.querySelector(`.tab-btn[data-tab="${savedTabId}"]`);
            const savedTab = document.getElementById(savedTabId);

            if (savedBtn && savedTab) {
                savedBtn.classList.add('active');
                savedTab.classList.add('active');
            }

            if (savedTabId === 'tab2') {
                deductTabLoaded = true;
            }
        }

        tabButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                tabButtons.forEach(b => b.classList.remove('active'));
                tabContents.forEach(tab => tab.classList.remove('active'));

                this.classList.add('active');
                const selectedTabId = this.getAttribute('data-tab');
                const selectedTab = document.getElementById(selectedTabId);
                selectedTab.classList.add('active');

                localStorage.setItem('activeTabId', selectedTabId);

                if (selectedTabId === 'tab2' && !deductTabLoaded) {
                    deductTabLoaded = true;
                }
            });
        });

        // Lot complete button logic
        const completeBtn = document.querySelector('.complete-btn');
        if (completeBtn && completeBtn.dataset.isComplete === '1') {
            completeBtn.classList.remove('btn-success');
            completeBtn.classList.add('btn-secondary');
            completeBtn.textContent = 'Lot Completed';
            completeBtn.style.pointerEvents = 'none';
        }
    });
</script>

<script>
    $(document).ready(function() {
        $('#lotForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                ,
                url: '/admin/lots/details/edit/' + $('#lotForm input[name="lot_id"]').val(),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: res => {
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                    console.log('Lot saved successfully:', res);
                    alert(res.message);
                    location.reload();
                },
                error: err => {
                    console.error('Error saving lot:', err);
                    alert("Error saving lot. Check console for details.");
                }
            });
        });
    });
</script>
<script>
    function openLotForm() {
        document.getElementById('lotFormModal').style.display = 'block';
    }

    function closeLotForm() {
        document.getElementById('lotFormModal').style.display = 'none';
    }

    // Optional: close on outside click
    window.onclick = function(event) {
        const modal = document.getElementById('lotFormModal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

    $(document).ready(function() {
        $(document).on('click', '.active-btn', function(e) {
            e.preventDefault();
            let lotId = $(this).data('id');
            let $this = $(this);

            if (confirm('Are you sure you want to mark this lot as Active?')) {
                $.ajax({
                    url: "{{ url('admin/lots/active') }}/" + lotId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                        alert(response.message);
                        $this.removeClass('btn-success').addClass('btn-secondary').text(
                            'Lot Active');
                        $this.css('pointer-events', 'none'); // Disable further clicks
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                    }
                });
            }
        });
    });
</script>
<script>
    document.querySelectorAll('.openEditModal').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const qty = this.getAttribute('data-qty');
            const gate = this.getAttribute('data-gate');
            const date = this.getAttribute('data-date');
            const lotId = this.getAttribute('data-lot-id');

            document.getElementById('edit_deduction_id').value = id;
            document.getElementById('edit_qty_bag').value = qty;
            document.getElementById('edit_gate_pass').value = gate;
            document.getElementById('edit_date').value = date;
            document.getElementById('edit_lot_id').value = lotId;

            new bootstrap.Modal(document.getElementById('editDeductionModal')).show();
        });
    });
</script>
<script>
  function zoomImage(imageUrl) {
    const img = document.getElementById('zoomedImage');
    img.src = imageUrl;
    img.setAttribute('data-url', imageUrl);

    const modal = new bootstrap.Modal(document.getElementById('imageZoomModal'));
    modal.show();
  }

  document.getElementById('shareImageBtn').addEventListener('click', () => {
    const imageUrl = document.getElementById('zoomedImage').getAttribute('data-url');

    if (navigator.share) {
      navigator.share({
        title: 'Lot Image',
        text: 'Check out this lot image',
        url: imageUrl
      }).catch(console.error);
    } else {
      navigator.clipboard.writeText(imageUrl)
        .then(() => alert('Image URL copied to clipboard!'))
        .catch(err => console.error('Clipboard error:', err));
    }
  });
</script>
<script>
    const mediaInput = document.getElementById('mediaInput');
    const dropzone = document.getElementById('media-dropzone');
    const preview = document.getElementById('mediaPreview');
    let mediaFiles = [];

    dropzone.addEventListener('click', () => mediaInput.click());

    mediaInput.addEventListener('change', (e) => {
        mediaFiles = Array.from(e.target.files);
        renderPreviews();
    });

    function renderPreviews() {
        preview.innerHTML = '';
        mediaFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.style.position = 'relative';
                wrapper.classList.add('me-2');

                const element = file.type.startsWith('image/') ? document.createElement('img') : document.createElement('video');
                element.src = e.target.result;
                element.classList.add('rounded');
                element.style.maxWidth = '100px';
                element.style.height = 'auto';
                if (element.tagName === 'VIDEO') element.controls = true;

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = '&times;';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '-5px';
                removeBtn.style.right = '-5px';
                removeBtn.style.background = '#f00';
                removeBtn.style.color = '#fff';
                removeBtn.style.borderRadius = '50%';
                removeBtn.style.cursor = 'pointer';
                removeBtn.style.padding = '2px 6px';
                removeBtn.onclick = () => {
                    mediaFiles.splice(index, 1);
                    renderPreviews();
                };

                wrapper.appendChild(element);
                wrapper.appendChild(removeBtn);
                preview.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }
</script>

@endsection