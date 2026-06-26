@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="card">
        <div class="card-header pb-0">
          <h5>Edit Profil</h5>
        </div>
        <div class="card-body">
          <!-- Form Edit Profil -->
          <form role="form" method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')
            
            <div class="row">
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" required>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" required>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Nomor Telepon</label>
                  <input type="tel" class="form-control" name="phone" value="{{ auth()->user()->phone }}">
                </div>
              </div>
            </div>
            
            <div class="input-group input-group-outline my-3">
              <label class="form-label">Alamat</label>
              <textarea class="form-control" name="address" rows="3">{{ auth()->user()->address }}</textarea>
            </div>
            
            <div class="text-center">
              <button type="submit" class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0">Update Profil</button>
            </div>
          </form>
          
          <hr class="horizontal dark my-4">
          
          <!-- Form Ganti Password -->
          <h6 class="mb-3">Ganti Password</h6>
          <form role="form" method="POST" action="{{ route('profile.password.update') }}">
            @csrf
            
            <div class="input-group input-group-outline my-3">
              <label class="form-label">Password Saat Ini</label>
              <input type="password" class="form-control" name="current_password" required>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Password Baru</label>
                  <input type="password" class="form-control" name="password" required>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Konfirmasi Password Baru</label>
                  <input type="password" class="form-control" name="password_confirmation" required>
                </div>
              </div>
            </div>
            
            <div class="text-center">
              <button type="submit" class="btn btn-lg bg-gradient-info btn-lg w-100 mt-4 mb-0">Ganti Password</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection