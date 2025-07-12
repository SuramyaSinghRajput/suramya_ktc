
<?php $__env->startSection('styles'); ?>
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
            z-index: 999999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
            position: relative;
        }

        .modal-backdrop {
            z-index: 1050;
        }

        .modal {
            z-index: 1055;
        }


        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('lots', 'active menu-item-open'); ?>
<?php $__env->startSection('content'); ?>

    <?php if(!$lots): ?>
        <div class="alert alert-warning" role="alert">
            No active lot found.
        </div>
        <a href="javascript:history.back()" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    <?php else: ?>
        <div class="card">
            <div class="header">
                <h2>Lot Details - <?php echo e($lots->lot_number); ?></h2>
                <div>
                    <button type="button" class="btn btn-sm btn-primary edit-lot" data-id="<?php echo e($lots->id); ?>"
                        onclick="openLotForm()">
                        <i class="fas fa-edit"></i> Edit Lot
                    </button>

                    <div class="modal" id="lotFormModal">
                        <div class="modal-content">
                            <span class="close" onclick="closeLotForm()">&times;</span>
                            <form id="lotForm" action="<?php echo e(url('/admin/lots/details/edit/' . $lots->id)); ?>" method="POST"
                                enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="lot_id" value="<?php echo e($lots->id ?? ''); ?>">
                                <input type="hidden" name="warehouse_id" value="<?php echo e($lots->warehouse_id); ?>">
                                <input type="hidden" name="item" id="selectedItemName" value="<?php echo e($lots->item ?? ''); ?>">
                                <h3 class="text-center">✏️ Edit Lot</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Lot Number</label>
                                        <input type="text" name="lot_number" class="form-control"
                                            value="<?php echo e($lots->lot_number ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Labour Rate</label>
                                        <input type="number" step="0.01" name="labour_rate" class="form-control"
                                            value="<?php echo e($lots->labour_rate ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Item Rate</label>
                                        <input type="number" step="0.01" name="product_rate" class="form-control"
                                            value="<?php echo e($lots->product_rate ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Quantity Bags</label>
                                        <input type="number" name="quantity_bags" class="form-control"
                                            value="<?php echo e($lots->quantity_bags ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date</label>
                                        <input type="date" name="date" class="form-control"
                                            value="<?php echo e($lots->date ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Product</label>
                                        <input type ="text" name="item" class="form-control"
                                            value="<?php echo e($lots->item ?? ''); ?>" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Each Bag Weight (optional)</label>
                                        <input type="number" step="0.01" name="each_bag_weight" class="form-control"
                                            value="<?php echo e($lots->each_bag_weight ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="mediaInput" class="form-label">Media (Images/Videos)</label>
                                        <input type="file" name="media[]" id="mediaInput" class="form-control" multiple>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Quality Description</label>
                                        <textarea name="quality_description" class="form-control" rows="2"><?php echo e($lots->quality_description ?? ''); ?></textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Packaging Remark</label>
                                        <input type="text" name="packaging_remark" class="form-control"
                                            value="<?php echo e($lots->packaging_remark ?? ''); ?>">
                                    </div>
                                    <?php if(isset($lots) && count($lots->media)): ?>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Existing Media</label>
                                            <div class="existing-media d-flex flex-wrap gap-2">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success w-100">
                                            💾 Update Lot
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <a href="<?php echo e(url()->previous()); ?>" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back to
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
                    <div class="col-md-3 info-block"><strong>Lot Number</strong> <?php echo e($lots->lot_number); ?></div>
                    <div class="col-md-3 info-block"><strong>Item</strong> <?php echo e($lots->item); ?></div>
                    <div class="col-md-3 info-block"><strong>Initial Quantity (Bags)</strong> <?php echo e($lots->quantity_bags); ?>

                    </div>
                    <div class="col-md-3 info-block"><strong>Current Balance (Bags)</strong>
                        <?php echo e($lots->remaining_quantity_bags_after_deduction ?? 'N/A'); ?></div>
                    <div class="col-md-3 info-block"><strong>Date Added/Completed</strong>
                        <?php echo e(date('Y-m-d', strtotime($lots->date))); ?></div>
                    <div class="col-md-3 info-block"><strong>Item Rate</strong> ₹<?php echo e($lots->product_rate); ?></div>
                    <div class="col-md-3 info-block"><strong>Quality</strong> <?php echo e($lots->quality_description); ?></div>
                </div>
                <div class="section-title">Expenses</div>
                <div class="row">
                    <div class="col-md-3 info-block"><strong>Total Value</strong> ₹<?php echo e($lots->total_value); ?></div>
                    <div class="col-md-3 info-block"><strong>Labour Amount</strong>
                        ₹<?php echo e($lots->quantity_bags * $lots->labour_rate); ?></div>
                    <div class="col-md-3 info-block"><strong>Rent Amount</strong>
                        <?php echo e($lots->rent_amount ? '₹' . $lots->rent_amount : 'N/A'); ?></div>
                    <div class="col-md-3 info-block"><strong>Total Cost</strong>
                        <?php echo e($lots->total_cost ? '₹' . $lots->total_cost : 'N/A'); ?></div>
                    <div class="col-md-3 info-block"><strong>Labour Rate</strong> ₹<?php echo e($lots->labour_rate); ?>/Bag</div>
                    <div class="col-md-3 info-block"><strong>Cost Per Bag</strong>
                        <?php echo e($lots->cost_per_bag ? '₹' . $lots->cost_per_bag : 'N/A'); ?></div>
                </div>
                <div class="section-title">Notes/Description</div>
                <div class="description"><?php echo e($lots->packaging_remark); ?></div>
                <div class="section-title">Lot Images</div>
                <?php if($lotImages && count($lotImages) > 0): ?>
                    <div class="images d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $lotImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(Str::startsWith($image->media_type, 'image/')): ?>
                                <img src="<?php echo e(URL('storage/app/public/' . $image->media_URL)); ?>" alt="Lot Image"
                                    class="img-thumbnail" style="max-width: 150px; height: auto;">
                            <?php elseif(Str::startsWith($image->media_type, 'video/')): ?>
                                <video controls class="img-thumbnail" style="max-width: 150px; height: auto;">
                                    <source src="<?php echo e(URL('storage/app/public/' . $image->media_URL)); ?>"
                                        type="<?php echo e($image->media_type); ?>">
                                    Your browser does not support the video tag.
                                </video>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="images no-image">No images uploaded for this lot.</div>
                <?php endif; ?>
                <div class="section-title">Transaction History</div>
                <div class="history">
                    <div class="history-timeline">
                        <div class="history-entry">
                            <span><?php echo e(date('Y-m-d H:i A', strtotime($lots->created_at))); ?></span>
                            <strong>Lot Created:</strong> Received <?php echo e($lots->quantity_bags); ?> Bags.
                        </div>
                        <?php $__currentLoopData = $deductionList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deduction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="history-entry">
                                <span><?php echo e(date('Y-m-d H:i A', strtotime($deduction->deduction_date))); ?></span>
                                <strong>Quantity Deducted:</strong> <?php echo e($deduction->qty_bag); ?> Bags (Gate Pass:
                                <?php echo e($deduction->gate_pass); ?>).
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <a href="javascript:void(0);" class="active-btn btn btn-success" data-id="<?php echo e($lots->id); ?>">
                    <i class="fas fa-check-circle"></i> Mark Lot as Active
                </a>
            </div>
            <div id="tab2" class="tab-content">
                <?php if($lots->remaining_quantity_bags_after_deduction != 0): ?>
                    <div class="section-title">➕ Add New Deduction</div>
                    <form action="<?php echo e(route('deduction.store')); ?>" method="POST" class="card p-3">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="lot_id" value="<?php echo e($lots->id); ?>">
                        <h5 class="mb-3"><i class="fas fa-plus-circle"></i> Add New Deduction</h5>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="deduct_qty" class="form-label">Quantity (Bags)</label>
                                <input type="number" name="deduct_qty" id="deduct_qty" class="form-control"
                                    placeholder="e.g., 50" max="<?php echo e($lots->quantity_bags); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label for="ddd" class="form-label">Deduction Date</label>
                                <input type="date" name="ddd" id="ddd" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="gate_pass" class="form-label">Gate Pass #</label>
                                <input type="text" name="gate_pass" id="gate_pass" class="form-control"
                                    placeholder="e.g., GP12345" required>
                            </div>
                            <div class="col-md-3"> 
                                <button type="submit" class="btn btn-warning">
                                    ➕ Add Deduction
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info mt-3">
                        ✅ All bags are deducted. No more deductions allowed.
                    </div>
                <?php endif; ?>
                <h5 class="mt-4">Recorded Deductions</h5>
                <table class="table table-bordered table-striped"> 
                    <thead class="table-light"> 
                        <tr>
                            <th>S.NO.</th>
                            <th>Qty (Bags)</th>
                            <th>Gate Pass #</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="deduct-list-body">
                        <?php $__empty_1 = true; $__currentLoopData = $deductionList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deduction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($deduction->qty_bag); ?></td>
                                <td><?php echo e($deduction->gate_pass); ?></td>
                                <td><?php echo e(date('Y-m-d', strtotime($deduction->deduction_date))); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5">No deductions found for this lot.</td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                          <td colspan="5">Total :- <?php echo e($deductionList->sum('qty_bag')); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HoA2eAEkGQ6vK/fnCm3jXtF7KYrLPxX5tAnC6h2vI8M+OAgZdkJ66GdQh3uvlFt9" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
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
              btn.addEventListener('click', function () {
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
                    url: '/admin/lots/update/' + $('#lotForm input[name="lot_id"]').val(),
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
                        url: "<?php echo e(url('admin/lots/active')); ?>/" + lotId,
                        type: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>'
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\ktc\resources\views/admin/pages/lots/completelotdetail.blade.php ENDPATH**/ ?>