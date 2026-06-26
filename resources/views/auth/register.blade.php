@extends('layouts.app')

@section('title', 'Register - Sistem Laundry')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1 text-center">
            <h4 class="text-white font-weight-bolder my-0">Daftar Akun Baru</h4>
            <p class="mb-0 text-sm text-white">Buat akun pelanggan baru</p>
          </div>
        </div>
        <div class="card-body">
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form role="form" method="POST" action="{{ route('register') }}">
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
                  <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}" required>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Password</label>
                  <input type="password" class="form-control" name="password" required>
                </div>
              </div>
            </div>
            
            <div class="input-group input-group-outline my-3">
              <label class="form-label">Alamat</label>
              <textarea class="form-control" name="address" rows="2" required>{{ old('address') }}</textarea>
            </div>
            
            <div class="input-group input-group-outline my-3">
              <label class="form-label">Konfirmasi Password</label>
              <input type="password" class="form-control" name="password_confirmation" required>
            </div>
            
            <div class="text-center">
              <button type="submit" class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0">Daftar</button>
            </div>
          </form>
        </div>
        <div class="card-footer text-center pt-0 px-lg-2 px-1">
          <p class="mb-4 text-sm mx-auto">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-dark text-gradient font-weight-bold">Login</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection