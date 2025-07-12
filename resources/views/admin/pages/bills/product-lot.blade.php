@extends('admin.layout.default')

@section('lots', 'active menu-item-open')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  body { font-family: 'Segoe UI', sans-serif; background: #f4f6fa; }
  .header { background: #fff; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
  .filter-bar { background: #fff; padding: 15px 20px; margin-bottom: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 15px; }
  .filter-bar select { padding: 8px; border-radius: 6px; border: 1px solid #ccc; font-size: 14px; }
  table { width: 100%; background: #fff; border-collapse: collapse; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
  th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
  th { background: #f0f4f8; font-weight: 600; }
  .thumbnail { height: 50px; width: auto; border-radius: 6px; object-fit: cover; }
  .empty-message { padding: 20px; text-align: center; color: #888; }
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
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $lot->warehouse->store ?? 'N/A' }}</td>
        <td>{{ $lot->lot_number }}</td>
        <td>{{ $lot->quantity_bags }}</td>
        <td>{{ $lot->remaining_quantity_bags_after_deduction }}</td>
        <td>{{ $lot->quality_description }}</td>
        <td>
          @php
            $imageFile = $lot->media->first(function ($m) {
              return in_array(strtolower(pathinfo($m->filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
            });
          @endphp
          @if($imageFile)
            <img src="{{ asset('storage/lots/' . $imageFile->filename) }}" class="thumbnail">
          @else
            <span>No Image</span>
          @endif
        </td>
      </tr>
    @empty
      <tr><td colspan="7" class="empty-message">No lots found.</td></tr>
    @endforelse
  </tbody>
</table>

<script>
  document.getElementById('warehouseFilter').addEventListener('change', function () {
  let warehouseId = this.value || 0; // 👈 fix: use 0 instead of empty string
  const productId = this.getAttribute('data-product-id');
  const baseUrl = @json(url('/admin/warehouse/filter'));

  fetch(`${baseUrl}/${warehouseId}/${productId}`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('lotTableBody');
      tbody.innerHTML = '';

      if (!Array.isArray(data) || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="empty-message">No lots found for selected warehouse.</td></tr>`;
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
            <td>${lot.warehouse_name}</td>
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
      const tbody = document.getElementById('lotTableBody');
      tbody.innerHTML = `<tr><td colspan="7" class="empty-message">Error loading lots.</td></tr>`;
      console.error(err);
    });
});

</script>

@endsection
