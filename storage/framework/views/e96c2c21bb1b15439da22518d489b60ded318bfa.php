

<?php $__env->startSection('lots', 'active menu-item-open'); ?>
<?php $__env->startSection('content'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lot Details - L1006</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f1f5fb;
      margin: 0;
      padding: 20px;
    }

    .card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      padding: 20px;
      margin-bottom: 20px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .header h2 {
      margin: 0;
      color: #003366;
    }

    .btn {
      padding: 8px 16px;
      border-radius: 5px;
      border: none;
      font-weight: bold;
      cursor: pointer;
    }

    .btn-edit {
      background: #e7f1ff;
      color: #004085;
      margin-right: 10px;
    }

    .btn-back {
      background: #f0c93d;
      color: #333;
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

    .row {
      display: flex;
      flex-wrap: wrap;
      margin: 0 -10px;
    }

    .col-6 {
      flex: 0 0 50%;
      padding: 10px;
    }

    .info-block {
      margin-bottom: 15px;
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

    .history-entry {
      margin-bottom: 10px;
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
    }
  </style>
</head>
<body>

  <div class="card">
    <div class="header">
      <h2>Lot Details - L1006</h2>
      <div>
        <button class="btn btn-edit"><i class="fas fa-edit"></i> Edit</button>
        <button class="btn btn-back"><i class="fas fa-arrow-left"></i> Back to List</button>
      </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
      <button class="active"><i class="fas fa-info-circle"></i> Basic Information</button>
      <button><i class="fas fa-list"></i> Deduct Quantity List</button>
    </div>

    <!-- Main Details -->
    <div class="row">
      <div class="col-6 info-block">
        <strong>Lot Number</strong> L1006
      </div>
      <div class="col-6 info-block">
        <strong>Item</strong> Gotu Kola
      </div>
      <div class="col-6 info-block">
        <strong>Initial Quantity (Bags)</strong> 300
      </div>
      <div class="col-6 info-block">
        <strong>Current Balance (Bags)</strong> 300
      </div>
      <div class="col-6 info-block">
        <strong>Date Added/Completed</strong> 2024-05-05
      </div>
      <div class="col-6 info-block">
        <strong>Purchase Price/Bag</strong> ₹300
      </div>
      <div class="col-6 info-block">
        <strong>Quality</strong> Grade A
      </div>
      <div class="col-6 info-block">
        <strong>Status</strong> <span class="status-badge">Active</span>
      </div>
    </div>

    <!-- Expenses -->
    <div class="section-title">Expenses</div>
    <div class="row">
      <div class="col-6 info-block"><strong>Total Purchase Price</strong> ₹90,000</div>
      <div class="col-6 info-block"><strong>Freight</strong> ₹2,500</div>
      <div class="col-6 info-block"><strong>Brokerage</strong> ₹1,000</div>
      <div class="col-6 info-block"><strong>Labour Amount</strong> ₹5,400</div>
      <div class="col-6 info-block"><strong>Rent Amount</strong> ₹400</div>
      <div class="col-6 info-block"><strong>Total Cost</strong> ₹99,300</div>
      <div class="col-6 info-block"><strong>Labour Rate</strong> ₹18/Bag</div>
      <div class="col-6 info-block"><strong>Cost Per Bag</strong> ₹331</div>
    </div>

    <!-- Notes -->
    <div class="section-title">Notes/Description</div>
    <div class="description">
      Organic certified.
    </div>

    <!-- Lot Images -->
    <div class="section-title">Lot Images</div>
    <div class="images no-image">
      No images uploaded for this lot.
    </div>

    <!-- Transaction History -->
    <div class="section-title">Transaction History</div>
    <div class="history">
      <div class="history-timeline">
        <div class="history-entry">
          <span>2024-05-05 16:00 PM</span>
          <strong>Lot Created:</strong> Received 300 Bags.
        </div>
      </div>
    </div>

    <!-- Mark as Complete -->
    <button class="complete-btn"><i class="fas fa-check-circle"></i> Mark Lot as Complete</button>
  </div>

</body>
</html>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\ktc\resources\views/admin/pages/lots/lotdetails.blade.php ENDPATH**/ ?>