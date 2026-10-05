<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') | Fantastic Stays Luxury Villas</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --fs-bg: #f8faf9;
            --fs-emerald: #0b251d;
            --fs-emerald-dark: #071913;
            --fs-emerald-light: #153b30;
            --fs-gold: #c5a880;
            --fs-gold-light: #e6d7c3;
            --fs-gold-dark: #a9853c;
            --fs-sidebar-width: 270px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--fs-bg);
            color: #2b3b35;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .font-serif, .navbar-brand {
            font-family: 'Outfit', sans-serif;
        }

        /* Sidebar */
        #admin-sidebar {
            width: var(--fs-sidebar-width);
            background: linear-gradient(180deg, var(--fs-emerald) 0%, var(--fs-emerald-dark) 100%);
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
        }

        /* Custom scrollbar for sidebar */
        #admin-sidebar::-webkit-scrollbar { width: 4px; }
        #admin-sidebar::-webkit-scrollbar-track { background: transparent; }
        #admin-sidebar::-webkit-scrollbar-thumb { background: rgba(197,168,128,0.3); border-radius: 2px; }
        #admin-sidebar::-webkit-scrollbar-thumb:hover { background: rgba(197,168,128,0.6); }

        .sidebar-brand {
            padding: 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(197, 168, 128, 0.15);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-category {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(197, 168, 128, 0.6);
            padding: 0.75rem 1.5rem 0.25rem;
            font-weight: 600;
        }

        .sidebar-nav .nav-link {
            color: #d1ded9;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            font-size: 0.92rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.15rem;
            margin-right: 0.85rem;
            color: var(--fs-gold);
            transition: transform 0.2s ease;
        }

        .sidebar-nav .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
            border-left-color: var(--fs-gold);
        }

        .sidebar-nav .nav-link.active {
            color: #ffffff;
            background: rgba(197, 168, 128, 0.15);
            border-left-color: var(--fs-gold);
            font-weight: 600;
        }

        /* Main Content */
        #admin-main {
            margin-left: var(--fs-sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* Top Navbar */
        .admin-topbar {
            background: #ffffff;
            height: 70px;
            border-bottom: 1px solid #eef2f0;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        /* Cards & Components */
        .card-custom {
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card {
            border-radius: 14px;
            border: none;
            background: #ffffff;
            box-shadow: 0 4px 18px rgba(0,0,0,0.04);
            overflow: hidden;
            position: relative;
        }

        .stat-icon-wrapper {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--fs-gold-dark) 0%, var(--fs-gold) 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.55rem 1.25rem;
            transition: all 0.2s ease;
        }

        .btn-gold:hover {
            color: #ffffff;
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(169, 133, 60, 0.3);
        }

        .btn-emerald {
            background: var(--fs-emerald);
            color: #ffffff;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.55rem 1.25rem;
        }

        .btn-emerald:hover {
            background: var(--fs-emerald-light);
            color: #ffffff;
        }

        .badge-gold {
            background-color: rgba(197, 168, 128, 0.18);
            color: var(--fs-gold-dark);
            font-weight: 600;
            border: 1px solid rgba(197, 168, 128, 0.4);
        }

        .table-custom th {
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.8px;
            color: #6c757d;
            font-weight: 600;
            background-color: #fbfcfc;
            border-bottom: 1px solid #edf2f0;
            padding: 1rem;
        }

        .table-custom td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f2f5f4;
            font-size: 0.9rem;
        }

        @media (max-width: 991.98px) {
            #admin-sidebar {
                margin-left: calc(-1 * var(--fs-sidebar-width));
            }
            #admin-sidebar.show {
                margin-left: 0;
            }
            #admin-main {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside id="admin-sidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <div>
                <span class="fs-5 fw-bold text-white tracking-wide d-block">
                    <span style="color: var(--fs-gold);">FANTASTIC</span> STAYS
                </span>
                <small class="text-white-50" style="font-size: 0.72rem; letter-spacing: 1px;">LUXURY VILLAS ADMIN</small>
            </div>
            <button class="btn btn-sm btn-link text-white-50 d-lg-none" id="sidebar-close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-category">Main Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <div class="nav-category">Villas & Properties</div>
            <a href="{{ route('admin.villas.index') }}" class="nav-link {{ request()->routeIs('admin.villas.*') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i> Villas List
            </a>
            <a href="{{ route('admin.villas.create') }}" class="nav-link {{ request()->routeIs('admin.villas.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle-fill"></i> Add New Villa
            </a>

            <div class="nav-category">Reservations & Leads</div>
            <!--<a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check-fill"></i> Bookings
                @php
                    $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                @endif
            </a>-->
            <a href="{{ route('admin.service-leads.index') }}" class="nav-link {{ request()->routeIs('admin.service-leads.*') ? 'active' : '' }}">
                <i class="bi bi-inboxes-fill"></i> Service Leads
                @php
                    $newServiceLeads = \App\Models\ServiceLead::where('status', 'new')->count();
                @endphp
                @if($newServiceLeads > 0)
                    <span class="badge bg-danger ms-auto">{{ $newServiceLeads }}</span>
                @endif
            </a>
            <a href="{{ route('admin.enquiries.index') }}" class="nav-link {{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}">
                <i class="bi bi-whatsapp"></i> Inquiries & Leads
                @php
                    $newEnquiriesCount = \App\Models\Enquiry::where('status', 'new')->count();
                @endphp
                @if($newEnquiriesCount > 0)
                    <span class="badge bg-danger ms-auto">{{ $newEnquiriesCount }}</span>
                @endif
            </a>

            <div class="nav-category">Catalog & Content</div>
            <a href="{{ route('admin.locations.index') }}" class="nav-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt-fill"></i> Goa Locations
            </a>
            <a href="{{ route('admin.occasions.index') }}" class="nav-link {{ request()->routeIs('admin.occasions.*') ? 'active' : '' }}">
                <i class="bi bi-stars"></i> Curated Occasions
            </a>
            <a href="{{ route('admin.why-choose.index') }}" class="nav-link {{ request()->routeIs('admin.why-choose.*') ? 'active' : '' }}">
                <i class="bi bi-patch-check-fill"></i> Why Choose
            </a>
            <a href="{{ route('admin.amenities.index') }}" class="nav-link {{ request()->routeIs('admin.amenities.*') ? 'active' : '' }}">
                <i class="bi bi-card-checklist"></i> Amenities
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-chat-quote-fill"></i> Guest Reviews
            </a>

            <div class="nav-category">System</div>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i> Site Settings
            </a>
            <a href="/api/v1/villas" target="_blank" class="nav-link">
                <i class="bi bi-code-slash"></i> API Endpoints <i class="bi bi-box-arrow-up-right ms-auto small text-white-50"></i>
            </a>
        </nav>
    </aside>

    <!-- Main Wrapper -->
    <div id="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary btn-sm d-lg-none me-3" id="sidebar-toggle">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="d-none d-md-flex align-items-center text-muted small">
                    <i class="bi bi-shield-check text-success me-2"></i>
                    <span>Laravel 11 Backend &bull; MySQL Database Connected</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="https://web.whatsapp.com" target="_blank" class="btn btn-sm btn-outline-success d-none d-sm-inline-flex align-items-center gap-1 rounded-pill px-3">
                    <i class="bi bi-whatsapp"></i> WhatsApp Concierge
                </a>

                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light d-flex align-items-center gap-2 rounded-pill px-3 py-1 border" type="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.85rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="fw-semibold small d-none d-sm-inline">{{ Auth::user()->name ?? 'Administrator' }}</span>
                        <i class="bi bi-chevron-down small text-muted"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2" style="border-radius: 10px;">
                        <li class="px-3 py-1 text-muted small">
                            <strong>{{ Auth::user()->email ?? 'admin@fantasticstays.com' }}</strong><br>
                            <span class="badge bg-success-subtle text-success">Super Admin</span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="p-4 p-md-5 flex-grow-1">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-2"></i> Please fix the following errors:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white py-3 px-4 border-top text-center text-muted small">
            Fantastic Stays Luxury Villas &bull; Laravel 11 Backend & Admin System &bull; &copy; {{ date('Y') }}
        </footer>
    </div>

    <!-- Bootstrap 5.3 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar on mobile
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarClose = document.getElementById('sidebar-close');
        const sidebar = document.getElementById('admin-sidebar');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
        if (sidebarClose) {
            sidebarClose.addEventListener('click', () => {
                sidebar.classList.remove('show');
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
