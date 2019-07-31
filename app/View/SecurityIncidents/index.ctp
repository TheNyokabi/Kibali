<?php
echo $this->Html->script('policy-document', array('inline' => false));
echo $this->Html->css('policy-document', array('inline' => false));
?>
<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Ajax->addAction(); ?>
					
					<?php /*
					<button class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i><?php echo __('Workflow'); ?> <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right" style="text-align: left;">
						<li>
							<?php
							echo $this->Html->link(__('Security Incident'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$workflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Security Incident Stages'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$stageWorkflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
					</ul>
					*/ ?>
				</div>

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'SecurityIncident'); ?>

				<div class="btn-group group-merge">
					<button class="btn dropdown-toggle" data-toggle="dropdown"><?php echo __('Settings'); ?> <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right">
						<li>
							<?php
							echo $this->Html->link(__('Stages'), array(
								'controller' => 'securityIncidentStages',
								'action' => 'index'
							), array(
								'escape' => false
							));
							?>
						</li>
					</ul>
				</div>

				<?php echo $this->NotificationSystem->getIndexLink('SecurityIncident'); ?>

				<?php echo $this->CustomFields->getIndexLink(array(
					'SecurityIncident' => __('Security Incident'),
				)); ?>

				<?php echo $this->Video->getVideoLink('SecurityIncident'); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>

				<?php //echo $this->element( CORE_ELEMENT_PATH . 'filter' , array('filterElement' => $this->element(FILTERS_ELEMENT_PATH . 'filter_security_incident'))); ?>
			</div>
		</div>
	</div>

</div>

<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'SecurityIncident')); ?>
<div class="row">
	<div class="col-md-12">

		<?php if (!empty($data)) : ?>
			<?php foreach ($data as $item) : ?>
				<div class="widget-item" data-id="<?php echo $item['SecurityIncident']['id']; ?>">
					<?php
					echo $this->element('../SecurityIncidents/item', array(
						'item' => $item
					));
					?>
				</div>
			<?php endforeach; ?>

			<?php echo $this->element(CORE_ELEMENT_PATH . 'pagination'); ?>

		<?php else : ?>
			<?php echo $this->element('not_found', array(
				'message' => __('No Security Incidents found.')
			)); ?>
		<?php endif; ?>
	</div>

</div>

<script type="text/javascript">


	function processStage(type, id, el, status) {
		var qc = "<?php echo __('Do you really want to set this stage as completed?') ?>";
		var qr = "<?php echo __('Do you really want to set this stage as initiated?') ?>" ;
		var q = type?qc:qr;
		var securityIncidentId = $(el).parents(".widget-item").data("id");

		bootbox.confirm(q, function(result) {
		  if(result){
		  	$.ajax({
				dataType: "json",
				type: "POST",
				url: "/securityIncidentStages/pocessStage/"+id+"/"+type+"/time:"+ new Date().getTime()
			})
			.done(function(data){
				if(data.workflowUrl){
					window.location = data.workflowUrl;
					return false;
				}
				if(!data.result){
					$(el).after( "<p class='red'> <?php echo __('Unable to process!') ?></p>" );
				}else{
					$('.stage-'+id).text(status);
					if(type){
						$('.stage-'+id).switchClass( "label-danger", "label-success", 1000, "easeInOutQuad" );
						$('.stage-btn-uncomp-'+id).removeClass('hidden');
						$('.stage-btn-comp-'+id).addClass('hidden');
					}else{
						$('.stage-'+id).switchClass( "label-success", "label-danger", 1000, "easeInOutQuad" );
						$('.stage-btn-comp-'+id).removeClass('hidden');
						$('.stage-btn-uncomp-'+id).addClass('hidden');
					}

					reloadLifecycle(securityIncidentId);
				}
			});
		  }
		});
	}

	function reloadLifecycle(id) {
		var $ele = $(".widget-item[data-id=" + id + "]");
		App.blockUI($ele);

		$.ajax({
			type: "GET",
			url: "/securityIncidents/reloadLifecycle/"+id+"/time:"+ new Date().getTime()
		})
		.done(function(data){
			$ele.html(data);
			Plugins.init();
			App.unblockUI($ele);

			App.init();
			Eramba.Ajax.UI.attachEvents()
			$("#content").trigger("Eramba.reloadIndex");
		});
	}
</script>
