@extends('layout.layout')

@section('content')

<!-- Dashboard Header -->
<div class="dashboard-header-hero mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="header-content">
                <div class="greeting-animation">
                    <h1 class="display-5 fw-bold mb-2 text-white">
                        Selamat Datang, <span class="gradient-text">{{ Auth::user()->name ?? 'Admin' }}</span>! 👋
                    </h1>
                </div>
                <p class="lead text-white-50 mb-0">Kelola booking lapangan futsal Anda dengan mudah dan efisien</p>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-flex justify-content-md-end gap-3 mt-3 mt-md-0">
                <button class="btn btn-light btn-lg shadow-lg btn-hover-lift" data-bs-toggle="modal" data-bs-target="#quickBookingModal">
                    <i class="bi bi-plus-circle me-2"></i> Quick Booking
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-card-primary h-100">
            <div class="stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Total Booking</p>
                <h3 class="stat-value">{{ $totalBookings ?? 156 }}</h3>
                <span class="stat-trend trend-up"><i class="bi bi-arrow-up"></i> +12% bulan ini</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card stat-card-success h-100">
            <div class="stat-icon">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Pendapatan Bulan Ini</p>
                <h3 class="stat-value">Rp {{ number_format($monthlyRevenue ?? 45000000, 0, ',', '.') }}</h3>
                <span class="stat-trend trend-up"><i class="bi bi-arrow-up"></i> +8%</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card stat-card-info h-100">
            <div class="stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Booking Hari Ini</p>
                <h3 class="stat-value">{{ $todayBookings ?? 12 }}</h3>
                <span class="stat-trend trend-neutral"><i class="bi bi-clock"></i> 3 sedang berlangsung</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card stat-card-warning h-100">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Member Aktif</p>
                <h3 class="stat-value">{{ $activeMembers ?? 89 }}</h3>
                <span class="stat-trend trend-up"><i class="bi bi-arrow-up"></i> +5 member baru</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row">
    <!-- Jadwal Booking Hari Ini -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">📅 Jadwal Booking Hari Ini</h5>
                        <small class="text-muted">Kelola semua booking lapangan futsal Anda</small>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-light active">Hari Ini</button>
                        <button class="btn btn-light">Besok</button>
                        <button class="btn btn-light">Minggu Ini</button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-bold text-muted text-uppercase small">Waktu</th>
                                <th class="fw-bold text-muted text-uppercase small">Lapangan</th>
                                <th class="fw-bold text-muted text-uppercase small">Customer</th>
                                <th class="fw-bold text-muted text-uppercase small">Status</th>
                                <th class="fw-bold text-muted text-uppercase small">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todaySchedule ?? [] as $schedule)
                            <tr class="booking-row">
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="booking-time-icon me-3">
                                            <i class="bi bi-clock-history"></i>
                                        </div>
                                        <strong class="time-badge">{{ $schedule->time ?? '08:00 - 10:00' }}</strong>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-primary">{{ $schedule->field ?? 'Lapangan A' }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-secondary me-3">
                                            {{ strtoupper(substr($schedule->customer ?? 'John Doe', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $schedule->customer ?? 'John Doe' }}</div>
                                            <small class="text-muted">{{ $schedule->phone ?? '0812-xxxx-xxxx' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    @php
                                        $status = $schedule->status ?? 'confirmed';
                                        $badges = [
                                            'confirmed' => 'success',
                                            'pending' => 'warning',
                                            'ongoing' => 'info',
                                            'completed' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $badges[$status] ?? 'secondary' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-primary btn-action" title="Detail" data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Check-in" data-bs-toggle="tooltip">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-action" title="Cancel" data-bs-toggle="tooltip">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="empty-state">
                                        <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-50"></i>
                                        <p class="mb-1 fw-bold">Belum ada booking untuk hari ini</p>
                                        <small>Buat booking baru dengan menekan tombol Quick Booking</small>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Status Lapangan -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-4">
                <h5 class="mb-0 fw-bold text-dark">🏟️ Status Lapangan</h5>
            </div>
            <div class="card-body">
                @foreach($fields ?? [] as $field)
                <div class="field-status-item mb-4 pb-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">{{ $field->name ?? 'Lapangan A' }}</h6>
                            <small class="text-muted">{{ $field->type ?? 'Sintetis' }}</small>
                        </div>
                        <span class="badge badge-status bg-{{ ($field->status ?? 'available') == 'available' ? 'success' : 'danger' }} px-3 py-2">
                            <i class="bi {{ ($field->status ?? 'available') == 'available' ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                            {{ ($field->status ?? 'available') == 'available' ? 'Tersedia' : 'Terpakai' }}
                        </span>
                    </div>
                </div>
                @endforeach
                
                @if(empty($fields))
                <div class="field-status-item mb-4 pb-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">Lapangan A</h6>
                            <small class="text-muted">Sintetis Premium</small>
                        </div>
                        <span class="badge badge-status bg-success px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>Tersedia
                        </span>
                    </div>
                </div>
                <div class="field-status-item mb-4 pb-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">Lapangan B</h6>
                            <small class="text-muted">Rumput Sintetis</small>
                        </div>
                        <span class="badge badge-status bg-danger px-3 py-2">
                            <i class="bi bi-x-circle me-1"></i>Terpakai
                        </span>
                    </div>
                </div>
                <div class="field-status-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">Lapangan C</h6>
                            <small class="text-muted">Indoor Parket</small>
                        </div>
                        <span class="badge badge-status bg-success px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>Tersedia
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Calendar Mini -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-4">
                <h5 class="mb-0 fw-bold text-dark">📆 Kalender Booking</h5>
            </div>
            <div class="card-body">
                <div id="miniCalendar" class="text-center">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <button class="btn btn-sm btn-outline-primary rounded-circle p-2">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <h6 class="mb-0 fw-bold">Desember 2024</h6>
                        <button class="btn btn-sm btn-outline-primary rounded-circle p-2">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                    <div class="calendar-grid">
                        <div class="row text-center small fw-bold text-muted mb-3">
                            <div class="col">Min</div>
                            <div class="col">Sen</div>
                            <div class="col">Sel</div>
                            <div class="col">Rab</div>
                            <div class="col">Kam</div>
                            <div class="col">Jum</div>
                            <div class="col">Sab</div>
                        </div>
                        <div class="row g-2">
                            @for($i = 1; $i <= 31; $i++)
                            <div class="col-auto">
                                <div class="calendar-day {{ $i == 10 ? 'active' : '' }}">{{ $i }}</div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-4">
                <h5 class="mb-0 fw-bold text-dark">⚡ Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <button class="btn btn-gradient btn-lg" data-bs-toggle="modal" data-bs-target="#quickBookingModal">
                        <i class="bi bi-plus-circle me-2"></i> Booking Baru
                    </button>
                    <button class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-calendar3 me-2"></i> Jadwal Lengkap
                    </button>
                    <button class="btn btn-outline-success btn-lg">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan
                    </button>
                    <button class="btn btn-outline-info btn-lg">
                        <i class="bi bi-gear me-2"></i> Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Analytics -->
<div class="row mt-5">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-4">
                <h5 class="mb-0 fw-bold text-dark">📊 Statistik Booking</h5>
                <small class="text-muted">Tren booking 12 bulan terakhir</small>
            </div>
            <div class="card-body">
                <canvas id="bookingChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-4">
                <h5 class="mb-0 fw-bold text-dark">🏆 Lapangan Terpopuler</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Lapangan A</span>
                        <strong class="text-primary">68%</strong>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-primary" style="width: 68%"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Lapangan B</span>
                        <strong class="text-success">52%</strong>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" style="width: 52%"></div>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Lapangan C</span>
                        <strong class="text-info">45%</strong>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-info" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Booking Modal -->
<div class="modal fade" id="quickBookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2 fs-5"></i>
                    <h5 class="modal-title fw-bold">Quick Booking</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickBookingForm">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Nama Customer</label>
                            <input type="text" class="form-control form-control-lg" placeholder="Nama lengkap" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">No. Telepon</label>
                            <input type="tel" class="form-control form-control-lg" placeholder="08xx-xxxx-xxxx" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Pilih Lapangan</label>
                            <select class="form-select form-select-lg" required>
                                <option selected disabled>-- Pilih Lapangan --</option>
                                <option>Lapangan A</option>
                                <option>Lapangan B</option>
                                <option>Lapangan C</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Tanggal</label>
                            <input type="date" class="form-control form-control-lg" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Jam Mulai</label>
                            <input type="time" class="form-control form-control-lg" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Durasi (Jam)</label>
                            <select class="form-select form-select-lg" required>
                                <option selected disabled>-- Pilih Durasi --</option>
                                <option>1 Jam</option>
                                <option>2 Jam</option>
                                <option>3 Jam</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-gradient btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Buat Booking
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* ============ Hero Header ============ */
    .dashboard-header-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        padding: 50px 30px;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }

    .dashboard-header-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        filter: blur(40px);
    }

    .dashboard-header-hero::after {
        content: '';
        position: absolute;
        bottom: -50%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        filter: blur(30px);
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .greeting-animation {
        animation: slideInDown 0.6s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .gradient-text {
        background: linear-gradient(45deg, #fff, #f0f0f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .btn-hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
    }

    /* ============ Stat Cards ============ */
    .stat-card {
        position: relative;
        padding: 25px;
        border: none;
        border-radius: 15px;
        background: white;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .stat-card:hover::before {
        left: 100%;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 15px;
        color: white;
        position: relative;
        z-index: 1;
    }

    .stat-card-primary .stat-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card-success .stat-icon {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .stat-card-info .stat-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-card-warning .stat-icon {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .stat-content {
        position: relative;
        z-index: 1;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .stat-trend {
        font-size: 0.75rem;
        padding: 6px 12px;
        border-radius: 8px;
        display: inline-block;
        font-weight: 600;
    }

    .stat-trend.trend-up {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-trend.trend-neutral {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    /* ============ Avatar Circle ============ */
    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* ============ Calendar ============ */
    .calendar-day {
        display: inline-block;
        width: calc(100% / 7);
        padding: 10px 0;
        text-align: center;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .calendar-day:hover {
        background-color: #f3f4f6;
        transform: scale(1.05);
    }

    .calendar-day.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* ============ Cards ============ */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-hover tbody tr:hover {
        background-color: #f9fafb;
    }

    .btn-group-sm .btn {
        transition: all 0.3s;
    }

    .btn-outline-primary:hover {
        transform: scale(1.08);
    }

    /* ============ Badge Enhancements ============ */
    .badge {
        padding: 8px 14px !important;
        border-radius: 20px !important;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    }

    .badge.bg-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
    }

    .badge.bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    }

    .badge.bg-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%) !important;
    }

    /* ============ Progress Bar ============ */
    .progress {
        border-radius: 10px;
        background-color: #e5e7eb;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
    }

    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
        font-weight: bold;
        color: white;
    }

    .progress-bar.bg-primary {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%) !important;
    }

    .progress-bar.bg-success {
        background: linear-gradient(90deg, #10b981 0%, #059669 100%) !important;
    }

    .progress-bar.bg-info {
        background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%) !important;
    }

    /* ============ Modal Enhancements ============ */
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        border: none;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e5e7eb;
        padding: 12px 16px;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* ============ Chart Container ============ */
    #bookingChart {
        max-height: 300px;
    }

    /* ============ Animations ============ */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .row > div {
        animation: slideInUp 0.5s ease-out;
        animation-fill-mode: both;
    }

    .row > div:nth-child(1) { animation-delay: 0.1s; }
    .row > div:nth-child(2) { animation-delay: 0.15s; }
    .row > div:nth-child(3) { animation-delay: 0.2s; }
    .row > div:nth-child(4) { animation-delay: 0.25s; }

    /* ============ Responsive ============ */
    @media (max-width: 768px) {
        .dashboard-header-hero {
            padding: 30px 20px;
        }

        .dashboard-header-hero h1 {
            font-size: 1.8rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }
    }

    /* ============ Booking Row & Actions ============ */
    .booking-row {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .booking-row:hover {
        background-color: #f9fafb;
    }

    .booking-time-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 16px;
    }

    .time-badge {
        color: #1f2937;
        font-size: 0.95rem;
    }

    .btn-action {
        padding: 6px 10px !important;
        border-radius: 8px !important;
    }

    .btn-action:hover {
        transform: scale(1.1);
    }

    .empty-state {
        animation: fadeIn 0.5s ease;
    }

    /* ============ Field Status ============ */
    .field-status-item {
        transition: all 0.3s ease;
    }

    .field-status-item:hover {
        transform: translateX(5px);
    }

    .badge-status {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .badge-status:hover {
        transform: translateY(-2px);
    }

    /* ============ Button Gradient ============ */
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-gradient:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-lg {
        padding: 12px 24px;
        font-size: 1rem;
    }

    /* ============ Form Enhancements ============ */
    .form-control-lg,
    .form-select-lg {
        padding: 14px 16px;
        font-size: 0.95rem;
    }

    /* ============ Table Enhancements ============ */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        border-top: none;
        border-bottom: 2px solid #e5e7eb;
        padding: 16px;
        font-weight: 700;
        color: #6b7280;
        font-size: 0.85rem;
    }

    .table tbody td {
        padding: 16px;
        vertical-align: middle;
    }

    /* ============ Calendar Grid ============ */
    .calendar-grid {
        padding: 10px 0;
    }

    /* ============ Tooltips & Interactions ============ */
    [data-bs-toggle="tooltip"] {
        cursor: help;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
    });

    // Booking Chart
    const ctx = document.getElementById('bookingChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Total Booking',
                    data: [45, 52, 48, 65, 72, 68, 75, 82, 78, 85, 90, 95],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.08)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 14,
                                weight: '600'
                            }
                        }
                    },
                    filler: {
                        propagate: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }

    // Calendar day selection
    document.querySelectorAll('.calendar-day').forEach(day => {
        day.addEventListener('click', function() {
            document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Smooth scroll for button clicks
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>

@endsection