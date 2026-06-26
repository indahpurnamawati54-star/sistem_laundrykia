@extends('layouts.app')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Form Tambah User</h5>
        </div>
        <div class="card-body">
          <form role="form" method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            
            <div class="row">
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Nomor Telepon</label>
                  <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}">
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Role</label>
                  <select class="form-control" name="role" required>
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    <option value="pelanggan" {{ old('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                  </select>
                </div>
              </div>
            </div>
            
            <div class="input-group input-group-outline my-3">
              <label class="form-label">Alamat</label>
              <textarea class="form-control" name="address" rows="3">{{ old('address') }}</textarea>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Password</label>
                  <input type="password" class="form-control" name="password" required>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Konfirmasi Password</label>
                  <input type="password" class="form-control" name="password_confirmation" required>
                </div>
              </div>
            </div>
            
            <div class="text-center">
              <button type="submit" class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0">Simpan</button>
              <a href="{{ route('admin.users.index') }}" class="btn btn-lg bg-gradient-secondary btn-lg w-100 mt-2 mb-0">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection