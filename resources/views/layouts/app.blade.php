<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('build/assets/bootstrap.min.css') }}">
    </head>
    <body class="bg-light">

        <!-- Top Navbar -->
        <nav class="navbar navbar-light bg-white border-bottom px-4 py-2 mb-4">
            <div class="container-fluid justify-content-between">
                <div>
                    <strong>{{ config('app.name') }}</strong>
                </div>
                <div>
                    <span class="me-3">{{ Auth::user()->full_name ?? '' }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Container with Sidebar + Content -->
        <div class="container">
            <div class="row">
                <!-- Sidebar (4 columns) -->
                <div class="col-md-4 mb-4">
                    <div class="list-group">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action {{ request()->is('products*') ? 'active' : '' }}">
                            Products
                        </a>
                        <a href="{{ route('orders.takeaway') }}" class="list-group-item list-group-item-action {{ request()->is('orders/takeaway') ? 'active' : '' }}">
                            Place Takeaway Order
                        </a>
                        <a href="{{ route('orders.delivery') }}" class="list-group-item list-group-item-action {{ request()->is('orders/delivery') ? 'active' : '' }}">
                            Place Delivery Order
                        </a>
                        <a href="{{ route('orders.displayTakeaway') }}" class="list-group-item list-group-item-action {{ request()->is('orders/takeaway/list') ? 'active' : '' }}">
                            Display Takeaway Orders
                        </a>
                        <a href="{{ route('orders.displayDelivery') }}" class="list-group-item list-group-item-action {{ request()->is('orders/delivery/list') ? 'active' : '' }}">
                            Display Delivery Orders
                        </a>
                        <a href="{{ route('orders.takeaway.collect') }}" class="list-group-item list-group-item-action {{ request()->is('orders/takeaway/collect') ? 'active' : '' }}">
                            Collect Takeaway Order
                        </a>
                        <a href="{{ route('orders.deliveries.process') }}" class="list-group-item list-group-item-action {{ request()->is('orders/deliveries/process') ? 'active' : '' }}">
                            Process Deliveries by Distance
                        </a>
                        <a href="{{ route('orders.recent') }}" class="list-group-item list-group-item-action {{ request()->is('orders/recent') ? 'active' : '' }}">
                            View Recent Orders
                        </a>
                        <a href="{{ route('feedback.create') }}" class="list-group-item list-group-item-action {{ request()->is('feedback') ? 'active' : '' }}">
                            Leave Feedback
                        </a>
                        <a href="{{ route('feedback.index') }}" class="list-group-item list-group-item-action {{ request()->is('feedbacks') ? 'active' : '' }}">
                            Display Feedbacks
                        </a>
                        <a href="{{ route('complaints.create') }}" class="list-group-item list-group-item-action {{ request()->is('complaints') ? 'active' : '' }}">
                            Register Complaint
                        </a>
                        <a href="{{ route('complaints.index') }}" class="list-group-item list-group-item-action {{ request()->is('all-complaints') ? 'active' : '' }}">
                            View All Complaints
                        </a>
                    </div>
                </div>

                <!-- Page Content (8 columns) -->
                <div class="col-md-8">
                    {{ $slot }}
                </div>
            </div>
        </div>

    </body>
</html>
