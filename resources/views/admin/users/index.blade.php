@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <h6>Daftar User</h6>
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm bg-gradient-dark">
              <i class="material-symbols-rounded">add</i> Tambah User
            </a>
          </div>
          <div class="row mt-3">
            <div class="col-md-8">
              <form method="GET" class="row g-2">
                <div class="col-md-4">
                  <select name="role" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ request('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    <option value="pelanggan" {{ request('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Telepon</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Transaksi</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Daftar</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($users as $user)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <i class="material-symbols-rounded text-gradient text-{{ 
                          $user->role === 'admin' ? 'danger' : 
                          ($user->role === 'kasir' ? 'warning' : 'success') 
                        }}">person</i>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ $user->address }}</p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="badge badge-sm bg-gradient-{{ 
                      $user->role === 'admin' ? 'danger' : 
                      ($user->role === 'kasir' ? 'warning' : 'success') 
                    }}">
                      {{ ucfirst($user->role) }}
                    </span>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $user->phone ?? '-' }}</p>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold">
                      @if($user->role === 'pelanggan')
                        {{ $user->customer_transactions_count ?? 0 }}
                      @elseif($user->role === 'kasir')
                        {{ $user->cashier_transactions_count ?? 0 }}
                      @else
                        -
                      @endif
                    </span>
                  </td>
                  <td class="align-middle">
                    <span class="text-secondary text-xs font-weight-bold">
                      {{ $user->created_at->format('d M Y') }}
                    </span>
                  </td>
                  <td class="align-middle">
                    <div class="btn-group">
                      <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">
                        <i class="material-symbols-rounded">visibility</i>
                      </a>
                      <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                        <i class="material-symbols-rounded">edit</i>
                      </a>
                      <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')">
                          <i class="material-symbols-rounded">delete</i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="text-center py-4">Tidak ada data user</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            {{ $users->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection