<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link rel="shortcut icon" href="{{asset('img/shorticon.png')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/admin.min.css') }}" rel="stylesheet"> --}}
    <link rel="shortcut icon" href="{{asset('img/shorticon.png')}}">
    <link href="/css/app.css" rel="stylesheet">
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="/css/admin.min.css" rel="stylesheet">
</head>
<body class="light" @auth id="page-top" @endauth>

@auth
<div id="wrapper">

    <ul class="navbar-nav admin-sidebar sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('dashboard')}}l">
            <div class="sidebar-brand-text mx-3">HERMES</div>
        </a>

        <hr class="sidebar-divider mb-4">

        <div class="sidebar-heading">
            Inventory
        </div>

        <li class="nav-item @if(Route::currentRouteName() == 'dashboard') active @endif">
            <a class="nav-link" href="{{route('dashboard')}}">
                <i class="far fa-chart-bar"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item @if(Route::currentRouteName() == 'sales') active @endif">
            <a class="nav-link"  href="{{route('sales')}}">
                <i class="far fa-chart-bar"></i>
                <span>Sales Module</span>
            </a>
        </li>
        <li class="nav-item @if(Route::currentRouteName() == 'item.logs') active @endif">
            <a class="nav-link"  href="{{route('item.logs')}}">
                <i class="fas fa-truck"></i>
                <span>Item Logs</span> <span class="badge bg-secondary" id="delivery_count">0</span>
            </a>
        </li>
        
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            Manage
        </div>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#itemsCollapse"
                aria-expanded="true" aria-controls="itemsCollapse">
                <i class="fas fa-box-open"></i>
                <span>Manage Items</span>
            </a>
            <div id="itemsCollapse" class="collapse @if(Route::currentRouteName() == 'items' || Route::currentRouteName() == 'transfer.in' || Route::currentRouteName() == 'deduct.items' || Route::currentRouteName() == 'item.quantity.check') show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-secondary py-2 collapse-inner rounded">
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'items' || Route::currentRouteName() == 'edit.item') active @endif" href="{{route('items')}}">
                        <i class="fas fa-boxes mr-3 text-tertiary"></i>
                        <span>Items</span>
                    </a>
                    <a class="collapse-item text-white   @if(Route::currentRouteName() == 'item.quantity.check') active @endif" href="{{route('item.quantity.check')}}">
                        <i class="fas fa-tasks mr-2 text-tertiary"></i>
                        <span>Item Quantity Check</span>
                    </a>
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'transfer.in') active @endif" href="{{route('transfer.in')}}">
                        <i class="fas fa-share mr-2 text-tertiary"></i>
                        <span>Transfer In</span>
                    </a>
                    <a class="collapse-item text-white   @if(Route::currentRouteName() == 'deduct.items') active @endif" href="{{route('deduct.items')}}">
                        <i class="fas fa-reply mr-2 text-tertiary"></i>
                        <span>Transfer Out</span>
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#categoryCollapse"
                aria-expanded="true" aria-controls="categoryCollapse">
                <i class="fas fa-box-open"></i>
                <span>Manage Category</span>
            </a>
            <div id="categoryCollapse" class="collapse @if(Route::currentRouteName() == 'categories' || Route::currentRouteName() == 'sub.categories') show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-secondary py-2 collapse-inner rounded">
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'categories') active @endif" href="{{route('categories')}}"><i class="fas fa-sitemap mr-2 text-tertiary"></i><span>Category</span></a>
                    <a class="collapse-item text-white  @if(Route::currentRouteName() == 'sub.categories') active @endif" href="{{route('sub.categories')}}"><i class="fas fa-sitemap mr-2 text-tertiary"></i><span>Sub Category</span></a>
                </div>
            </div>
        </li>
        @if(Auth::user()->role == 'Admin')
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#usersCollapse"
                aria-expanded="true" aria-controls="usersCollapse">
                <i class="fas fa-users"></i>
                <span>User Management</span>
            </a>
            <div id="usersCollapse" class="collapse @if(Route::currentRouteName() == 'user' || Route::currentRouteName() == 'add.user' || Route::currentRouteName() == 'edit.user') show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-secondary py-2 collapse-inner rounded">
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'user' || Route::currentRouteName() == 'edit.user') active @endif" href="{{route('user')}}"><i class="fas fa-user mr-2 text-tertiary"></i><span>Users</span></a>
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'add.user') active @endif" href="{{route('add.user')}}"><i class="fas fa-user-plus mr-2 text-tertiary"></i><span>Add New User</span></a>
                </div>
            </div>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#reportsCollapse"
                aria-expanded="true" aria-controls="reportsCollapse">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Reports</span>
            </a>
            <div id="reportsCollapse" class="collapse @if(Route::currentRouteName() == 'transfered.in.report' || Route::currentRouteName() == 'transfered.out.report' || Route::currentRouteName() == 'delivery.report' || Route::currentRouteName() == 'sales.report' || Route::currentRouteName() == 'revenue.report' || Route::currentRouteName() == 'inventory.report') show @endif" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-secondary py-2 collapse-inner rounded">
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'inventory.report') active @endif" href="{{route('inventory.report')}}">
                        <i class="fas fa-calendar-check mr-2 text-tertiary"></i>
                        <span>Inventory Report</span>
                    </a>
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'revenue.report') active @endif" href="{{route('revenue.report')}}">
                        <i class="fas fa-calendar-check mr-2 text-tertiary"></i>
                        <span>Revenue Report</span>
                    </a>
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'transfered.in.report') active @endif" href="{{route('transfered.in.report')}}">
                        <i class="fas fa-calendar-check mr-2 text-tertiary"></i>
                        <span>Transfered In</span>
                    </a>
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'transfered.out.report') active @endif" href="{{route('transfered.out.report')}}">
                        <i class="fas fa-calendar-check mr-2 text-tertiary"></i>
                        <span>Transfered Out</span>
                    </a>
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'delivery.report') active @endif" href="{{route('delivery.report')}}">
                        <i class="fas fa-calendar-check mr-2 text-tertiary"></i>
                        <span>Delivery Report</span>
                    </a>
                    <a class="collapse-item text-white mb-1  @if(Route::currentRouteName() == 'sales.report') active @endif" href="{{route('sales.report')}}">
                        <i class="fas fa-calendar-check mr-2 text-tertiary"></i>
                        <span>Sales Report</span>
                    </a>
                </div>
            </div>
        </li>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <nav class="navbar navbar-expand navbar-light admin-nav topbar mb-4 static-top shadow">

                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <ul class="navbar-nav ml-auto">

                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-white-600 small">{{Auth::user()->name}}</span>
                            <img class="img-profile rounded-circle"
                                src="{{asset('img/admin-avatar.png')}}">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in shadow"
                            aria-labelledby="userDropdown">
                            <div class="dropdown-divider gray-900"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-tertiary"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-light text-capitalize">{{str_replace("."," ", Route::currentRouteName())}}</h1>
                </div>
                {{-- errors MESSAGE --}}
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- success MESSAGE --}}
                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-4" role="alert">
                    <i class="fas fa-check-circle fa-lg"></i>
                    <div>
                        {{ session()->get('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- error MESSAGE --}}
                @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-4" role="alert">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                    <div>
                        {{ session()->get('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @yield('content')
            
            </div>

        </div>

        </div>

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-body text-center my-4">
            <i class="far fa-question-circle logout-info"></i>
            <h5>Ready to Leave?</h5>
            <a class="btn text-light mt-4" href="#" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"><i class="fas fa-undo-alt mr-2"></i><span>Logout</span></a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
    </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    @if(Route::currentRouteName() == 'dashboard')
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script>
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example: number_format(1234.56, 2, ',', ' ');
        // *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
        }

        // Area Chart Example
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
            label: "Sales",
            lineTension: 0.3,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "rgba(78, 115, 223, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "rgba(78, 115, 223, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: [{{$salesArr[1]}}, {{$salesArr[2]}}, {{$salesArr[3]}}, {{$salesArr[4]}}, {{$salesArr[5]}}, {{$salesArr[6]}}, {{$salesArr[7]}}, {{$salesArr[8]}}, {{$salesArr[9]}}, {{$salesArr[10]}}, {{$salesArr[11]}}, {{$salesArr[12]}}],
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
            },
            scales: {
            xAxes: [{
                time: {
                unit: 'date'
                },
                gridLines: {
                display: false,
                drawBorder: false
                },
                ticks: {
                maxTicksLimit: 7
                }
            }],
            yAxes: [{
                ticks: {
                maxTicksLimit: 5,
                padding: 10,
                // Include a dollar sign in the ticks
                callback: function(value, index, values) {
                    return '$' + number_format(value);
                }
                },
                gridLines: {
                color: "rgb(234, 236, 244)",
                zeroLineColor: "rgb(234, 236, 244)",
                drawBorder: false,
                borderDash: [2],
                zeroLineBorderDash: [2]
                }
            }],
            },
            legend: {
            display: false
            },
            tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
            callbacks: {
                label: function(tooltipItem, chart) {
                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                }
            }
            }
        }
        });

    </script>
    @endif
    <script src="{{asset('vendor/axios/axios.min.js')}}"></script>
    <script src="{{asset('vendor/sweetalert/sweetalert.min.js')}}"></script>
    <script>
        if(document.querySelector('.alert .btn-close')){
                setTimeout(() => {
                    document.querySelector('.alert .btn-close').click()
                }, 4000);
            }
            getDeliveryCount()
        

        async function getDeliveryCount(){
            let delivery_count = document.getElementById('delivery_count');

            await axios.get('/get-delivery-count')
                .then(function (response) {
                    if(response.status == 200){
                        delivery_count.innerHTML = response.data.count;
                    }
                })
                .catch(function (error) {
                    delivery_count.innerHTML = error.response.data.count;
                })
        }
    </script>
    @yield('script')

    @else
        @yield('content')
    @endauth
</body>
</html>
