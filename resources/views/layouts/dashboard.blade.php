<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <!-- Required meta tags-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Title Page-->
        <title>Diamond Insurance Broker </title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" href="{{ asset('Images/icon.png') }}" type="image/x-icon">
        <link rel="icon" href="{{ asset('Images/icon.png') }}" type="image/x-icon">

        <!-- Fontfaces CSS-->
        <link href="{{ asset('css/font-face.css') }}" rel="stylesheet" media="all">
        <link href=" {{ asset('vendor/font-awesome-4.7/css/font-awesome.min.css') }}" rel="stylesheet" media="all">
        <link href="{{ asset('vendor/font-awesome-5/css/fontawesome-all.min.css') }}" rel="stylesheet" media="all">
        <link href="{{ asset('vendor/mdi-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet" media="all">

        <!-- Bootstrap CSS-->
        <link href="{{ asset('vendor/bootstrap-4.1/bootstrap.min.css') }}" rel="stylesheet" media="all">

        <!-- Vendor CSS-->
        <link href="{{ asset('vendor/animsition/animsition.min.css') }}" rel="stylesheet" media="all">
        <link href="{{ asset('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet" media="all">
        <link href="{{ asset('vendor/wow/animate.css') }}" rel="stylesheet" media="all">
        <link href="{{ asset('vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" media="all">
        <link href="{{ asset('vendor/slick/slick.css') }}" rel="stylesheet" media="all">
        <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
        <link href="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" media="all">

        <!-- Main CSS-->
        <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
        <link href="{{ asset('css/theme.css') }}" rel="stylesheet" media="all">


        <!-- Jquery JS-->
        <script src="{{ asset('js/main2.js') }}"></script>
        <script src="{{ asset('js/locale.js') }}"></script> 
        <script src="{{ asset('js/main1.js') }}"></script> 
        <script src="{{ asset('js/global/Underscore.min.js') }}"></script>
        <script src="{{ asset('js/global/jquery-ui.js') }}"></script>
        <script src="{{ asset('js/global/jquery-dateformat.js') }}"></script>

        <style>
            .iframe-container {
                overflow: hidden;
                // Calculated from the aspect ration of the content (in case of 16:9 it is 9/16= 0.5625)
                padding-top: 56.25%!important ;
                position: relative!important;
                max-height:1750 !important;
                min-height:1000px !important;
            }

            .iframe-class {
                border: 0!important;
                height: 100%!important;
                left: 0!important;
                position: absolute!important;
                top: 0!important;
                width: 100%!important;
            }
            iframe{
                overflow:hidden;
            }
        </style>


    </head>

    <body class="animsition">
        <div class="page-wrapper">
            <!-- MENU SIDEBAR-->
            <aside class="menu-sidebar2">
                <div class="logo">
                    <a href="#">
                        <img src="{{ asset('Images/Companyheaderlogo.jpg') }}" alt="Diamond Insurance Broker" />
                    </a>
                </div>
                <div class="menu-sidebar2__content js-scrollbar1">
                    <div class="account2">
                        <div class="image img-cir img-120">
                            <img src="{{ asset('Images/avatar.jpg') }}" alt="{{ Auth::user()->name }}" />
                        </div>
                        <h4 class="name">{{ Auth::user()->name }}</h4>
                        <a href="{{ route('mainlogout') }}">Sign out</a>
                    </div>
                    <nav class="navbar-sidebar2">
                        <ul class="list-unstyled navbar__list">

                            @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles) || in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES', Auth::user()->roles))
                            <li class="has-sub active">
                                <a class="js-arrow" href="#">
                                    <i class="fas fa-tachometer-alt"></i>Request
                                    <span class="arrow">
                                        <i class="fas fa-angle-down"></i>
                                    </span>
                                </a>
                                <ul class="list-unstyled navbar__sub-list js-sub-list">
                                    @foreach($sidemenustatus as $key=>$statusvalue)
                                    <li id='status_{{$key}}'>
                                        <a href="{{route('dashboardrequestfilter',[$key]) }}">
                                            <i class="fas fa-tachometer-alt"></i>{{$statusvalue}}</a>
                                        @foreach($countDetails as $index=>$countValue)  
                                        @if($countValue->status ==$key)
                                        <span class="inbox-num">{{$countValue->count}}</span>
                                        @endif

                                        @endforeach
                                    </li>
                                    @endforeach



                                </ul>
                            </li>
                            @endif

                            <li> 
                                <a href="{{ route('dashboardcustomers') }}">
                                    <i class="fas fa-shopping-basket"></i>Customers</a>
                            </li>
                            
                            @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles))   
                            <li> 
                                <a href="{{ route('technicalPolicyDetails') }}">
                                    <i class="fas fa-calendar-alt"></i>Policy</a>
                            </li>
                            <li> 
                                <a href="{{ route('dashboardendorsementlist') }}">
                                    <i class="fas fa-shopping-basket"></i>Endorsement request</a>
                            </li>

                            @endif
                            

                            @if (in_array('ROLE_OPERATION_MANAGER', Auth::user()->roles) || in_array('ROLE_OPERATION', Auth::user()->roles))
                            <li> 
                                <a href="{{ route('dashboardendorsementlist') }}">
                                    <i class="fas fa-shopping-basket"></i>Endorsement request</a>
                            </li>

                            <li> 
                                <a href="{{ route('dashboardcomplaintlist') }}">
                                    <i class="fas fa-shopping-basket"></i>Complaints</a>
                            </li>
                            <li > 
                                <a href="{{ route('claimlist') }}">
                                    <i class="fas fa-shopping-basket"></i>Claims</a>
                            </li>
                            @endif
                            @if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES', Auth::user()->roles))   
                            <li> 
                                <a href="{{ route('leads') }}">
                                    <i class="fas fa-shopping-basket"></i>Leads</a>
                            </li>
                            <li> 
                                <a href="{{ route('salescrmlist') }}">
                                    <i class="fas fa-shopping-basket"></i>Crm request</a>
                            </li>

                            @endif

                            @if (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles))   
                            
                            <li class="has-sub">
                                <a class="js-arrow" href="#">
                                    <i class="fas fa-tachometer-alt"></i>Production
                                    <span class="arrow">
                                        <i class="fas fa-angle-down"></i>
                                    </span>
                                </a>
                                <ul class="list-unstyled navbar__sub-list js-sub-list">
                            
                            
                            <li> 
                                <a href="{{ route('dashboardfinancepolicylist') }}">
                                    <i class="fas fa-calendar-alt"></i>Policy</a>
                            </li>
                            <li> 
                                <a href="{{ route('financeendorsementlist') }}">
                                    <i class="fas fa-calendar-alt"></i>Endorsement</a>
                            </li>
                                </ul>
                            </li>
                            
                            <li class="has-sub">
                                <a class="js-arrow" href="#">
                                    <i class="fas fa-tachometer-alt"></i>Collection
                                    <span class="arrow">
                                        <i class="fas fa-angle-down"></i>
                                    </span>
                                </a>
                                <ul class="list-unstyled navbar__sub-list js-sub-list">
                            
                            
                            <li> 
                                <a href="{{ route('invoicelist') }}">
                                    <i class="fas fa-calendar-alt"></i>Invoices</a>
                            </li>
                            <li> 
                                <a href="{{ route('invoicepayment') }}">
                                    <i class="fas fa-calendar-alt"></i>Payments</a>
                            </li>
                                </ul>
                            </li>

                            @endif

                            <li> 
                                <a href="{{ route('appointmentlist') }}">
                                    <i class="fas fa-calendar-alt"></i>Appointments</a>
                            </li>



                        </ul>
                    </nav>
                </div>
            </aside>
            <!-- END MENU SIDEBAR-->

            <!-- PAGE CONTAINER-->
            <div class="page-container2">
                <!-- HEADER DESKTOP-->
                <header class="header-desktop2">
                    <div class="section__content section__content--p30">
                        <div class="container-fluid">
                            <div class="header-wrap2">
                                <div class="logo d-block d-lg-none">
                                    <a href="#">
                                        <img src="{{ asset('Images/Companyheaderlogo.jpg') }}" alt="Diamond Insurance Broker" />
                                    </a>
                                </div>
                                <div class="header-wrap">
                                    <div class="header-button">
                                        <div class="noti-wrap">
                                            <div class="noti__item js-item-menu">
                                                <i class="zmdi zmdi-notifications"></i>
                                                <span class="quantity">{{$notificationCount}}</span>

                                            </div> 
                                        </div>

                                    </div>

                                </div>
                                <div class="header-button2">
                                    <div class="header-button-item js-item-menu">


                                    </div>
                                    <div class="header-button-item has-noti js-item-menu">

                                        <div class="notifi-dropdown js-dropdown">

                                        </div>
                                    </div>
                                    <div class="header-button-item mr-0 js-sidebar-btn">
                                        <i class="zmdi zmdi-menu"></i>
                                    </div>
                                    <div class="setting-menu js-right-sidebar d-none d-lg-block">
                                        <div class="account-dropdown__body">
                                            <div class="account-dropdown__item">
                                                <a href="#">
                                                    <i class="zmdi zmdi-account"></i>Account</a>
                                            </div>
                                            <div class="account-dropdown__item">
                                                <a href="#">
                                                    <i class="zmdi zmdi-settings"></i>Setting</a>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <aside class="menu-sidebar2 js-right-sidebar d-block d-lg-none">
                    <div class="logo">
                        <a href="#">
                            <img src="{{ asset('Images/Companyheaderlogo.jpg') }}" alt="Diamond Insurance Broker" />
                        </a>
                    </div>
                    <div class="menu-sidebar2__content js-scrollbar2">
                        <div class="account2">
                            <div class="image img-cir img-120">
                                <img src="{{ asset('Images/avatar.jpg') }}" alt="{{ Auth::user()->name }}" />
                            </div>
                            <h4 class="name">{{ Auth::user()->name }}</h4>
                            <a href="{{ route('mainlogout') }}">Sign out</a>
                        </div>

                    </div>
                </aside>
                <!-- END HEADER DESKTOP-->

                <section class="au-breadcrumb">

                </section>
                @include('layouts.flash-message')
                @yield('content')
                <!-- BREADCRUMB-->

                <section>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="copyright">
                                    <p>Copyright © {{ date('Y') }} &nbsp;  | &nbsp; Development: <a target="_blank" href="http://dbroker.com.sa">Diamond Insurance Broker</a> &nbsp; | &nbsp; Technical support: <a href="mailto:info@dbroker.com.sa">info@dbroker.com.sa</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- END PAGE CONTAINER-->
            </div>

        </div>


        <!-- Bootstrap JS-->
    <!--    <script src="{{ asset('vendor/bootstrap-4.1/popper.min.js') }} "></script>
        <script src="{{ asset('vendor/bootstrap-4.1/bootstrap.min.js') }} "></script>-->
        <!-- Vendor JS       -->
        <script src="{{ asset('vendor/slick/slick.min.js') }}">
        </script>
        <script src="{{ asset('vendor/wow/wow.min.js') }} "></script>
        <script src="{{ asset('vendor/animsition/animsition.min.js') }} "></script>
        <script src="{{ asset('vendor/bootstrap-progressbar/bootstrap-progressbar.min.js') }} ">
        </script>
        <script src="{{ asset('vendor/counter-up/jquery.waypoints.min.js') }} "></script>
        <script src="{{ asset('vendor/counter-up/jquery.counterup.min.js') }} ">
        </script>
        <script src="{{ asset('vendor/circle-progress/circle-progress.min.js') }} "></script>
        <script src="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.js') }} "></script>
        <script src="{{ asset('vendor/chartjs/Chart.bundle.min.js') }} "></script>
        <script src="{{ asset('vendor/select2/select2.min.js') }} "></script>   


        <!-- Main JS-->
        <script src=" {{ asset('js/dashboard.js') }} "></script>


    </body>

</html>
<!-- end document-->
