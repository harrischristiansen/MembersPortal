<?php $__env->startSection("content"); ?>
<div class="container">
	<h1>Rankings</h1>
	<div class="panel panel-default">
	<table class="table table-bordered panel-body" >
	<thead>
		<tr>
			<th>name</th>
			<th>games</th>
			<th>wins</th>
			<th>win percent</th>
			<th>edit, reset, delete</th>
		</tr>
	</thead>
	<?php foreach($rankings as $team): ?>
	    <tr>
	        <td><?php echo e($team['abb']); ?>: <?php echo e($team['name']); ?></td>
	    	<td><?php echo e($team['games']); ?></td>
	    	<td><?php echo e($team['wins']); ?></td>
	        <td><?php echo e($team['win_percent']*100); ?>%</td>
	    	<td><a href="<?php echo e(URL::to('/edit-team', $team['id'])); ?>"> edit </a></td>
	    </tr>
	<?php endforeach; ?>
	</table>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("app", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>