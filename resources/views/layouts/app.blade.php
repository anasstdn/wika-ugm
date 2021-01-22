<!doctype html>
<html lang="en" class="no-focus">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>WIKA Gedung</title>

    <meta name="description" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="Codebase">
    <meta property="og:description" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{asset('codebase/')}}/src/assets/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('codebase/')}}/src/assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('codebase/')}}/src/assets/media/favicons/apple-touch-icon-180x180.png">
    <!-- END Icons -->

    <!-- Stylesheets -->

    <!-- Fonts and Codebase framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{asset('codebase/')}}/src/assets/css/codebase.min.css">

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
    <link rel="stylesheet" href="{{asset('codebase/')}}/src/assets/js/plugins/datatables/dataTables.bootstrap4.css">
    <link rel="stylesheet" id="css-theme" href="{{asset('codebase/')}}/src/assets/css/themes/corporate.min.css">
    <link rel="stylesheet" href="{{asset('codebase/')}}/src/assets/js/plugins/select2/css/select2.min.css">
    {{-- <link href="{{asset('codebase/')}}/build/toastr.css" rel="stylesheet" type="text/css" /> --}}
    <link rel="stylesheet" href="{{asset('codebase/')}}/src/assets/js/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="{{asset('css/')}}/bootstrap-table.css">

    <link rel="stylesheet" href="{{asset('codebase/')}}/src/assets/js/plugins/flatpickr/flatpickr.min.css">

    <link rel="stylesheet" href="{{asset('codebase/')}}/src/assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">

    <!-- END Stylesheets -->
</head>
<style>
  .ajax-loader{
    position:fixed;
    top:0px;
    right:0px;
    width:100%;
    height:auto;
    background-color:#A9A9A9;
    background-repeat:no-repeat;
    background-position:center;
    z-index:10000000;
    opacity: 0.4;
    filter: alpha(opacity=40); /* For IE8 and earlier */
  }

  .new-notification {
    animation-name: new-row;
    animation-duration: 5s;
  }

  @keyframes new-row {
    0% {
      background-color: rgba(132, 220, 207,.5);
    }
    50% {
      background-color: rgba(132, 220, 207,.5);
    }
    100% {
      background-color: rgba(255,255,255,0);
    }

  }

