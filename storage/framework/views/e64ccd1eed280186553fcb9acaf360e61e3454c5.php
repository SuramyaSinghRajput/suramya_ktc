<?php $__env->startSection('lots', 'active menu-item-open'); ?>
<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: #f0f4f8;
  }

  header {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .tabs {
    display: flex;
    border-bottom: 2px solid #ccc;
    margin-top: 10px;
  }

  .tabs button {
    padding: 10px 20px;
    border: none;
    background: none;
    cursor: pointer;
    font-weight: bold;
    border-bottom: 3px solid transparent;
    transition: 0.3s;
  }

  .tabs button.active {
    color: #0b3d91;
    border-bottom: 3px solid #0b3d91;
  }

  .container {
    padding: 20px;
  }

  .search-add {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
  }
  span#lot-count {
      margin: 20px 0px 15px 12px;
  }

  .search-add input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    margin-top: 10px;
  }

  .add-btn {
    background: #0b3d91;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  }

  th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
  }

  th {
    background: #edf3f9;
  }

  .status {
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 0.9em;
    font-weight: 500;
    text-align: center;
    display: inline-block;
  }

  .status.active {
    background: #cfefff;
    color: #007bff;
  }

  .status.partial {
    background: #fff3cd;
    color: #856404;
  }

  .status.completed {
    background: #d4edda;
    color: #155724;
  }

  .action-btn {
    background: #ffd23f;
    padding: 5px 8px;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    display: inline-block;
  }

  .action-btn i {
    color: #333;
  }

  .tab-content {
    display: none;
  }

  .tab-content.active {
    display: block;
  }
</style>
<style>
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
  .add-btn, .submit-btn {
    background: #28a745;
    color: white;
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
  }
  </style>
  



<header>
  <h2>📦 Lot Inventory</h2>
  <button class="add-btn" onclick="openLotForm()">+ Add New Lot</button>
</header>

<!-- Modal -->
<div id="lotFormModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeLotForm()">&times;</span>
    
    <form id="lotForm" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="warehouse_id" value="<?php echo e($warehouses->id); ?>">
      <input type="hidden" name="item" id="selectedItemName"> <!-- Dynamically set -->
    
      <h3 style="text-align: center;">➕ Add Lot</h3>
      <div class="row">
    
        <!-- Lot Number -->
        <div class="col-md-6 mb-3">
          <label>Lot Number</label>
          <input type="text" name="lot_number" class="form-control" required>
        </div>
    
        <!-- Product -->
        <div class="col-md-6 mb-3">
          <label>Product</label>
          <select name="product_id" class="form-control" id="productSelect" required>
            <option value="">Select Product</option>
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($product->id); ?>" data-name="<?php echo e($product->full_name); ?>">
                <?php echo e($product->full_name); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
    
        <!-- Labour Rate -->
        <div class="col-md-6 mb-3">
          <label>Labour Rate</label>
          <input type="number" step="0.01" name="labour_rate" class="form-control" required>
        </div>
    
        <!-- Rent -->
        <div class="col-md-6 mb-3">
          <label>Rent</label>
          <input type="number" step="0.01" name="rent" class="form-control" required>
        </div>
    
        <!-- Quantity -->
        <div class="col-md-6 mb-3">
          <label>Quantity</label>
          <input type="number" name="qty" class="form-control" required>
        </div>
    
        <!-- Date -->
        <div class="col-md-6 mb-3">
          <label>Date</label>
          <input type="date" name="date" class="form-control" required>
        </div>
    
        <!-- Each Bag Weight -->
        <div class="col-md-6 mb-3">
          <label>Each Bag Weight (optional)</label>
          <input type="number" step="0.01" name="each_bag_weight" class="form-control">
        </div>
    
        <!-- Media Upload -->
        <div class="col-md-6 mb-3">
          <label>Media (Images/Videos)</label>
          <input type="file" name="media[]" class="form-control" multiple>
        </div>
    
        <!-- Optional: Media Preview (You can remove if not needed) -->
        <div class="col-md-12 mb-3" id="mediaPreview"></div>
    
        <!-- Quality Description -->
        <div class="col-md-12 mb-3">
          <label>Quality Description</label>
          <textarea name="quality_description" class="form-control" rows="2"></textarea>
        </div>
    
        <!-- Packaging Remark -->
        <div class="col-md-12 mb-3">
          <label>Packaging Remark</label>
          <input type="text" name="packaging_remark" class="form-control">
        </div>
    
        <!-- Submit Button -->
        <div class="col-md-12">
          <button type="submit" class="btn btn-success w-100">💾 Save Lot</button>
        </div>
    
      </div>
    </form>    
  </div>
</div>


