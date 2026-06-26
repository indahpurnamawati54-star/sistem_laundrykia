@extends('layouts.app')

@section('title', 'Manajemen Layanan')
@section('page-title', 'Manajemen Layanan Laundry')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <h6>Daftar Layanan</h6>
            <a href="{{ route('admin.services.create') }}" class="btn btn-sm bg-gradient-dark">
              <i class="material-symbols-rounded">add</i> Tambah Layanan
            </a>
          </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Layanan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tipe</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estimasi</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Diskon</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Transaksi</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($services as $service)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <i class="material-symbols-rounded text-gradient text-{{ 
                          $service->type === 'kiloan' ? 'primary' : 
                          ($service->type === 'satuan' ? 'success' : 'warning') 
                        }}">local_laundry_service</i>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $service->name }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ Str::limit($service->description, 50) }}</p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="badge badge-sm bg-gradient-{{ 
                      $service->type === 'kiloan' ? 'primary' : 
                      ($service->type === 'satuan' ? 'success' : 'warning') 
                    }}">
                      {{ ucfirst($service->type) }}
                    </span>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $service->formatted_price }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $service->estimated_completion_time }}</p>
                  </td>
                  <td class="align-middle">
                    <span class="text-xs font-weight-bold">{{ $service->discount }}%</span>
                  </td>
                  <td class="align-middle">
                    <form action="{{ route('admin.services.toggle-status', $service->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-{{ $service->is_active ? 'success' : 'secondary' }}">
                        {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                      </button>
                    </form>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold">{{ $service->transactions_count ?? 0 }}</span>
                  </td>
                  <td class="align-middle">
                    <div class="btn-group">
                      <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-warning">
                        <i class="material-symbols-rounded">edit</i>
                      </a>
                      <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Hapus layanan ini?')"
                                {{ $service->transactions_count > 0 ? 'disabled' : '' }}>
                          <i class="material-symbols-rounded">delete</i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="text-center py-4">Tidak ada data layanan</td>
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
@endsection