</style>
<body>

    <!-- Page Container -->
        <!--
            Available classes for #page-container:

        GENERIC

            'enable-cookies'                            Remembers active color theme between pages (when set through color theme helper Template._uiHandleTheme())

        SIDEBAR & SIDE OVERLAY

            'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
            'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
            'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
            'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
            'sidebar-inverse'                           Dark themed sidebar

            'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
            'side-overlay-o'                            Visible Side Overlay by default

            'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

            'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

        HEADER

            ''                                          Static Header if no class is added
            'page-header-fixed'                         Fixed Header

        HEADER STYLE

            ''                                          Classic Header style if no class is added
            'page-header-modern'                        Modern Header style
            'page-header-inverse'                       Dark themed Header (works only with classic Header style)
            'page-header-glass'                         Light themed Header with transparency by default
                                                        (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
            'page-header-glass page-header-inverse'     Dark themed Header with transparency by default
                                                        (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

        MAIN CONTENT LAYOUT

            ''                                          Full width Main Content if no class is added
            'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
            'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)
        -->
        <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-fixed page-header-glass page-header-inverse sidebar-inverse main-content-boxed">

            @include('layouts.sidebar')

            @include('layouts.header')

            <!-- Main Container -->
            <main id="main-container">
               <meta name="csrf-token" content="{{ csrf_token() }}">
               <div class="main-content">
                  @yield('content')
              </div>
          </main>
          <!-- END Main Container -->

          <!-- Footer -->
          <footer id="page-footer" class="bg-white opacity-0">
            <div class="content py-20 font-size-sm clearfix">
                <div class="float-right">
                    Crafted with <i class="fa fa-heart text-pulse"></i> by <a class="font-w600" href="https://1.envato.market/ydb" target="_blank">pixelcave</a>
                </div>
                <div class="float-left">
                    <a class="font-w600" href="https://1.envato.market/95j" target="_blank">Codebase 3.3</a> &copy; <span class="js-year-copy"></span>
                </div>
            </div>
        </footer>
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->

        <!--
            Codebase JS Core

            Vital libraries and plugins used in all pages. You can choose to not include this file if you would like
            to handle those dependencies through webpack. Please check out assets/_es6/main/bootstrap.js for more info.

            If you like, you could also include them separately directly from the assets/js/core folder in the following
            order. That can come in handy if you would like to include a few of them (eg jQuery) from a CDN.

            assets/js/core/jquery.min.js
            assets/js/core/bootstrap.bundle.min.js
            assets/js/core/simplebar.min.js
            assets/js/core/jquery-scrollLock.min.js
            assets/js/core/jquery.appear.min.js
            assets/js/core/jquery.countTo.min.js
            assets/js/core/js.cookie.min.js
        -->
        <script src="{{asset('codebase/')}}/src/assets/js/codebase.core.min.js"></script>

        <!--
            Codebase JS

            Custom functionality including Blocks/Layout API as well as other vital and optional helpers
            webpack is putting everything together at assets/_es6/main/app.js
        -->
        <script src="{{asset('codebase/')}}/src/assets/js/codebase.app.min.js"></script>

        <!-- Page JS Plugins -->
        <script src="{{asset('codebase/')}}/src/assets/js/plugins/chartjs/Chart.bundle.min.js"></script>

        <!-- Page JS Code -->
        <script src="{{asset('codebase/')}}/src/assets/js/pages/db_corporate.min.js"></script>
        <script src="{{asset('codebase/')}}/src/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="{{asset('codebase/')}}/src/assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="{{asset('codebase/')}}/src/assets/js/pages/be_tables_datatables.min.js"></script>
        <script src="{{asset('codebase/')}}/src/assets/js/plugins/select2/js/select2.full.min.js"></script>
        <script src="{{asset('js/')}}/bootstrap-table.js"></script>

        <script src="{{asset('codebase/')}}/src/assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="{{asset('codebase/')}}/src/assets/js/plugins/jquery-validation/additional-methods.js"></script>
        {{-- <script src="{{asset('codebase/')}}/build/toastr.min.js"></script> --}}
        <script src="{{asset('codebase/')}}/src/assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
        <script src="{{asset('codebase/')}}/src/assets/js/plugins/es6-promise/es6-promise.auto.min.js"></script>
        <script src="{{asset('codebase/')}}/src/assets/js/plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="https://js.pusher.com/5.0/pusher.min.js"></script>
        <script src="{{asset('codebase/')}}/src/assets/js/pages/be_ui_activity.min.js"></script>

        <script src="{{asset('codebase/')}}/src/assets/js/plugins/flatpickr/flatpickr.min.js"></script>

        <script src="{{asset('js/')}}/moment.min.js"></script>

        <script src="{{asset('codebase/')}}/src/assets/js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>

         <script src="{{asset('codebase/')}}/src/assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

        <script src="{{asset('codebase/')}}/src/assets/js/plugins/bootstrap-wizard/jquery.bootstrap.wizard.js"></script>

        <script src="{{asset('js/')}}/AppendGrid.js"></script>

        <script>jQuery(function(){ Codebase.helpers(['maxlength']); });</script>

        <script type="text/javascript">
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            notification("{!! $error !!}","gagal");
            @endforeach
            @endif
            @if(Session::get('messageType'))
            notification("{!! Session::get('message') !!}","{!! Session::get('messageType') !!}");
            <?php
            Session::forget('messageType');
            Session::forget('message');
            ?>
            @endif

            function notification(message,type)
            {
              if(type=='sukses')
              {
                $.notify({
                  align: 'right',             
                  from: 'top',                       
                  icon: 'fa fa-info mr-5',    
                  message: message
                },{
                  type: 'success',       
                });
            }
            else
            {
              $.notify({
                align: 'right',             
                from: 'top',                             
                icon: 'fa fa-info mr-5',    
                message: message
              },
              {
                type: 'warning', 
              });
        }
    }

    function reset(url)
    {
      var token = $("meta[name='csrf-token']").attr("content");
      let toast = Swal.mixin({
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-alt-success m-5',
                cancelButton: 'btn btn-alt-danger m-5',
                input: 'form-control'
            }
        });
    toast.fire({
      title: 'Reset Password?',
      text: 'Apakah anda yakin?',
      icon: 'warning',
      showCancelButton: true,
      customClass: {
        confirmButton: 'btn btn-alt-danger m-1',
        cancelButton: 'btn btn-alt-secondary m-1'
      },
      confirmButtonText: 'Ya',
      cancelButtonText: 'Tidak',
      html: false,
    }).then(result => {
      if (result) {
       $.ajax({
        url : url,
        type: 'GET',
        headers: {
          'X-CSRF-TOKEN': token
        },
        success:function(){
          toast.fire('Reset!', 'Password berhasil direset.', 'success');
          setTimeout(function() {
            location.reload();
          }, 1000);
        },
      });
     } else if (result.dismiss === 'cancel') {
      toast.fire('Batal', 'Data batal dihapus', 'error');
    }
  });
  }

  function hapus(url)
  {
    var token = $("meta[name='csrf-token']").attr("content");
    let toast = Swal.mixin({
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-alt-success m-5',
                cancelButton: 'btn btn-alt-danger m-5',
                input: 'form-control'
            }
        });
    toast.fire({
      title: 'Apakah anda yakin?',
      text: 'File tidak akan terhapus total!',
      icon: 'warning',
      showCancelButton: true,
      customClass: {
        confirmButton: 'btn btn-alt-danger m-1',
        cancelButton: 'btn btn-alt-secondary m-1'
      },
      confirmButtonText: 'Ya',
      cancelButtonText: 'Tidak',
      html: false,
    }).then(result => {
      if (result) {
       $.ajax({
        url : url,
        type: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': token
        },
        success:function(){
          toast.fire('Dihapus!', 'File berhasil dihapus.', 'success');
          setTimeout(function() {
            location.reload();
          }, 1000);
        },
      });
     } else if (result.dismiss === 'cancel') {
      toast.fire('Baral', 'Data batal dihapus', 'error');
    }
  });
  }

  function show_modal(url) { 
      $.ajax({
        url:url,
        dataType: 'text',
        success: function(data) {
          $('.modal').appendTo("body");
          $("#formModal").html(data);
          $("#formModal").modal('show');
      }
  });
  };

  function uploadProgressHandler(event) {
    // $("#loaded_n_total").html("Uploaded " + event.loaded + " bytes of " + event.total);
    var percent = (event.loaded / event.total) * 100;
    var progress = Math.round(percent);
    $("#percent").html(progress + "%");
    $(".progress-bar").css("width", progress + "%");
    $("#status").html(progress + "% uploaded... please wait");
}

