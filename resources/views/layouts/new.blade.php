
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Diamond Insurance Broker </title>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="MSSmartTagsPreventParsing" content="true">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <meta name="msapplication-TileColor" content="#ffffff">
        <link rel="shortcut icon" href="{{ asset('Images/icon.png') }}" />
        <link rel="stylesheet" href="{{ asset('css/dibstyle.css') }}">
<!--          <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
        
        <script src="{{ asset('js/main2.js') }}"></script>
        <script src="{{ asset('js/locale.js') }}"></script> 
        <script src="{{ asset('ckeditor/ckeditor.js') }}" type="text/javascript"></script>
       <script src="{{ asset('ckeditor/adapters/jquery.js') }}" type="text/javascript"></script>
<!--        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
        <script src="{{ asset('js/global/Underscore.min.js') }}"></script>
<!--        <script src="{{ asset('js/shim.latest.js') }}"></script>-->
        <script src="{{ asset('js/main1.js') }}"></script>
        
        
        
      
        <!--[if gte IE 9]>
        <style type="text/css">
            .gradient {
                filter: none;
            }
        </style>
        <![endif]-->
    </head>
    <body class="">

        <div id="main">
            <header>
                <div class="pull-left">
                    <a href="{{ route('dashboard') }}"><img alt="logo" src="{{ asset('Images/Companyheaderlogo.jpg') }}" id="logo"></a>
                </div>
                <div id="header-menu">
                    <div id="header-settings">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <span class="icon-settings"></span>
                                <span class="icon-active" style="position:absolute;top:10px;left:0px;"></span>
                            </button>
                            <ul id="settings-menu" class="dropdown-menu" role="menu">
                                <li><a href="/usersettings">My Settings</a></li>
                                <li><a href="/admin">System Management</a></li>
                                <li><a href="/admin/recurringbilling">Billing credentials</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ route('mainlogout') }}">Log out</a></li>
                            </ul>
                        </div>
                    </div>

                    <div id="header-logged-in-user">
                        <span  style="width:33px !important;height:33px">&nbsp;</span>                
        <!-- <img src="/usersettings/image/10018478/h=33/" width="33" height="33"> --> 
                        <div id="user-info" class="nowrap">
                            <strong>{{ Auth::user()->name }}</strong>
                            <small>{{ Auth::user()->email }}</small>
                        </div>
                    </div>
                </div>
            </header>
            <nav class="navbar navbar-default" role="navigation">
                <ul id="main-menu" class="nav navbar-nav pull-left">
                    <li ><a href="{{ route('dashboard') }}"><span class="icon-dashboard"></span>Home</a></li>
                    <li class='active'><a href="{{ route('listcustomerdata') }}"><span class="icon-customer"></span>Customers</a> </li>
                     @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles))  
                    <li><a href="{{ route('listpolicy') }}"><span class="icon-policy"></span>Policies</a><span class="" style="margin-left:42.5px;"></span></li>
                   
                    @endif
                </ul>

                <ul id="side-menu" class="nav navbar-nav pull-right">

                    <li id="sidemenu-add" class="hassubmenu">
                        <div class="btn-group"><button type="button" class="btn btn-nav dropdown-toggle" data-toggle="dropdown"><div><span class="icon-add"></span></div>Add<span class="icon-menu-open"></span></button>
                            <ul class="dropdown-menu settings" role="menu">
                          
                                <li><a href="{{ route('customeradd') }}">+ Add a new customer</a></li>
                                 @if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles))  
                                <li><a href='{!! route("createpolicy") !!}'>+ Add a new policy</a></li>
                                <li><a class='dpib_add_claim' id='dpib_add_claim'>+ Add a new claim</a></li>
                                @endif
                            </ul>
                            {{ Form::open(array('route' => array('addclaim'), 'name' => 'form_claim_add','id'=>'form_claim_add','files'=>'true' )) }} 
                   <input type="hidden" name="customer_id" value="0" />
                   <input type="hidden" name="policy_id" value="0" />
                   {{ Form::close() }}
                        </div></li></ul>    </nav>
            <main id="page-policy" class="container-fluid">
                 @yield('overviewmenu')
                
                <h1>@isset($title) {{$title}}  @endisset</h1>
                
               
                <div class="panel panel-default open">
                    
                     @yield('headerContent')
                    


                    <div class="panel-body content-area">
                         @include('layouts.flash-message')
                        @yield('content')

                    </div>

                </div>



            </main>
        </div>

        <footer class="container-fluid"><div class="pull-right">Copyright © {{ date('Y') }} &nbsp;  | &nbsp; Development: <a target="_blank" href="http://dbroker.com.sa">Diamond Insurance Broker</a> &nbsp; | &nbsp; Technical support: <a href="mailto:info@dbroker.com.sa">info@dbroker.com.sa</a></div></footer>
<script>
$(function(){
     $(document).off('click', '#dpib_add_claim');
                $(document).on('click', '#dpib_add_claim', function(){
                          $('#form_claim_add').submit();
                });
    
})</script>
      
    </body>
</html>
