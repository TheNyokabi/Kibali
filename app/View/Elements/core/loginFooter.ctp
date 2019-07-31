<p>
	<?php echo __('Copyright'); ?> 2011-<?php echo date('Y'); ?> <a href="http://www.eramba.org/" target="_blank"><strong>eramba Ltd</strong></a>
</p>
<p>
	<?php
	//echo __('Released under the terms of <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank"><strong>GNU General Public License 2</strong></a>');
	?>
	<strong>
		<?php
		echo $this->Html->link(__('User License'), array(
			'controller' => 'pages',
			'action' => 'license'
		), array(
			'target' => '_blank'
		));
		?>
	</strong>
</p>

<p>
	<?php echo __('App version'); ?> <strong><?php echo Configure::read('Eramba.version'); ?></strong>
</p>
<p>
	<?php echo __('Db schema version'); ?> <strong><?php echo DB_SCHEMA_VERSION; ?></strong>
</p>