<div class="container">
  <!-- Tabs -->
  <div class="tabs">
    <button class="tab-button active" data-target="active-lots">Active Lots</button>
    <button class="tab-button" data-target="completed-lots">Completed Lots</button>
  </div>

  <!-- Active Lots -->
  <div class="tab-content active" id="active-lots">
      <!-- Search -->
  <div class="search-add">
    <input type="text" placeholder="Search Lots (Lot #, Item)...">
    
  </div>
    <table>
      <thead>
        <tr>
          <th>Lot #</th>
          <th>Item</th>
          <th>Qty (Initial Bags)</th>
          <th>Balance (Bags)</th>
          <th>Quality Description</th>
          <th>Date Added</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $activelots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr onclick="window.location='<?php echo e(url('admin/lots/details/active/list/' . $value->id)); ?>'" style="cursor:pointer;">
            <td><?php echo e($value->lot_number); ?></td>
            <td><?php echo e($value->item); ?></td>
            <td><?php echo e($value->quantity_bags); ?></td>
            <td><?php echo e($value->remaining_quantity_bags_after_deduction); ?></td>
            <td><?php echo e($value->quality_description); ?></td>
            <td><?php echo e(date('Y-m-d', strtotime($value->date))); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <b><td colspan="6" style="text-align: center;">Total Lot :- <?php echo e(count($activelots)); ?><br>Total Quality of Bags :- <?php echo e($activelots->sum('quantity_bags')); ?><br>Total Balance Bags :- <?php echo e($activelots->sum('remaining_quantity_bags_after_deduction')); ?></td></b>
      </tr>
      </tbody>
    </table>
  </div>

  <!-- Completed Lots -->
  <div class="tab-content" id="completed-lots">
      <!-- Search -->
  <div class="search-add">
    <input type="text" placeholder="Search Lots (Lot #, Item)...">
    
  </div>
    <table>
      <thead>
        <tr>
          <th>Lot #</th>
          <th>Item</th>
          <th>Qty (Initial Bags)</th>
          <th>Balance (Bags)</th>
          <th>Quality Description</th>
          <th>Date Added</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $completelots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr onclick="window.location='<?php echo e(url('admin/lots/details/complete/list/' . $value->id)); ?>'" style="cursor:pointer;">
                <td><?php echo e($value->lot_number); ?></td>
                <td><?php echo e($value->item); ?></td>
                <td><?php echo e($value->quantity_bags); ?></td>
                <td><?php echo e($value->remaining_quantity_bags_after_deduction); ?></td>
                <td><?php echo e($value->quality_description); ?></td>
                <td><?php echo e(date('Y-m-d', strtotime($value->date))); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <b><td colspan="6" style="text-align: center;">Total Lot :- <?php echo e(count($completelots)); ?><br>Total Quality of Bags :- <?php echo e($completelots->sum('quantity_bags')); ?><br>Total Balance Bags :- <?php echo e($completelots->sum('remaining_quantity_bags_after_deduction')); ?></td></b>
        </tr>
    </tbody>    
    </table>
  </div>
</div>

<script>
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabContents = document.querySelectorAll('.tab-content');

  tabButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      tabButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      tabContents.forEach(tab => tab.classList.remove('active'));
      document.getElementById(btn.dataset.target).classList.add('active');

      const targetTable = document.querySelector(`#${btn.dataset.target} tbody`);
      document.getElementById('lot-count').textContent = `${targetTable.rows.length} lots`;
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
  </script>

<script>
  document.getElementById('lotForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("<?php echo e(route('create.lot')); ?>", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
        'Accept': 'application/json' // ✅ This tells Laravel to return JSON, not redirect!
      },
      body: formData
    })
    .then(response => {
      if (!response.ok) {
        // Try to parse JSON error
        return response.json().then(err => {
          throw err;
        });
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        setTimeout(function() {
            location.reload();
        }, 1000);
        alert('✅ Lot added successfully!');
        closeLotForm();
        // Optionally: refresh the lot list or clear form
      } else {
        alert('❌ Failed to save. Please check input.');
      }
    })
    .catch(error => {
      console.error('❌ Validation error:', error);
      let messages = '';
      if (error.errors) {
        messages = Object.values(error.errors).map(msg => `• ${msg}`).join('\n');
      } else {
        messages = error.message || 'Something went wrong.';
      }
      alert(messages);
    });
  });
</script>
  
<script>
  // Set hidden input value based on selected product
  document.getElementById('productSelect').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    const fullName = selectedOption.getAttribute('data-name') || '';
    document.getElementById('selectedItemName').value = fullName;
  });
</script>
<script>
  // Set selected product full name into hidden input
  document.getElementById('productSelect').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('selectedItemName').value = selectedOption.getAttribute('data-name');
  });

  // Media file preview (optional)
  document.querySelector('input[name="media[]"]').addEventListener('change', function () {
    const preview = document.getElementById('mediaPreview');
    preview.innerHTML = '';
    Array.from(this.files).forEach(file => {
      const reader = new FileReader();
      reader.onload = function (e) {
        if (file.type.startsWith('image/')) {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.style.height = '100px';
          img.style.marginRight = '10px';
          preview.appendChild(img);
        } else if (file.type.startsWith('video/')) {
          const video = document.createElement('video');
          video.src = e.target.result;
          video.controls = true;
          video.style.height = '100px';
          video.style.marginRight = '10px';
          preview.appendChild(video);
        }
      };
      reader.readAsDataURL(file);
    });
  });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\ktc\resources\views/admin/pages/lots/list.blade.php ENDPATH**/ ?>