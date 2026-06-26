@extends('layouts.app')

@section('title', 'Tambah Layanan')
@section('page-title', 'Tambah Layanan Baru')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Form Tambah Layanan</h5>
        </div>
        <div class="card-body">
          <form role="form" method="POST" action="{{ route('admin.services.store') }}">
            @csrf
            
            <div class="input-group input-group-outline my-3">
              <label class="form-label">Nama Layanan</label>
              <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Tipe Layanan</label>
                  <select class="form-control" name="type" id="service_type" required>
                    <option value="">Pilih Tipe</option>
                    <option value="kiloan" {{ old('type') == 'kiloan' ? 'selected' : '' }}>Kiloan</option>
                    <option value="satuan" {{ old('type') == 'satuan' ? 'selected' : '' }}>Satuan</option>
                    <option value="ekspres" {{ old('type') == 'ekspres' ? 'selected' : '' }}>Ekspres</option>
                  </select>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Estimasi Waktu (jam)</label>
                  <input type="number" class="form-control" name="estimated_hours" value="{{ old('estimated_hours') }}" min="1" required>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3" id="weight_field" style="display: none;">
                  <label class="form-label">Harga per Kg</label>
                  <input type="number" class="form-control" name="price_per_kg" value="{{ old('price_per_kg') }}" min="0">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3" id="quantity_field" style="display: none;">
                  <label class="form-label">Harga per Item</label>
                  <input type="number" class="form-control" name="price_per_item" value="{{ old('price_per_item') }}" min="0">
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Diskon (%)</label>
                  <input type="number" class="form-control" name="discount" value="{{ old('discount', 0) }}" min="0" max="100" step="0.1">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <div class="form-check form-switch mt-4">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                    <label class="form-check-label" for="is_active">Aktif</label>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="input-group input-group-outline my-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
            </div>
            
            <div class="text-center">
              <button type="submit" class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0">Simpan</button>
              <a href="{{ route('admin.services.index') }}" class="btn btn-lg bg-gradient-secondary btn-lg w-100 mt-2 mb-0">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection