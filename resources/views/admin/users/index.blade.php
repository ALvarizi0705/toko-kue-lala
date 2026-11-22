@extends('layouts.admin')
@section('title', 'Kelola Akun Pengguna')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-people me-2"></i>Kelola Akun Pengguna & Staf</h5>
        <p class="text-muted small mb-0">Kelola akun pemilik, staf kasir, staf produksi, dan pelanggan</p>
    </div>
    <a href="{{ route('admin.akun.create') }}" class="btn btn-primary-lala">
        <i class="bi bi-person-plus me-1"></i>Buat Akun Baru
    </a>
</div>

{{-- FILTER --}}
<div class="form-card mb-4 p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama, email, atau nomor HP..." value="{{ request('q') }}">
        </div>
        <div class="col-md-3">
            <select name="role" class="form-select form-select-sm">
                <option value="">Semua Role</option>
                <option value="pemilik" {{ request('role') === 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                <option value="staf_kasir" {{ request('role') === 'staf_kasir' ? 'selected' : '' }}>Staf Kasir</option>
                <option value="staf_produksi" {{ request('role') === 'staf_produksi' ? 'selected' : '' }}>Staf Produksi</option>
                <option value="pelanggan" {{ request('role') === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-primary-lala">Filter</button>
            <a href="{{ route('admin.akun.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
        </div>
    </form>
</div>

<div class="table-lala bg-white rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama Pengguna</th>
                    <th>Kontak (WA & Email)</th>
                    <th>Role Akun</th>
                    <th>Status</th>
                    <th class="text-end">Aksi & Reset Sandi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td class="fw-bold text-choco">{{ $u->name }}</td>
                    <td>
                        <div><i class="bi bi-whatsapp text-success me-1"></i>{{ $u->phone }}</div>
                        <div class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $u->email }}</div>
                    </td>
                    <td>
                        @php
                            $roleColors = [
                                'pemilik' => 'bg-danger',
                                'staf_kasir' => 'bg-primary',
                                'staf_produksi' => 'bg-warning text-dark',
                                'pelanggan' => 'bg-secondary',
                            ];
                        @endphp
                        <span class="badge {{ $roleColors[$u->role] ?? 'bg-secondary' }}">
                            {{ strtoupper(str_replace('_', ' ', $u->role)) }}
                        </span>
                    </td>
                    <td>
                        @if($u->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.akun.edit', $u) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            
                            {{-- TOGGLE STATUS AKTIF/NONAKTIF --}}
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.akun.toggle', $u) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="bi bi-{{ $u->is_active ? 'person-slash' : 'person-check' }}"></i>
                                </button>
                            </form>
                            @endif
                            
                            {{-- RESET PASSWORD MODAL TRIGGER --}}
                            <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#resetModal{{ $u->id }}" title="Reset Kata Sandi">
                                <i class="bi bi-key"></i>
                            </button>
                        </div>
                        
                        {{-- MODAL RESET PASSWORD --}}
                        <div class="modal fade text-start" id="resetModal{{ $u->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.akun.reset-password', $u) }}">
                                        @csrf @method('PATCH')
                                        <div class="modal-header">
                                            <h6 class="modal-title fw-bold">Reset Kata Sandi Akun {{ $u->name }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Kata Sandi Baru (Min. 8 Karakter) <span class="text-danger">*</span></label>
                                                <input type="password" name="new_password" class="form-control" placeholder="Masukkan kata sandi baru" required minlength="8">
                                                <div class="form-text small">Kata sandi baru hasil reset harus dapat langsung dipakai untuk login.</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-sm btn-primary-lala">Reset Kata Sandi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data akun pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $users->links() }}
</div>
@endsection
