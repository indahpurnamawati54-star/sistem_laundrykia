@extends('layouts.app')

@section('title', 'Pelanggan Dashboard')
@section('page-title', 'Dashboard Pelanggan')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <!-- Statistik -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $pendingTransactions }}
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

    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Dalam Proses</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $processingTransactions }}
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

    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Selesai</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $completedTransactions }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                <i class="material-symbols-rounded text-lg opacity-10">check_circle</i>
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Pengeluaran</p>
                <h5 class="font-weight-bolder mb-0">
                  Rp {{ number_format($totalSpent, 0, ',', '.') }}
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
  </div>

  <!-- Laundry Aktif -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Laundry Aktif</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invoice</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Layanan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Progress</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estimasi Selesai</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($activeTransactions as $transaction)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $transaction->invoice_number }}</h6>
                        <p class="text-xs text-secondary mb-0">
                          {{ $transaction->created_at->format('d M Y') }}
                        </p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $transaction->service->name }}</p>
                    <p class="text-xs text-secondary mb-0">
                      @if($transaction->service->type == 'kiloan')
                        {{ $transaction->weight }} kg
                      @else
                        {{ $transaction->quantity }} item
                      @endif
                    </p>
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
                    <div class="progress-wrapper w-75">
                      <div class="progress-info">
                        <div class="progress-percentage">
                          <span class="text-xs font-weight-bold">{{ $transaction->progress_percentage }}%</span>
                        </div>
                      </div>
                      <div class="progress">
                        <div class="progress-bar bg-gradient-{{ 
                          $transaction->status === 'diterima' ? 'warning' : 
                          ($transaction->status === 'dalam_proses' ? 'info' : 'success') 
                        }} w-{{ $transaction->progress_percentage }}" 
                             role="progressbar"></div>
                      </div>
                    </div>
                  </td>
                  <td class="align-middle">
                    <span class="text-secondary text-xs font-weight-bold">
                      @if($transaction->estimated_completion_time)
                        {{ $transaction->estimated_completion_time->format('d M H:i') }}
                      @else
                        -
                      @endif
                    </span>
                  </td>
                  <td class="align-middle">
                    <a href="{{ route('pelanggan.transactions.show', $transaction->id) }}" class="btn btn-sm btn-info">
                      <i class="material-symbols-rounded">visibility</i>
                    </a>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center py-4">Tidak ada laundry aktif</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Transaksi Terbaru -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Transaksi Terbaru</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Invoice</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Layanan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentTransactions as $transaction)
                <tr>
                  <td>
                    <span class="text-secondary text-xs font-weight-bold">
                      {{ $transaction->created_at->format('d M Y') }}
                    </span>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $transaction->invoice_number }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $transaction->service->name }}</p>
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
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('pelanggan.transactions.index') }}" class="btn btn-sm bg-gradient-dark w-100">Lihat Semua Transaksi</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection