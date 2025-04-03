<?php $__env->startSection('title'); ?>
	VC Airports
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	
	<h1 class="fw-bold">All Airports</h1>
	<p>In those airports you can find flights by <span class="fw-bold">VC Airlines</span></p>
	<table class="table mt-3">
		<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th>City</th>
				<th>Country</th>
			</tr>
		</thead>
		<tbody>
			<?php $__currentLoopData = $airports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
						<td><?php echo e($loop->index + 1); ?></td>
						<td class="fw-bold"><?php echo e($airport->name); ?></td>
						<td><?php echo e($airport->city); ?></td>
						<td><?php echo e($airport->country); ?></td>
				</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</tbody>
	</table>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/msamokhi/Prog/UPEC/PHP/airlines_php/resources/views/airports-page.blade.php ENDPATH**/ ?>