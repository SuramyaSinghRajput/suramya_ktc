@extends('admin.layout.default')

@section('lots', 'active menu-item-open')
@section('content')

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
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }

  th,
  td {
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

  .add-btn,
  .submit-btn {
    background: #28a745;
    color: white;
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
  }

  #mediaPreview img {
    width: 100px;
    height: 100px;
    object-fit: cover;
  }
</style>

<header>
  <h2>📦 Lot Inventory</h2>
  @if(in_array($permission['export'] ?? 0, [1,2,3,4,5]))
    <a href="{{route('lots.export.form')}}" class="add-btn">Export</a>
  @endif

  @if(in_array($permission['lots'] ?? 0, [3,4,5]))
  <button class="add-btn" onclick="openLotForm()">+ Add New Lot</button>
  @endif
</header>

<!-- Modal -->
<div id="lotFormModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeLotForm()">&times;</span>
    <form id="lotForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="warehouse_id" value="{{ $warehouses->id }}">
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
            @foreach($products as $product)
            <option value="{{ $product->id }}" data-name="{{ $product->name }}">
              {{ $product->name }}
            </option>
            @endforeach
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

        <div class="col-md-6 mb-3">
          <label>Media (Images/Videos)</label>
          <div id="media-dropzone" class="p-4 text-center position-relative" style="cursor:pointer;">
            <input type="file" id="mediaInput" accept="image/*,video/*" multiple hidden>
            <div class="d-flex flex-column align-items-center justify-content-center" style="height: 150px;">
              <i class="fa fa-camera" style="font-size: 2rem; color: #999;"></i>
              <span class="mt-2 text-muted">Add Photos/Videos</span>
            </div>
          </div>
        </div>
        <div id="mediaPreview" class="col-md-12 mb-3 d-flex flex-wrap gap-2 mt-3"></div>

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
      {{-- <span id="lot-count">{{count($activelots)}} lots</span> --}}
    </div>
    <table class="sortable-table" data-default-sort-index="1" data-default-sort-order="asc">
      <thead>
        <tr>
          <th>Lot #</th>
          <th>Item</th>
          <th>Qty (Initial Bags)</th>
          <th>Balance (Bags)</th>
          <th>Quality Description</th>
          <th>Date Added</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forEach($activelots as $value)
        <tr 
            @if(in_array($permission['lots'] ?? 0, [1, 2, 3, 4, 5]))
                onclick="window.location='{{ url('admin/lots/details/active/list/' . $value->id) }}'" 
                style="cursor:pointer;"
            @endif
        >
          <td>{{$value->lot_number}}</td>
          <td>{{$value->item}}</td>
          <td>{{$value->quantity_bags}}</td>
          <td>{{$value->remaining_quantity_bags_after_deduction}}</td>
          <td>{{$value->quality_description}}</td>
          <td>{{date('Y-m-d', strtotime($value->date))}}</td>
          <td>
            @if(in_array($permission['lots'] ?? 0, [1, 2, 3, 4, 5]))
              <button class="btn btn-sm btn-danger" onclick="event.stopPropagation(); openDeleteModal({{ $value->id }})">
                <i class="fa fa-trash"></i>
              </button>
            @endif

          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr style="font-weight: bold; background-color: #f9f9f9;">
          <td colspan="1" style="text-align: right;">Total:</td>
          <td>{{count($activelots)}}</td>
          <td>{{ $activelots->sum('quantity_bags') }}</td>
          <td>{{ $activelots->sum('remaining_quantity_bags_after_deduction') }}</td>
          <td></td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  </div>

  <!-- Completed Lots -->
  <div class="tab-content" id="completed-lots">
    <!-- Search -->
    <div class="search-add">
      <input type="text" placeholder="Search Lots (Lot #, Item)...">
      {{-- <span id="lot-count">{{count($completelots)}} lots</span> --}}
    </div>
    <table class="sortable-table" data-default-sort-index="2" data-default-sort-order="asc">
      <thead>
        <tr>
          <th>Lot #</th>
          <th>Item</th>
          <th>Qty (Initial Bags)</th>
          <th>Balance (Bags)</th>
          <th>Quality Description</th>
          <th>Date Added</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($completelots as $value)
        <tr 
            @if(in_array($permission['lots'] ?? 0, [1, 2, 3, 4, 5]))
                onclick="window.location='{{ url('admin/lots/details/complete/list/' . $value->id) }}'" 
                style="cursor:pointer;"
            @endif
        >
          <td>{{ $value->lot_number }}</td>
          <td>{{ $value->item }}</td>
          <td>{{ $value->quantity_bags }}</td>
          <td>{{$value->remaining_quantity_bags_after_deduction}}</td>
          <td>{{$value->quality_description}}</td>
          <td>{{ date('Y-m-d', strtotime($value->date)) }}</td>
          <td>
             @if(in_array($permission['lots'] ?? 0, [1, 2, 3, 4, 5]))
              <button class="btn btn-sm btn-danger" onclick="event.stopPropagation(); openDeleteModal({{ $value->id }})">
                <i class="fa fa-trash"></i>
              </button>
            @endif

          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr style="font-weight: bold; background-color: #f9f9f9;">
          <td colspan="1" style="text-align: right;">Total:</td>
          <td>{{count($completelots)}}</td>
          <td>{{ $completelots->sum('quantity_bags') }}</td>
          <td>{{ $completelots->sum('remaining_quantity_bags_after_deduction') }}</td>
          <td></td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
  <div class="modal-content" style="width: 400px;">
    <span class="close" onclick="closeDeleteModal()">&times;</span>
    <form id="deleteLotForm">
      @csrf
      @method('DELETE')
      <input type="hidden" name="lot_id" id="deleteLotId">

      <h4 style="text-align: center;">🔒 Confirm Delete</h4>
      <p>Enter your password to delete this lot:</p>
      <input type="password" name="password" class="form-control mb-3" required placeholder="Password">

      <button type="submit" class="btn btn-danger w-100">🗑️ Delete Lot</button>
    </form>
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
  function openDeleteModal(lotId) {
    document.getElementById('deleteLotId').value = lotId;
    document.getElementById('deleteModal').style.display = 'block';
  }

  function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
  }

  window.onclick = function(event) {
    const deleteModal = document.getElementById('deleteModal');
    if (event.target === deleteModal) {
      closeDeleteModal();
    }
  };

  document.getElementById('deleteLotForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const baseUrl = "{{ url('/') }}";
    const form = e.target;
    const lotId = document.getElementById('deleteLotId').value;
    const password = form.password.value;

    fetch(`${baseUrl}/admin/lots/delete/${lotId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          password: password
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('✅ Lot deleted!');
          closeDeleteModal();
          location.reload(); // ✅ This reloads the page
        }
        else {
          alert('❌ ' + (data.message || 'Deletion failed.'));
        }
      })
      .catch(error => {
        console.error('Delete error:', error);
        alert('❌ Something went wrong.');
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

    fetch("{{ route('create.lot') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
  document.getElementById('productSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const fullName = selectedOption.getAttribute('data-name') || '';
    document.getElementById('selectedItemName').value = fullName;
  });
</script>
<script>
  // Set selected product full name into hidden input
  document.getElementById('productSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('selectedItemName').value = selectedOption.getAttribute('data-name');
  });

  // Media file preview (optional)
  document.querySelector('input[name="media[]"]').addEventListener('change', function() {
    const preview = document.getElementById('mediaPreview');
    preview.innerHTML = '';
    Array.from(this.files).forEach(file => {
      const reader = new FileReader();
      reader.onload = function(e) {
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
<script>
  function openLotForm() {
    document.getElementById('lotFormModal').style.display = 'block';
  }

  function closeLotForm() {
    document.getElementById('lotFormModal').style.display = 'none';
  }

  window.onclick = function(event) {
    const modal = document.getElementById('lotFormModal');
    if (event.target === modal) {
      closeLotForm();
    }
  };

  document.getElementById('productSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('selectedItemName').value = selectedOption.getAttribute('data-name');
  });

  const dropzone = document.getElementById('media-dropzone');
  const mediaInput = document.getElementById('mediaInput');
  const preview = document.getElementById('mediaPreview');
  let mediaFiles = [];

  dropzone.addEventListener('click', () => mediaInput.click());

  mediaInput.addEventListener('change', function() {
    const files = Array.from(this.files);
    files.forEach(file => {
      if (!mediaFiles.find(f => f.name === file.name && f.size === file.size)) {
        mediaFiles.push(file);
      }
    });
    renderPreviews();
    this.value = '';
  });

  function renderPreviews() {
    preview.innerHTML = '';
    mediaFiles.forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = function(e) {
        let element = file.type.startsWith('image/') ? document.createElement('img') : document.createElement('video');
        element.src = e.target.result;
        if (element.tagName === 'VIDEO') element.controls = true;

        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';
        wrapper.style.display = 'inline-block';
        wrapper.style.marginRight = '10px';

        const removeBtn = document.createElement('span');
        removeBtn.innerHTML = '&times;';
        removeBtn.style.position = 'absolute';
        removeBtn.style.top = '-8px';
        removeBtn.style.right = '0';
        removeBtn.style.background = '#ff4d4d';
        removeBtn.style.color = 'white';
        removeBtn.style.fontSize = '14px';
        removeBtn.style.padding = '0 6px';
        removeBtn.style.borderRadius = '50%';
        removeBtn.style.cursor = 'pointer';
        removeBtn.title = 'Remove';

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

  document.getElementById('lotForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    mediaFiles.forEach((file, i) => {
      formData.append(`media[${i}]`, file);
    });

    fetch("{{ route('create.lot') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('✅ Lot saved!');
          closeLotForm();
          location.reload();
        } else {
          alert('❌ Error: ' + (data.error || 'Failed to save.'));
        }
      })
      .catch(err => console.error('Upload error:', err));
  });
</script>
@endsection