function loadHandler(event) {
    $("#status").html('Load Completed');
    setTimeout(function(){
      $('.ajax-loader').fadeOut()
      $("#percent").html("0%");
      $(".progress-bar").css("width", "100%");
  }, 500);
}

function errorHandler(event) {
    $("#status").html("Send Data Failed");
}

function abortHandler(event) {
    $("#status").html("Send Data Aborted");
}

function clicked(e)
{
  if(!confirm('Apakah anda yakin untuk melanjutkan ke proses selanjutnya?')) {
    e.preventDefault();
  }
}


</script>
<script>
  Pusher.logToConsole = false;

  var notificationsWrapper   = $('.notifications');
  var notificationsToggle    = notificationsWrapper.find('button[data-toggle]');
  var notificationsCountElem = notificationsToggle.find('span[data-count]');
  var notificationsCount     = parseInt(notificationsCountElem.data('count'));
  var notifications          = notificationsWrapper.find('ul.content-notif');

  var pusher = new Pusher('d32a5c0e169aaa4f1287', {
    cluster: 'ap1'
  });

  var channel = pusher.subscribe('{{ Auth::user()->id }}');
  channel.bind('send-message', function(data) {
    var existingNotifications = notifications.html();
    var newNotificationHtml = `
    <li class="new-notification">
    <a class="text-body-color-dark media mb-15" href="{{url('/')}}/`+data.url+`">
    <div class="ml-5 mr-15">
    <i class="fa fa-fw fa-exclamation-triangle text-warning"></i>
    </div>
    <div class="media-body pr-10">
    <p class="mb-0" style="font-size:10pt"><b>`+data.title+`</b></p>
    <p class="mb-0" style="font-size:9pt">`+data.content+`</p>
    <div class="text-muted font-size-sm font-italic">15 min ago</div>
    </div>
    </a>
    </li>
    `;
    notifications.html(newNotificationHtml + existingNotifications);
    notificationsCount += 1;
    notificationsCountElem.attr('data-count', notificationsCount);
    notificationsCountElem.text(notificationsCount);

    notificationsWrapper.show();
  });
</script>

@yield('js')  
@stack('js')
</body>
</html>