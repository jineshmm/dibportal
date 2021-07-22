
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
            
      
            <main id="page-policy" class="container-fluid">
                 @yield('overviewmenu')
                
                <h1>@isset($title) {{$title}}  @endisset</h1>
                <div class="panel-heading">
                        <ul class="panel-actions list-inline pull-right">
                            <li style="border-left:none"><span class="panel-action-download" onclick="" data-toggle="tooltip" title="" data-original-title="back"><a class="fas fa-reply dp-insly-color" onclick='window.history.back();'>Back</a></span></li>
                            <li class=""><span class="panel-action-settings customersetting"  data-toggle="tooltip" title="" data-original-title="Dashboard"><a class="fas fa-home dp-insly-color" href="{!! route('dashboard') !!}">Home</a></span></li>
                            
                        </ul>
                    
                    </div>
               
                <div class="panel panel-default open">
                    
                     @yield('headerContent')
            
                    
                    
                   
  

                     
                    <div class="panel-body content-area">
                       
                         @include('layouts.flash-message')
                        @yield('content')

                    </div>

                </div>



            </main>
        </div>

<link type="text/css" href="{{ asset('css/font-awesome.min.css') }}" type="text/css" rel="stylesheet" />
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css' >
      
    </body>
</html>
