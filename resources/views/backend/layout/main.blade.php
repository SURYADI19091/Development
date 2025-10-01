<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Panel Desa Ciuwlan</title>
    
    <!-- Local Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <!-- Bootstrap CSS (included in AdminLTE) -->
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Styles -->
    <style>
        .wrapper {
            min-height: 100vh;
        }
        
        .main-sidebar {
            background: linear-gradient(180deg, #343a40 0%, #495057 100%);
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }
        
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
            background-color: rgba(255,255,255,.1);
        }
        
        .content-wrapper {
            background-color: #f4f6f9;
        }
        
        .nav-header {
            background: rgba(0,0,0,.1);
            color: #c2c7d0;
        }
        
        /* Dropdown fixes */
        .dropdown-menu.show {
            display: block !important;
        }
        
        .dropdown-toggle::after {
            display: none;
        }
        
        .navbar .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            z-index: 1000;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            font-size: 14px;
            color: #212529;
            background-color: #fff;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
        }
        
        .sidebar-mini .main-sidebar:hover .nav-link p {
            display: inline-block !important;
        }
        
        .brand-text {
            color: #fff !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        @include('backend.layout.navbar')
        
        <!-- Main Sidebar Container -->
        @include('backend.layout.sidebar')
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('page_title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                    @if(View::hasSection('page_actions'))
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <div class="float-right">
                                    @yield('page_actions')
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-check"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-ban"></i> {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fas fa-exclamation-triangle"></i> {{ session('warning') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-ban"></i> Terdapat kesalahan:</h5>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Main Content Area -->
                    @yield('content')
                </div>
            </section>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- JavaScript -->
    <script>
        // CSRF Token Setup
        window.csrf_token = '{{ csrf_token() }}';
        
        // Initialize AdminLTE components
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap dropdowns
            if (typeof $.fn.dropdown !== 'undefined') {
                $('[data-toggle="dropdown"]').dropdown();
                console.log('Bootstrap dropdown initialized');
            } else {
                console.error('Bootstrap dropdown not available');
                // Fallback: Manual dropdown toggle
                $('[data-toggle="dropdown"]').on('click', function(e) {
                    e.preventDefault();
                    const menu = $(this).next('.dropdown-menu');
                    $('.dropdown-menu').not(menu).removeClass('show');
                    menu.toggleClass('show');
                });
                
                // Close dropdown when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.dropdown').length) {
                        $('.dropdown-menu').removeClass('show');
                    }
                });
            }
            
            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('[id$="-alert"]');
                alerts.forEach(alert => {
                    if (alert) {
                        alert.style.transition = 'opacity 0.5s ease-out';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                });
            }, 5000);
        });
        
        // Sidebar Toggle Function
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('hidden');
            }
        }
        
        // Loading Overlay Functions
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
            document.getElementById('loading-overlay').classList.add('flex');
        }
        
        function hideLoading() {
            document.getElementById('loading-overlay').classList.add('hidden');
            document.getElementById('loading-overlay').classList.remove('flex');
        }
        
        // Form Auto-submit with Loading
        function submitFormWithLoading(formId) {
            showLoading();
            document.getElementById(formId).submit();
        }
        
        // AJAX Helper Function
        function makeRequest(url, method = 'GET', data = null, headers = {}) {
            const defaultHeaders = {
                'X-CSRF-TOKEN': window.csrf_token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            };
            
            return fetch(url, {
                method: method,
                headers: { ...defaultHeaders, ...headers },
                body: data ? JSON.stringify(data) : null
            });
        }
        
        // Confirmation Dialog
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
        
        // Delete Function with Confirmation
        function deleteItem(url, message = 'Apakah Anda yakin ingin menghapus item ini?') {
            confirmAction(message, function() {
                showLoading();
                makeRequest(url, 'DELETE')
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Terjadi kesalahan saat menghapus item.');
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus item.');
                    });
            });
        }
        
        // Responsive handling
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('show');
                overlay.classList.add('hidden');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>