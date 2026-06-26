@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan Transaksi')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <h6>Filter Laporan</h6>
            <div class="dropdown">
              <button class="btn btn-sm bg-gradient-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Export
              </button>
              <div class="dropdown-menu">
                <form action="{{ route('admin.reports.export') }}" method="POST" class="px-2">
                  @csrf
                  <input type="hidden" name="start_date" value="{{ $startDate }}">
                  <input type="hidden" name="end_date" value="{{ $endDate }}">
                  <input type="hidden" name="type" value="excel">
                  <button type="submit" class="dropdown-item">Excel</button>
                </form>
                <form action="{{ route('admin.reports.export') }}" method="POST" class="px-2">
                  @csrf
                  <input type="hidden" name="start_date" value="{{ $startDate }}">
                  <input type="hidden" name="end_date" value="{{ $endDate }}">
                  <input type="hidden" name="type" value="pdf">
                  <button type="submit" class="dropdown-item">PDF</button>
                </form>
              </div>
            </div>
          </div>
          <form method="GET" class="row g-3 mt-2">
            <div class="col-md-3">
              <label class="form-label">Dari Tanggal</label>
              <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Sampai Tanggal</label>
              <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
            </div>
            <div class="col-md-2">
              <label class="form-label">Status</label>
              <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="dalam_proses" {{ request('status') == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label">Pembayaran</label>
              <select name="payment_status" class="form-control">
                <option value="">Semua</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
              </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Summary Statistics -->
  <div class="row mb-4">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Transaksi</p>
                <h5 class="font-weight-bolder mb-0">
                  {{ $summary['total_transactions'] }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Pemasukan</p>
                <h5 class="font-weight-bolder mb-0">
                  Rp {{ number_format($summary['total_income'], 0, ',', '.') }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
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
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Tunggakan</p>
                <h5 class="font-weight-bolder mb-0">
                  Rp {{ number_format($summary['pending_payments'], 0, ',', '.') }}
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

    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Rata-rata Transaksi</p>
                <h5 class="font-weight-bolder mb-0">
                  Rp {{ number_format($summary['average_transaction'], 0, ',', '.') }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="material-symbols-rounded text-lg opacity-10">trending_up</i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Daily Report Chart -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>Laporan Harian</h6>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <canvas id="daily-report-chart" class="chart-canvas" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Transaction List -->
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Daftar Transaksi</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Invoice</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pelanggan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Layanan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pembayaran</th>
                </tr>
              </thead>
              <tbody>
                @forelse($transactions as $transaction)
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
                    <p class="text-xs font-weight-bold mb-0">{{ $transaction->customer->name }}</p>
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
                  <td class="align-middle">
                    @if($transaction->is_paid)
                      <span class="badge badge-sm bg-gradient-success">Lunas</span>
                    @else
                      <span class="badge badge-sm bg-gradient-danger">Belum Lunas</span>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="text-center py-4">Tidak ada data transaksi</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Daily Report Chart
  var ctx = document.getElementById("daily-report-chart").getContext("2d");
  
  var dailyData = @json($dailyReport);
  var labels = dailyData.map(item => item.date);
  var incomeData = dailyData.map(item => item.total_income);
  var transactionData = dailyData.map(item => item.transaction_count);
  
  new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [{
        label: "Pemasukan",
        tension: 0.4,
        borderWidth: 0,
        borderRadius: 4,
        borderSkipped: false,
        backgroundColor: "#e91e63",
        data: incomeData,
        yAxisID: 'y',
      }, {
        label: "Jumlah Transaksi",
        tension: 0.4,
        borderWidth: 0,
        borderRadius: 4,
        borderSkipped: false,
        backgroundColor: "#3A416F",
        data: transactionData,
        yAxisID: 'y1',
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        y: {
          type: 'linear',
          display: true,
          position: 'left',
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5],
            color: '#e5e5e5'
          },
          ticks: {
            callback: function(value) {
              return 'Rp ' + value.toLocaleString('id-ID');
            },
            padding: 10,
            color: "#737373"
          },
        },
        y1: {
          type: 'linear',
          display: true,
          position: 'right',
          grid: {
            drawOnChartArea: false,
          },
          ticks: {
            padding: 10,
            color: "#737373"
          }
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5]
          },
          ticks: {
            display: true,
            color: '#737373',
            padding: 10
          }
        },
      },
    },
  });
</script>
@endpush
@endsection