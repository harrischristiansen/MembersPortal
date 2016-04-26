<?php $__env->startSection("content"); ?>

<div class="titlePage">
	<p class="titlePageSub">Members</p>
	<h1 class="titlePageTitle">Purdue Hackers</h1>
	
	<form method="post" action="/login">
		<?php echo csrf_field(); ?>

		<input type="password" name="password" id="password" placeholder="Password">
		<input type="submit" value="Sign In">
	</form>
	
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("app", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>