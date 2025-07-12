<?php $__env->startSection('lots', 'active menu-item-open'); ?>
<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6fa;
  }

  .header {
    background: #fff;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .filter-bar {
    background: #fff;
    padding: 15px 20px;
    margin-bottom: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .filter-bar select {
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
  }

  table {
    width: 100%;
    background: #fff;
    border-collapse: collapse;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  }

  th, td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: left;
  }

  th {
    background: #f0f4f8;
    font-weight: 600;
  }

  .thumbnail {
    height: 50px;
    width: auto;
    border-radius: 6px;
    object-fit: cover;
  }

  .empty-message {
    padding: 20px;
    text-align: center;
    color: #888;
  }
</style>

<div class="header">
  <h2>📦 Lot Inventory</h2>
</div>

<div class="filter-bar">
  <label for="productFilter"><strong>Filter by Product:</strong></label>
  <select id="productFilter">
    <option value="">All Products</option>
    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e($product->id); ?>"><?php echo e($product->full_name); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </select>
</div>

<table id="lotTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Warehouse</th>
      <th>Lot #</th>
      <th>Qty</th>
      <th>Balance Qty</th>
      <th>Quality</th>
      <th>Image</th>
    </tr>
  </thead>
  <tbody id="lotTableBody">
    <?php $__empty_1 = true; $__currentLoopData = $lots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $lot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <tr>
        <td><?php echo e($index + 1); ?></td>
        <td><?php echo e($lot->warehouse->store ?? 'N/A'); ?></td>
        <td><?php echo e($lot->lot_number); ?></td>
        <td><?php echo e($lot->quantity_bags); ?></td>
        <td><?php echo e($lot->remaining_quantity_bags_after_deduction); ?></td>
        <td><?php echo e($lot->quality_description); ?></td>
        <td>
          <?php
            $imageFile = $lot->media->first(function ($m) {
              return in_array(strtolower(pathinfo($m->filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
            });
          ?>
          <?php if($imageFile): ?>
            <img src="<?php echo e(asset('storage/lots/' . $imageFile->filename)); ?>" class="thumbnail">
          <?php else: ?>
            <span>No Image</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <tr>
        <td colspan="7" class="empty-message">No lots found.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<script>
  document.getElementById('productFilter').addEventListener('change', function () {
    const productId = this.value;

    fetch(`<?php echo e(url('admin/lots/filter')); ?>/${productId}`)
      .then(res => res.json())
      .then(data => {
        const tbody = document.getElementById('lotTableBody');
        tbody.innerHTML = '';

        if (!Array.isArray(data) || data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="7" class="empty-message">No lots found for selected product.</td></tr>`;
          return;
        }

        let serial = 1;
        data.forEach(lot => {
          let imgHTML = '<span>No Image</span>';
          if (lot.media && lot.media.length > 0) {
            const img = lot.media.find(file => /\.(jpg|jpeg|png|gif|webp)$/i.test(file.filename));
            if (img) {
              imgHTML = `<img src="/storage/lots/${img.filename}" class="thumbnail">`;
            }
          }

          tbody.innerHTML += `
            <tr>
              <td>${serial++}</td>
              <td>${lot.warehouse_name || 'N/A'}</td>
              <td>${lot.lot_number}</td>
              <td>${lot.quantity_bags}</td>
              <td>${lot.remaining_quantity_bags_after_deduction}</td>
              <td>${lot.quality_description || ''}</td>
              <td>${imgHTML}</td>
            </tr>
          `;
        });
      })
      .catch(err => {
        alert("Failed to fetch filtered data.");
        console.error(err);
      });
  });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\ktc\resources\views/admin/pages/product/product-lot.blade.php ENDPATH**/ ?>