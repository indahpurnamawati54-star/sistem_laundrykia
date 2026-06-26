@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Admin')

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

    <div class="col-xl-3 col-sm-6">
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
  </div>

  <div class="row mt-4">
    <!-- Layanan Populer -->
    <div class="col-lg-5 col-md-6 mt-4 mb-4">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>Layanan Populer</h6>
        </div>
        <div class="card-body p-3">
          <div class="table-responsive">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Layanan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Transaksi</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pendapatan</th>
                </tr>
              </thead>
              <tbody>
                @foreach($popularServices as $service)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <i class="material-symbols-rounded text-gradient text-{{ $service->type === 'kiloan' ? 'primary' : ($service->type === 'satuan' ? 'success' : 'warning') }}">
                          {{ $service->type === 'kiloan' ? 'scale' : ($service->type === 'satuan' ? 'checklist' : 'bolt') }}
                        </i>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $service->name }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ ucfirst($service->type) }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="align-middle text-center text-sm">
                    <span class="text-xs font-weight-bold">{{ $service->transactions_count }}</span>
                  </td>
                  <td class="align-middle">
                    <div class="progress-wrapper w-75 mx-auto">
                      <div class="progress-info">
                        <div class="progress-percentage">
                          <span class="text-xs font-weight-bold">
                            {{ $service->transactions_count > 0 ? round(($service->transactions_count / $popularServices->sum('transactions_count')) * 100) : 0 }}%
                          </span>
                        </div>
                      </div>
                      <div class="progress">
                        <div class="progress-bar bg-gradient-{{ $service->type === 'kiloan' ? 'primary' : ($service->type === 'satuan' ? 'success' : 'warning') }} w-{{ $service->transactions_count > 0 ? round(($service->transactions_count / $popularServices->sum('transactions_count')) * 100) : 0 }}" 
                             role="progressbar"></div>
                      </div>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart Pendapatan Bulanan -->
    <div class="col-lg-7 col-md-6 mt-4 mb-4">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>Pendapatan Bulanan</h6>
          <p class="text-sm">
            <i class="fa fa-arrow-up text-success"></i>
            <span class="font-weight-bold">Pendapatan {{ date('F Y') }}</span>
          </p>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <canvas id="monthly-income-chart" class="chart-canvas" height="300"></canvas>
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
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invoice</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pelanggan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Layanan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                  <th class="text-secondary opacity-7"></th>
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
                    <p class="text-xs text-secondary mb-0">{{ $transaction->customer->phone }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $transaction->service->name }}</p>
                    <p class="text-xs text-secondary mb-0">{{ ucfirst($transaction->service->type) }}</p>
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
                      {{ $transaction->created_at->format('d M Y H:i') }}
                    </span>
                  </td>
                  <td class="align-middle">
                    <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="text-secondary font-weight-bold text-xs">
                      Detail
                    </a>
                  </td>
                </tr>
                @endforeach
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
  // Monthly Income Chart
  var ctx = document.getElementById("monthly-income-chart").getContext("2d");
  
  var monthlyData = @json($monthlyIncomeData);
  var labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  var data = new Array(12).fill(0);
  
  monthlyData.forEach(item => {
    data[item.month - 1] = item.total;
  });
  
  new Chart(ctx, {
    type: "line",
    data: {
      labels: labels,
      datasets: [{
        label: "Pendapatan",
        tension: 0.4,
        borderWidth: 2,
        pointRadius: 3,
        pointBackgroundColor: "#e91e63",
        pointBorderColor: "transparent",
        borderColor: "#e91e63",
        backgroundColor: "transparent",
        fill: true,
        data: data,
        maxBarThickness: 6
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        }
      },
      scales: {
        y: {
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
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5]
          },
          ticks: {
            padding: 10,
            color: '#737373'
          }
        },
      },
    },
  });
</script>
@endpush
@endsection