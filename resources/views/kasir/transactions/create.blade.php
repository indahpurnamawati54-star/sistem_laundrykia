@extends('layouts.app')

@section('title', 'Buat Transaksi')
@section('page-title', 'Buat Transaksi Baru')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Form Transaksi Baru</h5>
        </div>
        <div class="card-body">
          <form role="form" method="POST" action="{{ route('kasir.transactions.store') }}" id="transactionForm">
            @csrf
            
            <!-- Pilih Pelanggan -->
            <div class="mb-4">
              <h6 class="text-sm">Pilih Pelanggan</h6>
              <div class="input-group input-group-outline">
                <select class="form-control" name="customer_id" id="customer_id" required>
                  <option value="">Pilih Pelanggan</option>
                  @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                      {{ $customer->name }} - {{ $customer->phone }}
                    </option>
                  @endforeach
                </select>
                <a href="{{ route('kasir.customers.create') }}" class="input-group-text btn btn-sm bg-gradient-info">
                  <i class="material-symbols-rounded">person_add</i>
                </a>
              </div>
            </div>
            
            <!-- Pilih Layanan -->
            <div class="mb-4">
              <h6 class="text-sm">Pilih Layanan</h6>
              <div class="input-group input-group-outline">
                <select class="form-control" name="service_id" id="service_id" required onchange="calculatePrice()">
                  <option value="">Pilih Layanan</option>
                  @foreach($services as $service)
                    <option value="{{ $service->id }}" 
                            data-type="{{ $service->type }}"
                            data-price-per-kg="{{ $service->price_per_kg }}"
                            data-price-per-item="{{ $service->price_per_item }}"
                            data-discount="{{ $service->discount }}"
                            {{ old('service_id') == $service->id ? 'selected' : '' }}>
                      {{ $service->name }} ({{ $service->formatted_price }})
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            
            <!-- Input Berat/Jumlah -->
            <div class="row mb-4">
              <div class="col-md-6" id="weight_field" style="display: none;">
                <div class="input-group input-group-outline">
                  <label class="form-label">Berat (kg)</label>
                  <input type="number" class="form-control" name="weight" id="weight" 
                         step="0.1" min="0.1" oninput="calculatePrice()" 
                         value="{{ old('weight') }}">
                </div>
              </div>
              <div class="col-md-6" id="quantity_field" style="display: none;">
                <div class="input-group input-group-outline">
                  <label class="form-label">Jumlah Item</label>
                  <input type="number" class="form-control" name="quantity" id="quantity" 
                         min="1" oninput="calculatePrice()" 
                         value="{{ old('quantity', 1) }}">
                </div>
              </div>
            </div>
            
            <!-- Informasi Harga -->
            <div class="card mb-4" id="price_info" style="display: none;">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <p class="mb-1">Harga Satuan: <span id="unit_price">-</span></p>
                    <p class="mb-1">Diskon: <span id="discount_percentage">0%</span></p>
                    <p class="mb-1">Total Diskon: <span id="discount_amount">Rp 0</span></p>
                  </div>
                  <div class="col-md-6 text-end">
                    <h4 class="text-gradient text-dark">Total: <span id="total_amount">Rp 0</span></h4>
                    <p class="text-sm text-muted" id="estimation_time">Estimasi: -</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Catatan -->
            <div class="mb-4">
              <div class="input-group input-group-outline">
                <label class="form-label">Catatan (Opsional)</label>
                <textarea class="form-control" name="notes" rows="3">{{ old('notes') }}</textarea>
              </div>
            </div>
            
            <!-- Submit Button -->
            <div class="text-center">
              <button type="submit" class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0" id="submit_btn">
                Buat Transaksi
              </button>
              <a href="{{ route('dashboard') }}" class="btn btn-lg bg-gradient-secondary btn-lg w-100 mt-2 mb-0">
                Batal
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const weightField = document.getElementById('weight_field');
    const quantityField = document.getElementById('quantity_field');
    const priceInfo = document.getElementById('price_info');
    
    function updateFields() {
      const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
      const serviceType = selectedOption?.dataset?.type;
      
      if (serviceType === 'kiloan') {
        weightField.style.display = 'block';
        quantityField.style.display = 'none';
        priceInfo.style.display = 'block';
      } else if (serviceType === 'satuan' || serviceType === 'ekspres') {
        weightField.style.display = 'none';
        quantityField.style.display = 'block';
        priceInfo.style.display = 'block';
      } else {
        weightField.style.display = 'none';
        quantityField.style.display = 'none';
        priceInfo.style.display = 'none';
      }
      
      calculatePrice();
    }
    
    function calculatePrice() {
      const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
      if (!selectedOption?.dataset?.type) return;
      
      const serviceType = selectedOption.dataset.type;
      const pricePerKg = parseFloat(selectedOption.dataset.pricePerKg) || 0;
      const pricePerItem = parseFloat(selectedOption.dataset.pricePerItem) || 0;
      const discountPercentage = parseFloat(selectedOption.dataset.discount) || 0;
      
      let quantity = 0;
      let basePrice = 0;
      
      if (serviceType === 'kiloan') {
        const weight = parseFloat(document.getElementById('weight').value) || 0;
        quantity = weight;
        basePrice = pricePerKg * weight;
        document.getElementById('unit_price').textContent = 'Rp ' + pricePerKg.toLocaleString('id-ID') + '/kg';
      } else {
        const itemCount = parseInt(document.getElementById('quantity').value) || 1;
        quantity = itemCount;
        basePrice = pricePerItem * itemCount;
        document.getElementById('unit_price').textContent = 'Rp ' + pricePerItem.toLocaleString('id-ID') + '/item';
      }
      
      const discountAmount = basePrice * (discountPercentage / 100);
      const totalAmount = basePrice - discountAmount;
      
      document.getElementById('discount_percentage').textContent = discountPercentage + '%';
      document.getElementById('discount_amount').textContent = 'Rp ' + discountAmount.toLocaleString('id-ID');
      document.getElementById('total_amount').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
      
      // Update submit button text with total
      const submitBtn = document.getElementById('submit_btn');
      submitBtn.innerHTML = `Buat Transaksi (Rp ${totalAmount.toLocaleString('id-ID')})`;
    }
    
    serviceSelect.addEventListener('change', updateFields);
    document.getElementById('weight')?.addEventListener('input', calculatePrice);
    document.getElementById('quantity')?.addEventListener('input', calculatePrice);
    
    // Initial calculation
    updateFields();
    
    // Form validation
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
      const serviceId = document.getElementById('service_id').value;
      const customerId = document.getElementById('customer_id').value;
      const serviceType = serviceSelect.options[serviceSelect.selectedIndex]?.dataset?.type;
      
      if (!serviceId || !customerId) {
        e.preventDefault();
        alert('Harap pilih pelanggan dan layanan');
        return;
      }
      
      if (serviceType === 'kiloan') {
        const weight = parseFloat(document.getElementById('weight').value);
        if (!weight || weight < 0.1) {
          e.preventDefault();
          alert('Harap masukkan berat minimal 0.1 kg');
          return;
        }
      } else {
        const quantity = parseInt(document.getElementById('quantity').value);
        if (!quantity || quantity < 1) {
          e.preventDefault();
          alert('Harap masukkan jumlah minimal 1 item');
          return;
        }
      }
    });
  });
</script>
@endpush
@endsection