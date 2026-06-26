@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="card">
        <div class="card-header pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <h5>Detail Transaksi</h5>
            <span class="badge bg-gradient-{{ 
              $transaction->status === 'diterima' ? 'warning' : 
              ($transaction->status === 'dalam_proses' ? 'info' : 
              ($transaction->status === 'selesai' ? 'success' : 'secondary')) 
            }}">
              {{ $transaction->status_label }}
            </span>
          </div>
        </div>
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-md-6">
              <h6 class="text-sm">Informasi Transaksi</h6>
              <table class="table table-sm">
                <tr>
                  <td width="40%"><strong>Invoice</strong></td>
                  <td>{{ $transaction->invoice_number }}</td>
                </tr>
                <tr>
                  <td><strong>Tanggal</strong></td>
                  <td>{{ $transaction->created_at->format('d F Y H:i') }}</td>
                </tr>
                <tr>
                  <td><strong>Kasir</strong></td>
                  <td>{{ $transaction->cashier->name }}</td>
                </tr>
                <tr>
                  <td><strong>Status</strong></td>
                  <td>
                    <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST" class="d-inline">
                      @csrf
                      <div class="input-group input-group-sm">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                          <option value="diterima" {{ $transaction->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                          <option value="dalam_proses" {{ $transaction->status == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                          <option value="selesai" {{ $transaction->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                          <option value="diambil" {{ $transaction->status == 'diambil' ? 'selected' : '' }}>Diambil</option>
                        </select>
                      </div>
                    </form>
                  </td>
                </tr>
              </table>
            </div>
            
            <div class="col-md-6">
              <h6 class="text-sm">Informasi Pelanggan</h6>
              <table class="table table-sm">
                <tr>
                  <td width="40%"><strong>Nama</strong></td>
                  <td>{{ $transaction->customer->name }}</td>
                </tr>
                <tr>
                  <td><strong>Email</strong></td>
                  <td>{{ $transaction->customer->email }}</td>
                </tr>
                <tr>
                  <td><strong>Telepon</strong></td>
                  <td>{{ $transaction->customer->phone }}</td>
                </tr>
                <tr>
                  <td><strong>Alamat</strong></td>
                  <td>{{ $transaction->customer->address }}</td>
                </tr>
              </table>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-12">
              <h6 class="text-sm">Detail Layanan</h6>
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Layanan</th>
                      <th>Tipe</th>
                      <th>Jumlah</th>
                      <th>Harga</th>
                      <th>Diskon</th>
                      <th>Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ $transaction->service->name }}</td>
                      <td>{{ ucfirst($transaction->service->type) }}</td>
                      <td>
                        @if($transaction->service->type == 'kiloan')
                          {{ $transaction->weight }} kg
                        @else
                          {{ $transaction->quantity }} item
                        @endif
                      </td>
                      <td>Rp {{ number_format($transaction->price, 0, ',', '.') }}</td>
                      <td>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                      <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h6>Timeline Proses</h6>
                </div>
                <div class="card-body">
                  <div class="timeline timeline-one-side">
                    @foreach($transaction->getTimeline() as $timeline)
                    <div class="timeline-block mb-3">
                      <span class="timeline-step">
                        <i class="material-symbols-rounded text-{{ $timeline['completed'] ? 'success' : 'secondary' }} text-gradient">
                          {{ $timeline['completed'] ? 'check_circle' : 'radio_button_unchecked' }}
                        </i>
                      </span>
                      <div class="timeline-content">
                        <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $timeline['status'] }}</h6>
                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                          @if($timeline['time'])
                            {{ $timeline['time']->format('d M Y H:i') }}
                          @else
                            Menunggu...
                          @endif
                        </p>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h6>Informasi Pembayaran</h6>
                </div>
                <div class="card-body">
                  @if($transaction->is_paid)
                    <div class="alert alert-success">
                      <i class="material-symbols-rounded">check_circle</i>
                      Pembayaran Lunas
                    </div>
                    <p><strong>Metode:</strong> {{ $transaction->payment_method_label }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                  @else
                    <div class="alert alert-warning">
                      <i class="material-symbols-rounded">pending</i>
                      Menunggu Pembayaran
                    </div>
                    <form action="{{ route('admin.transactions.process-payment', $transaction->id) }}" method="POST">
                      @csrf
                      <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" class="form-control" required>
                          <option value="">Pilih Metode</option>
                          <option value="cash">Cash</option>
                          <option value="transfer">Transfer Bank</option>
                          <option value="e-wallet">E-Wallet</option>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-success w-100">Proses Pembayaran</button>
                    </form>
                  @endif
                </div>
              </div>
            </div>
          </div>
          
          @if($transaction->notes)
          <div class="row mt-4">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h6>Catatan</h6>
                </div>
                <div class="card-body">
                  <p>{{ $transaction->notes }}</p>
                </div>
              </div>
            </div>
          </div>
          @endif
          
          <div class="row mt-4">
            <div class="col-12">
              <div class="d-flex justify-content-between">
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Kembali</a>
                <div>
                  <a href="{{ route('admin.transactions.print-invoice', $transaction->id) }}" target="_blank" class="btn btn-primary">
                    <i class="material-symbols-rounded">print</i> Cetak Invoice
                  </a>
                  @if($transaction->status == 'diterima' && $transaction->created_at->diffInHours(now()) <= 24)
                  <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus transaksi ini?')">
                      <i class="material-symbols-rounded">delete</i> Hapus
                    </button>
                  </form>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection