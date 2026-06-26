@extends('layouts.app')

@section('title', 'Data Transaksi')
@section('page-title', 'Data Transaksi')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <h6>Daftar Transaksi</h6>
            <div class="dropdown">
              <button class="btn btn-sm bg-gradient-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Filter
              </button>
              <div class="dropdown-menu px-2 py-3">
                <form method="GET" class="px-2">
                  <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" onchange="this.form.submit()">
                      <option value="">Semua Status</option>
                      <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                      <option value="dalam_proses" {{ request('status') == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                      <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                      <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Status Pembayaran</label>
                    <select name="payment_status" class="form-control" onchange="this.form.submit()">
                      <option value="">Semua</option>
                      <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                      <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                  </div>
                  <div class="row g-2">
                    <div class="col-6">
                      <label class="form-label">Dari Tanggal</label>
                      <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-6">
                      <label class="form-label">Sampai Tanggal</label>
                      <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 mt-2">Terapkan</button>
                  <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary w-100 mt-2">Reset</a>
                </form>
              </div>
            </div>
          </div>
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
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pembayaran</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($transactions as $transaction)
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
                    @if($transaction->service->type == 'kiloan')
                      <span class="text-xs">{{ $transaction->weight }} kg</span>
                    @else
                      <span class="text-xs">{{ $transaction->quantity }} item</span>
                    @endif
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
                      <span class="badge badge-sm bg-gradient-success">
                        <i class="material-symbols-rounded">check</i> Lunas
                      </span>
                      <p class="text-xs text-secondary mb-0">{{ $transaction->payment_method_label }}</p>
                    @else
                      <span class="badge badge-sm bg-gradient-danger">Belum Lunas</span>
                    @endif
                  </td>
                  <td class="align-middle">
                    <span class="text-secondary text-xs font-weight-bold">
                      {{ $transaction->created_at->format('d M Y H:i') }}
                    </span>
                  </td>
                  <td class="align-middle">
                    <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="text-secondary font-weight-bold text-xs">
                      <i class="material-symbols-rounded">visibility</i>
                    </a>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="text-center py-4">Tidak ada data transaksi</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            {{ $transactions->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection