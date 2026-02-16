<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion Facturation | Dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        /* Animation de scintillement */
        @keyframes shimmer {
            0% {
                background-position: -200% center;
            }

            100% {
                background-position: 200% center;
            }
        }

        /* Animation de pulsation "Radioactive/Alert" */
        @keyframes pulse-glow {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
            }
        }

        .btn-sparkle {
            /* Gradient doré/jaune intense */
            background: linear-gradient(45deg, #ffc107, #ffe066, #ffc107) !important;
            background-size: 200% auto !important;
            color: #333 !important;
            font-weight: bold;
            border: 1px solid #d39e00;
            /* Combinaison des animations */
            animation: shimmer 3s linear infinite, pulse-glow 2s infinite;
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* Effet de reflet blanc qui traverse */
        .btn-sparkle::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: shimmer 3s infinite;
            /* Synchro avec le background */
            transform: skewX(-20deg);
            z-index: 2;
        }

        .btn-status-action {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .btn-status-action:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
            filter: brightness(1.1);
        }
    </style>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-dark border-bottom-0"
                style="background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                                class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="{{ route('home') }}" class="nav-link">Tableau de Bord</a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="{{ route('factures.index') }}" class="nav-link">Factures</a>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <!-- User Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-user-circle"></i>
                            <span class="d-none d-md-inline ml-1">{{ Auth::user()->name ?? 'Utilisateur' }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-item dropdown-header">Mon Compte</span>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('profile.show') }}" class="dropdown-item">
                                <i class="fas fa-user mr-2"></i> Profil
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST" class="dropdown-item p-0">
                                @csrf
                                <button type="submit" class="btn btn-link btn-block text-left text-danger px-4 py-2">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #0f172a;">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="brand-link"
                    style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <span class="brand-image img-circle elevation-3"
                        style="opacity: .8; background: #fff; color: #000; width: 33px; height: 33px; text-align: center; line-height: 33px; font-weight: bold;">GF</span>
                    <span class="brand-text font-weight-light">Gestion Facturation</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center"
                        style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <div class="image">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'U') }}&background=random"
                                class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block"
                                style="color: #cbd5e1;">{{ Auth::user()->name ?? 'Utilisateur' }}</a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                            data-accordion="false">
                            <li class="nav-item mb-1">
                                <a href="{{ route('home') }}"
                                    class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                                    style="{{ request()->routeIs('home') ? 'background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item mb-1">
                                <a href="{{ route('factures.index') }}"
                                    class="nav-link {{ request()->routeIs('factures.index') && !request('entreprise_id') ? 'active' : '' }}"
                                    style="{{ request()->routeIs('factures.index') && !request('entreprise_id') ? 'background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);' : '' }}">
                                    <i class="nav-icon fas fa-file-invoice"></i>
                                    <p>Toutes les Factures</p>
                                </a>
                            </li>

                            <li class="nav-header text-uppercase text-muted font-weight-bold mt-3 mb-2"
                                style="font-size: 0.8rem; letter-spacing: 1px;">ENTREPRISES</li>

                            @foreach ($sidebar_entreprises as $entreprise)
                                <li class="nav-item mb-1">
                                    <a href="{{ route('factures.index', ['entreprise_id' => $entreprise->id]) }}"
                                        class="nav-link {{ request('entreprise_id') == $entreprise->id ? 'active' : '' }}"
                                        style="{{ request('entreprise_id') == $entreprise->id ? 'background: linear-gradient(90deg, #10b981 0%, #059669 100%);box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);' : '' }}">
                                        <i class="nav-icon fas fa-building"></i>
                                        <p>
                                            {{ $entreprise->nom }}
                                        </p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>@yield('title', 'Dashboard')</h1>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <footer class="main-footer">
                <div class="float-right d-none d-sm-block">
                    <b>Version</b> 1.0.0
                </div>
                <strong>Copyright &copy; {{ date('Y') }} Gestion Facturation.</strong> Tous droits réservés.
            </footer>

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

        @stack('styles')
        @stack('scripts')
</body>

</html>
