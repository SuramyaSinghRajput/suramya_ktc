@extends('admin.layout.default')

@section('lots', 'active menu-item-open')
@section('content')

{{-- Font Awesome & Bootstrap --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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

  tr:hover {
    background-color: #f5faff;
  }
</style>

<div class="header">
  <h2>📦 {{ $product->full_name }}</h2>
</div>

<div class="filter-bar">
  <label for="warehouseFilter"><strong>Filter by Warehouse:</strong></label>
  <select id="warehouseFilter" data-product-id="{{ $product->id }}">
    <option value="">All Warehouses</option>
    @foreach($warehouses as $warehouse)
      <option value="{{ $warehouse->id }}">{{ $warehouse->store }}</option>
    @endforeach
  </select>
</div>

<table id="lotTable" class="sortable-table" data-default-sort-index="1" data-default-sort-order="asc">
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
    @forelse($lots as $index => $lot)
    @php
      $link = $lot->status == 1
        ? url('admin/lots/details/active/list/' . $lot->id)
        : url('admin/lots/details/complete/list/' . $lot->id);
      $imageFile = App\Models\LotsMedia::where('lot_id', $lot->id)->first();
    @endphp
    <tr onclick="window.location='{{ $link }}'" style="cursor: pointer;">
      <td>{{ $index + 1 }}</td>
      <td>{{ $lot->warehouse->store ?? 'N/A' }}</td>
      <td>{{ $lot->lot_number }}</td>
      <td>{{ $lot->quantity_bags }}</td>
      <td>{{ $lot->remaining_quantity_bags_after_deduction }}</td>
      <td>{{ $lot->quality_description ?? '—' }}</td>
      <td>
        @if($imageFile)
          <img src="{{ url('/storage/app/public/' . $imageFile->media_URL) }}" class="thumbnail" alt="Lot Image" onclick="zoomImage(event, '{{ url('/storage/app/public/' . $imageFile->media_URL) }}')">
        @else
          <span>No Image</span>
        @endif
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="7" class="empty-message">No lots found.</td>
    </tr>
    @endforelse
  </tbody>
  <tfoot>
    <tr>
      <td colspan="3" class="text-end"><strong>Total:</strong></td>
      <td><strong>{{ $lots->sum('quantity_bags') }}</strong></td>
      <td><strong>{{ $lots->sum('remaining_quantity_bags_after_deduction') }}</strong></td>
      <td colspan="2"></td>
    </tr>
  </tfoot>
</table>
<!-- Image Zoom Modal -->
<div class="modal fade" id="imageZoomModal" tabindex="-1" aria-labelledby="imageZoomModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="zoomedImage" src="" alt="Zoomed Lot Image" class="img-fluid rounded">
        <div class="mt-3">
          <button class="btn btn-primary me-2" id="shareImageBtn">
            <i class="fas fa-share-alt"></i> Share
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const baseUrl = "{{ url('/admin/warehouse/filter') }}";
  const baseDetailUrl = "{{ url('/admin/lots/details') }}";
  const url = "{{url('/')}}";

  document.getElementById('warehouseFilter').addEventListener('change', function () {
    let warehouseId = this.value || 0;
    const productId = this.getAttribute('data-product-id');

    fetch(`${baseUrl}/${warehouseId}/${productId}`)
      .then(res => res.json())
      .then(data => {
        const tbody = document.getElementById('lotTableBody');
        const tfoot = document.querySelector('tfoot');
        tbody.innerHTML = '';

        if (!Array.isArray(data.lots) || data.lots.length === 0) {
          tbody.innerHTML = `<tr><td colspan="7" class="empty-message">No lots found for selected warehouse.</td></tr>`;
          tfoot.innerHTML = '';
          return;
        }

        let serial = 1;
        let totalQty = 0;
        let totalBalanceQty = 0;

        data.lots.forEach(lot => {
          const permissionLot = {{ $permission['lots'] ?? 0 }};
          const link = lot.status == 1
            ? `${baseDetailUrl}/active/list/${lot.id}`
            : `${baseDetailUrl}/complete/list/${lot.id}`;

          totalQty += parseInt(lot.quantity_bags);
          totalBalanceQty += parseInt(lot.remaining_quantity_bags_after_deduction);

          let imgHTML = '<span>No Image</span>';
          if (lot.media && lot.media.length > 0) {
            const img = lot.media.find(file => /\.(jpg|jpeg|png|gif|webp)$/i.test(file.filename));
            if (img) {
              imgHTML = `<img src="${url}/storage/app/public/${img.filename}" class="thumbnail" alt="Lot Image" onclick="zoomImage(event, '${url}/storage/app/public/${img.filename}')">`;
            }
          }

          tbody.innerHTML += `
            <tr ${permissionLot > 0 ? `onclick="window.location='${link}'" style="cursor:pointer;"` : ''}>
              <td>${serial++}</td>
              <td>${lot.warehouse_name}</td>
              <td>${lot.lot_number}</td>
              <td>${lot.quantity_bags}</td>
              <td>${lot.remaining_quantity_bags_after_deduction}</td>
              <td>${lot.quality_description || '—'}</td>
              <td>${imgHTML}</td>
            </tr>
          `;
        });

        tfoot.innerHTML = `
          <tr>
            <td colspan="3" class="text-end"><strong>Total:</strong></td>
            <td><strong>${totalQty}</strong></td>
            <td><strong>${totalBalanceQty}</strong></td>
            <td colspan="2"></td>
          </tr>
        `;
      })
      .catch(err => {
        const tbody = document.getElementById('lotTableBody');
        tbody.innerHTML = `<tr><td colspan="7" class="empty-message">Error loading lots.</td></tr>`;
        console.error(err);
      });
  });
</script>

<script>
  function zoomImage(event, imageUrl) {
    event.stopPropagation(); // prevent triggering row click
    const zoomedImage = document.getElementById('zoomedImage');
    zoomedImage.src = imageUrl;
    zoomedImage.setAttribute('data-url', imageUrl);

    const zoomModal = new bootstrap.Modal(document.getElementById('imageZoomModal'));
    zoomModal.show();
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
      // Fallback: copy to clipboard
      navigator.clipboard.writeText(imageUrl)
        .then(() => alert('Image URL copied to clipboard!'))
        .catch(err => console.error('Failed to copy URL:', err));
    }
  });
</script>


@endsection
