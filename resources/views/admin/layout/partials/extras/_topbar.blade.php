{{-- topbar.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Topbar</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0.5rem 1rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .logout-icon {
            cursor: pointer;
        }
        .logout-icon:hover {
            background-color: #e2f0d9;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    {{-- Topbar --}}
    <div class="topbar">
        @if (config('layout.extras.user.display'))
            <div class="d-flex align-items-center">
                {{-- Hi, Name on md+ screens --}}
                <span class="text-muted fw-bold d-none d-md-inline me-1">Hi,</span>
                <span class="text-dark fw-bolder d-none d-md-inline me-3">
                    {{ request()->session()->get('name') }}
                </span>

                {{-- Logout Icon (Visible on all screen sizes) --}}
                <a href="{{ url('logout') }}" class="logout-icon d-flex align-items-center justify-content-center text-decoration-none" title="Logout">
                    <span class="symbol symbol-35 symbol-light-success">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <!-- Logout SVG Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 13v-2H7V8l-5 4 5 4v-3z"></path>
                                <path d="M20 3H10c-1.1 0-2 .9-2 2v4h2V5h10v14H10v-4H8v4c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"></path>
                            </svg>
                        </span>
                    </span>
                </a>
            </div>
        @endif
    </div>

    <!-- Optional Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
