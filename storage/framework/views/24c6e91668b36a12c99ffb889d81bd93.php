<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $__env->yieldContent('title'); ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
	<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-primary ps-3 pe-3">
		<a class="navbar-brand fw-bold" href="<?php echo e(url('/')); ?>">VC Airlines</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="w-100 d-flex justify-content-even" id="navbarNavAltMarkup">
			<div class="navbar-nav flex-fill">
				<a class="nav-item nav-link" href="<?php echo e(url('/airports/')); ?>">Airports</a>
				<a class="nav-item nav-link" href="#">Flights</a>
				<a class="nav-item nav-link" href="#">About</a>
			</div>
			<div class="navbar-nav">
				<a class="nav-item nav-link" href="#">Log in</a>
				<a class="nav-item nav-link" href="#">Sing up</a>
			</div>
		</div>
	</nav>
	<div class="container">
		<?php echo $__env->yieldContent('content'); ?>
	</div>
</body>
</html><?php /**PATH /home/msamokhi/Prog/UPEC/PHP/airlines_php/resources/views/layout.blade.php ENDPATH**/ ?>