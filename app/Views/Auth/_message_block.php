<?php if (session()->has('message')) : ?>
	<div class="alert alert-success">
		<div>
			<i class="fas fa-check-circle"></i>
			<?= session('message') ?>
		</div>
	</div>
<?php endif ?>

<?php if (session()->has('error')) : ?>
	<div class="alert alert-danger">
		<div>
			<i class="fas fa-exclamation-circle"></i>
			<?= session('error') ?>
		</div>
	</div>
<?php endif ?>

<?php if (session()->has('errors')) : ?>
	<div class="alert alert-danger">
		<div>
			<i class="fas fa-exclamation-circle"></i>
			<?php if (is_array(session('errors'))) : ?>
				<?php foreach (session('errors') as $error) : ?>
					<?= $error ?><br>
				<?php endforeach ?>
			<?php else : ?>
				<?= session('errors') ?>
			<?php endif ?>
		</div>
	</div>
<?php endif ?> 