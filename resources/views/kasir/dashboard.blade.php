@extends('layouts.app')

@section('title', 'Kasir Dashboard')
@section('page-title', 'Dashboard Kasir')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <!-- Statistik Hari Ini -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Pemasukan Hari Ini</p>
                <h5 class="font-weight-bolder mb-0">
                  Rp {{ number_format($todayIncome, 0, ',', '.') }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                <i class="material-symbols-rounded text-lg opacity-10">payments</i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Transaksi Hari Ini</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $todayTransactions }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                <i class="material-symbols-rounded text-lg opacity-10">receipt</i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Dalam Proses</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $processingLaundry }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="material-symbols-rounded text-lg opacity-10">refresh</i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Belum Lunas</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $pendingPayments }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                <i class="material-symbols-rounded text-lg opacity-10">pending</i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <!-- Quick Actions -->
    <div class="col-lg-4 col-md-6 mt-4 mb-4">
      <div class="card h-100">
        <div class="card-header pb-0">
          <h6>Quick Actions</h6>
        </div>
        <div class="card-body p-3">
          <div class="d-grid gap-2">
            <a href="{{ route('kasir.transactions.create') }}" class="btn btn-lg bg-gradient-dark">
              <i class="material-symbols-rounded me-2">add</i> Buat Transaksi Baru
            </a>
            <a href="{{ route('kasir.customers.create') }}" class="btn btn-lg bg-gradient-info">
              <i class="material-symbols-rounded me-2">person_add</i> Tambah Pelanggan
            </a>
            <a href="{{ route('kasir.transactions.index') }}" class="btn btn-lg bg-gradient-success">
              <i class="material-symbols-rounded me-2">list_alt</i> Lihat Semua Transaksi
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="col-lg-8 col-md-6 mt-4 mb-4">
      <div class="card">
        <div class="card-header pb-0">
          <h6>Transaksi Terbaru</h6>
        </div>
        <div class="card-body p-3">
          <div class="table-responsive">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invoice</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pelanggan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Waktu</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentTransactions as $transaction)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $transaction->invoice_number }}</h6>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $transaction->customer->name }}</p>
                  </td>
                  <td class="align-middle text-sm">
                    <span class="text-xs font-weight-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                  </td>
                  <td class="align-middle">
                    <span class="badge badge-sm bg-gradient-{{ 
                      $transaction->status === 'diterima' ? 'warning' : 
                      ($transaction->status === 'dalam_proses' ? 'info' : 
                      ($transaction->status === 'selesai' ? 'success' : 'secondary')) 
                    }}">
                      {{ $transaction->status_label }}
                    </span>
                  </td>
                  <td class="align-middle">
                    <span class="text-secondary text-xs font-weight-bold">
                      {{ $transaction->created_at->format('H:i') }}
                    </span>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('kasir.transactions.index') }}" class="btn btn-sm bg-gradient-dark w-100">Lihat Semua</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection