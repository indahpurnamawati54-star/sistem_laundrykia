<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>
    @yield('title', 'Sistem Laundry')
  </title>
  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet" />
  @stack('styles')
</head>

<body class="g-sidenav-show  bg-gray-100">
  @auth
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="{{ route('dashboard') }}">
        <i class="material-symbols-rounded text-dark opacity-10">local_laundry_service</i>
        <span class="ms-1 text-sm text-dark font-weight-bold">Laundry System</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('dashboard') }}">
            <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-symbols-rounded opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        
        @if(auth()->user()->role === 'admin')
          <!-- Admin Menu -->
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.users.index') }}">
              <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded opacity-10">people</i>
              </div>
              <span class="nav-link-text ms-1">Manajemen User</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.services.index') }}">
              <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded opacity-10">list_alt</i>
              </div>
              <span class="nav-link-text ms-1">Layanan Laundry</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.transactions.index') }}">
              <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded opacity-10">receipt_long</i>
              </div>
              <span class="nav-link-text ms-1">Data Transaksi</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.reports.index') }}">
              <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded opacity-10">analytics</i>
              </div>
              <span class="nav-link-text ms-1">Laporan</span>
            </a>
          </li>
        @elseif(auth()->user()->role === 'kasir')
          <!-- Kasir Menu -->
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kasir.transactions.create') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('kasir.transactions.create') }}">
              <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded opacity-10">add</i>
              </div>
              <span class="nav-link-text ms-1">Buat Transaksi</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kasir.transactions.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('kasir.transactions.index') }}">
              <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded opacity-10">list_alt</i>
              </div>
              <span class="nav-link-text ms-1">Data Transaksi</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kasir.customers.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('kasir.customers.index') }}">
              <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded opacity-10">person</i>
              </div>
              <span class="nav-link-text ms-1">Data Pelanggan</span>
            </a>
          </li>
        @else
          <!-- Pelanggan Menu -->
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pelanggan.transactions.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('pelanggan.transactions.index') }}">
              <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded opacity-10">local_laundry_service</i>
              </div>
              <span class="nav-link-text ms-1">Status Laundry</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pelanggan.history.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('pelanggan.history.index') }}">
              <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded opacity-10">history</i>
              </div>
              <span class="nav-link-text ms-1">Riwayat Transaksi</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pelanggan.tracking.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('pelanggan.tracking.index') }}">
              <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded opacity-10">track_changes</i>
              </div>
              <span class="nav-link-text ms-1">Tracking Laundry</span>
            </a>
          </li>
        @endif

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('profile.edit') }}">
            <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-symbols-rounded opacity-10">person</i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-outline-danger mt-4 w-100">
            <i class="material-symbols-rounded opacity-10">logout</i>
            Logout
          </button>
        </form>
      </div>
    </div>
  </aside>
  @endauth

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @auth
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">@yield('page-title', 'Dashboard')</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">@yield('page-title', 'Dashboard')</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">Search here...</label>
              <input type="text" class="form-control">
            </div>
          </div>
          <ul class="navbar-nav d-flex align-items-center  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <span class="badge bg-gradient-{{ auth()->user()->role === 'admin' ? 'danger' : (auth()->user()->role === 'kasir' ? 'warning' : 'success') }}">
                {{ ucfirst(auth()->user()->role) }}
              </span>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            <li class="nav-item dropdown pe-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="material-symbols-rounded cursor-pointer">notifications</i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="avatar avatar-sm bg-gradient-secondary me-3 my-auto">
                        <i class="material-symbols-rounded">local_laundry_service</i>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">Laundry selesai</span>
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          13 minutes ago
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    @endauth
    <!-- End Navbar -->

    <div class="container-fluid py-3">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
          <i class="material-symbols-rounded me-2">check_circle</i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
          <i class="material-symbols-rounded me-2">error</i>
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @yield('content')
    </div>

    <footer class="footer py-3">
      <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="copyright text-center text-sm text-muted text-lg-start">
              © <script>document.write(new Date().getFullYear())</script>,
              Sistem Laundry
            </div>
          </div>
        </div>
      </div>
    </footer>
  </main>

  <!-- Core JS Files -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
  
  @stack('scripts')
  
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  
  <!-- Control Center for Material Dashboard -->
  <script src="{{ asset('assets/js/material-dashboard.min.js?v=3.2.0') }}"></script>

  <script>
    // Dynamic form based on service type
    document.addEventListener('DOMContentLoaded', function() {
      const serviceTypeSelect = document.getElementById('service_type');
      const weightField = document.getElementById('weight_field');
      const quantityField = document.getElementById('quantity_field');

      if (serviceTypeSelect) {
        function toggleFields() {
          const selectedType = serviceTypeSelect.value;
          
          if (selectedType === 'kiloan') {
            weightField.style.display = 'block';
            quantityField.style.display = 'none';
          } else {
            weightField.style.display = 'none';
            quantityField.style.display = 'block';
          }
        }

        serviceTypeSelect.addEventListener('change', toggleFields);
        toggleFields(); // Initial call
      }

      // Auto calculate price
      const priceInput = document.getElementById('price');
      const discountInput = document.getElementById('discount');
      const totalAmountSpan = document.getElementById('total_amount');

      if (priceInput && discountInput && totalAmountSpan) {
        function calculateTotal() {
          const price = parseFloat(priceInput.value) || 0;
          const discount = parseFloat(discountInput.value) || 0;
          const discountAmount = price * (discount / 100);
          const total = price - discountAmount;
          totalAmountSpan.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        priceInput.addEventListener('input', calculateTotal);
        discountInput.addEventListener('input', calculateTotal);
        calculateTotal(); // Initial calculation
      }
    });
  </script>
</body>

</html>