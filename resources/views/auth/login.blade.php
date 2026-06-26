@extends('layouts.app')

@section('title', 'Login - Sistem Laundry')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-4 mx-auto">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1 text-center">
            <h4 class="text-white font-weight-bolder my-0">Login Sistem</h4>
            <p class="mb-0 text-sm text-white">Masuk ke akun Anda</p>
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

          <form role="form" method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="input-group input-group-outline my-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            </div>
            
            <div class="input-group input-group-outline my-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" name="password" required>
            </div>
            
            <div class="form-check form-switch d-flex align-items-center mb-3">
              <input class="form-check-input" type="checkbox" id="remember" name="remember">
              <label class="form-check-label mb-0 ms-2" for="remember">Ingat saya</label>
            </div>
            
            <div class="text-center">
              <button type="submit" class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0">Login</button>
            </div>
          </form>
        </div>
        <div class="card-footer text-center pt-0 px-lg-2 px-1">
          <p class="mb-4 text-sm mx-auto">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-dark text-gradient font-weight-bold">Daftar</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection