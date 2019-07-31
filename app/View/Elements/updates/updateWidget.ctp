<?php
App::uses('CakeSession', 'Model/Datasource');
?>

<?php if (!empty($errorMessage)) : ?>
	<div class="alert alert-danger fade in">
		<i class="icon-exclamation-sign"></i>
		<strong><?php echo __('Error'); ?>:</strong> <?php echo nl2br($errorMessage); ?>
	</div>
<?php endif; ?>

<?php if (!empty($successMessage)) : ?>
	<div class="alert alert-success fade in">
		<i class="icon-exclamation-sign"></i>
		<strong><?php echo __('Info'); ?>:</strong> <?php echo $successMessage; ?>
	</div>

	<div class="alert alert-success fade in">
		<i class="icon-exclamation-sign"></i>
		<?= __('You have been logged out of the application. Click <a href="%s" style="text-decoration: underline;">here</a> to login.', Router::url(['plugin' => null, 'controller' => 'users', 'action' => 'login'])) ?>
	</div>

	<div id="update-cache-error" class="alert alert-danger fade in hidden">
		<i class="icon-exclamation-sign"></i>
		<?= __('Cleanup process that clears the cache was not successful, please clear the cache manually by running a terminal command from eramba_v2/app directory: Console/cake update deleteCache') ?>
	</div>

	<?php 
	//remove whole session
	$sessionKeys = array_keys(CakeSession::read());
	foreach ($sessionKeys as $sessionKey) {
		CakeSession::delete($sessionKey);
	}
	?>

	<script type="text/javascript">
		//delete cache
		function showCacheError() {
			$('#update-cache-error').removeClass('hidden');
		}

		App.blockUI('body');

		$.ajax({
			url: "<?= Router::url('/delete_cache.php') ?>",
		}).done(function(response) {
			if (!response) {
				showCacheError();
			}
		}).fail(function() {
			showCacheError();
		}).always(function() {
			App.unblockUI('body');
		});
	</script>
<?php endif; ?>

<div class="well well-sm">
	<strong><?php echo __('System version'); ?>:</strong> <?php echo Configure::read('Eramba.version'); ?>
</div>

<?php if ($update['response']['updates'] && !empty($update['response']['pending'])) : ?>
	<div class="well well-sm">
		<strong><?php echo __('Latest available version'); ?>:</strong> <?php echo $update['response']['latest_version']; ?>
	</div>
	<div class="widget box hidden-xs">
		<div class="widget-header">
			<h4><i class="icon-download"></i> <?php echo __('Available updates'); ?></h4> 
		</div> 
		<div class="widget-content no-padding"> 
			<table class="table table-striped">
				<thead> 
					<tr>
						<th width="100"><?php echo __('Version'); ?></th>
						<th width="140"><?php echo __('Release date'); ?></th>
						<th><?php echo __('Changelog'); ?></th>
						<th width="100"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($update['response']['pending'] as $key => $item) : ?>
						<?php if ($item == reset($update['response']['pending'])) : ?>
							<tr class="update-loading">
								<td colspan="4" style="padding: 0; border: none;">
									<div class="progress progress-striped active" style="display: none;"><div class="progress-bar progress-bar-success" style="width: 100%"></div></div>
								</td>
							</tr>
							<tr class="hidden">
								<td colspan="4">
								</td>
							</tr>
						<?php endif; ?>
						<tr>
							<td><?php echo $item['version']; ?></td>
							<td><?php echo date('d. m. Y', strtotime($item['date'])); ?></td>
							<td><?php echo nl2br($item['changelog']); ?></td>
							<td class="text-center">
								<?php if ($key == 0) : ?>
									<a href="<?php echo Router::url(array('plugin' => null, 'controller' => 'updates', 'action' => 'update', time())); ?>" class="btn-update btn btn-sm btn-success"><?php echo __('Update'); ?></a>
								<?php else : ?>
									<a href="#" disabled="disabled" class="btn btn-sm btn-success"><?php echo __('Update'); ?></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div> 
	</div>
<?php elseif (empty($errorMessage)) : ?>
	<div class="alert alert-info">
		<i class="icon-exclamation-sign"></i>
		<?php echo __('System is up to date. No updates available.'); ?>
	</div>
<?php endif; ?>