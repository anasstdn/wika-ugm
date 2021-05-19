<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>404 - Halaman Tidak Ditemukan</title>
	<link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-16x16.png') }}" sizes="16x16"/>
	<link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-32x32.png') }}" sizes="32x32"/>
	<link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-96x96.png') }}" sizes="96x96"/>
	<link href="{{ asset('installer/css/style.min.css') }}" rel="stylesheet"/>
	@yield('style')
	<script>
		window.Laravel = <?php echo json_encode([
			'csrfToken' => csrf_token(),
			]); ?>
		</script>
	</head>
	<body>
		<div class="master">
			<div class="box">
				<div class="header">
					<h1 class="header__title">404 - Galat Sistem</h1>
				</div>
				<div class="main">
					Halaman tidak ditemukan. Silahkan klik tombol di bawah ini untuk kembali ke halaman awal.
					<br/>
					<div class="buttons">
						<a href="{{ url('/') }}" class="button button-classic">
							<i class="fa fa-home fa-fw" aria-hidden="true"></i> Kembali ke Halaman Awal
						</a>
					</div>
				</div>
			</div>
		</div>
		@yield('scripts')
		<script type="text/javascript">
			var x = document.getElementById('error_alert');
			var y = document.getElementById('close_alert');
			y.onclick = function() {
				x.style.display = "none";
			};
		</script>
	</body>
	</html>
