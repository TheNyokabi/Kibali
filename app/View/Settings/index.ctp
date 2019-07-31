<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">

				<?php echo $this->Video->getVideoLink('Setting'); ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<?php foreach ($settingsGroups as $key => $settingsGroup): ?>
		<?php if($key%((int)(count($settingsGroups)/2)) == 0): ?>
			<div class="col-md-6">
		<?php endif; ?>
		<div class="widget box">
			<div class="widget-header">
				<h4>
					<?php echo $this->Html->tag('i', '', array('class' => $settingsGroup['SettingGroup']['icon_code'])); ?>
					<?php echo $settingsGroup['SettingGroup']['name'] ?>
				</h4>
				<!-- <div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div> -->
			</div>
			<div class="widget-content">
				<ul class="list-unstyled">
					<?php foreach ($settingsGroup['SettingSubGroup'] as $settingSubGroup): ?>
					<li>
						<?php echo $this->Html->link(
							$settingSubGroup['name'],
							!empty($settingSubGroup['url'])?json_decode($settingSubGroup['url'], true):array('controller' => 'settings', 'action' => 'edit', $settingSubGroup['slug'])
							);
						?>
						<?php if(isset($groupHelpText[$settingSubGroup['slug']])): ?>
						<i class="icon-info-sign bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo $groupHelpText[$settingSubGroup['slug']] ; ?>'>
					    </i>
						<?php endif;?>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php if(($key+1)%((int)(count($settingsGroups)/2)) == 0): ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>

