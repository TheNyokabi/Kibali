<?php echo $this->Ajax->flash(); ?>

<?php echo $this->Html->css("ObjectVersion.history.css?01122016"); ?>

<?php
$_Audits = $historyClass->getAudits();
?>

<?php if (empty($_Audits)) : ?>
	<div class="custom-padding">
		<?php
		echo $this->Ux->getAlert(__('There is no history recorded for this object yet.'), array('type' => 'info'));
		?>

		<button type="button" class="btn btn-inverse" data-dismiss="modal"><?php echo __('Close'); ?></button>
	</div>

	<?php
	return true;
	?>
<?php endif; ?>

<div class="custom-padding">

	<ul class="timeline">
		<?php $i = 0; foreach ($_Audits as $Audit) : ?>
			<?php
			$Event = $Audit->getEventClass();
			?>
			<li class="<?php echo $this->ObjectVersionAudit->getTimelineClass($Event, $i); ?>">
				<?php
				echo $this->ObjectVersionAudit->getTimelineBadge($Event);
				?>

				<div class="timeline-panel">

					<div class="timeline-heading">
						<div class="timeline-manage-dropdown">
							<?php
							// we dont allow restoration of a last version
							if ($i != 0 || $Event->isDeleted()) :
							?>
								<div class="btn-group">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
										Manage <span class="caret"></span>
									</button>
									<ul class="dropdown-menu pull-right" role="menu">
										<!-- <li><a href="#">View</a></li> -->
										<li>
											<?php
											$restoreUrl = array(
												'plugin' => 'objectVersion',
												'controller' => 'objectVersion',
												'action' => 'restore',
												$Audit->getId()
											);

											echo $this->Html->link(__('Restore'), $restoreUrl, array(
												'class' => 'ajax-restore-link',
												// 'data-ajax-action' => 'submit',
												'escape' => false
											));
											?>
										</li>
										<!-- <li class="divider"></li>
										<li><a href="#">Delete Revision</a></li> -->
									</ul>
								</div>
							<?php endif; ?>
						</div>

						<h4 class="timeline-title">
							<?php
							echo $Event->getLabel();
							?>

							<?php if ($Audit->getVersion()) : ?>
								<small>(<?php echo __('version #%s', $Audit->getVersion()); ?>)</small>
							<?php endif; ?>
						</h4>
						<?php if ($Event->isRestored()) : ?>
							<p>
								<small class="text-muted">
									<i class="icon-retweet"></i>&nbsp;

									<?php
									if ($Audit->hasRestoredVersion()) {
										echo __('Restored from version #%s', $Audit->getRestoredVersion());
									}
									else {
										echo __('Restored from an unspecified revision');
									}

									?>
								</small>
							</p>
						<?php endif; ?>
						<p>
							<small class="text-muted">
								<i class="icon-time"></i>&nbsp;&nbsp;<?php echo $Audit->getTimeAgo(); ?>
							</small>
						</p>
						<p>
							<small class="text-muted">
								<i class="icon-male"></i>&nbsp;&nbsp;&nbsp;<?php echo $Audit->getDescription(); ?>
							</small>
						</p>
					</div>

					<?php if ($Audit->hasAuditDelta(true)) : ?>
						<div class="timeline-body">

							<?php
							$_AuditDeltas = $Audit->getAuditDeltas(true);
							?>
							<div class="list-group">
								<a href="#" class="list-group-item list-group-header" data-toggle-id="<?php echo $Audit->getId(); ?>">
									<span class="badge"><?php echo count($_AuditDeltas); ?></span> 
									<?php echo __('Changes'); ?>
								</a>

								<div class="list-group-section-block hidden fade" data-section-id="<?php echo $Audit->getId(); ?>">
									<?php
									echo $this->Ux->getAlert(__('Changed items are in green.'), array(
										'type' => 'success',
										'class' => 'list-group-changes'
									));
									?>
									<?php foreach ($_AuditDeltas as $AuditDelta) : ?>
										<?php if ($AuditDelta->isEmpty()) continue; ?>
										
										<div class="list-group-item list-audit-delta">
											<div class="row">
												<div class="col-md-4">
													<?php echo $AuditDelta->getFieldLabel($historyClass->getModel()); ?>
												</div>
												<div class="col-md-8">
													<?php if (!$Event->isDeleted()/* && $AuditDelta->hasNewValue()*/) : ?>
													<code class="alert-success audit-delta-change">
														<?php echo getEmptyValue($AuditDelta->getNewLabel()); ?>
													</code>
												<?php endif; ?>

												<?php if (!$Event->isCreated() && $AuditDelta->hasOldValue()) : ?>
													<code class="alert-danger audit-delta-change">
														<?php echo getEmptyValue($AuditDelta->getOldLabel()); ?>
													</code>
												<?php endif; ?>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
					
						</div>
					<?php endif; ?>						

				</div>
			</li>
		<?php $i++; endforeach; ?>
	</ul>

	<button type="button" class="btn btn-inverse history-close-modal" data-dismiss="modal"><?php echo __('Close'); ?></button>
</div>

<script type="text/javascript">
	jQuery(function($) {
		$(".history-close-modal").on("click", function(e) {
			Eramba.Ajax.UI.triggerIndexReload = true;
			Eramba.Ajax.UI.reloadIndex();
		});

		$(".ajax-restore-link").on("click", function(e) {
			var ajax = Eramba.Ajax.UI.requestAction($(this).prop("href"));
			console.log(ajax);
			ajax.done(function(data) {
				$("#eramba-modal").animate({
					scrollTop: 20 //tolerance
				}, 850);
			});

			e.preventDefault();
		});

		$(".list-group-header[data-toggle-id]").on("click", function(e) {
			var $toggleId = $(this).data("toggle-id");

			var $section = $(".list-group-section-block[data-section-id=" + $toggleId + "]");
			if ($section.length) {
				if ($section.hasClass("hidden")) {
					$section.removeClass("hidden").focus().addClass("in");
				}
				else {
					$section.addClass("hidden").removeClass("in");
				}
			}

			e.preventDefault();
		});
	});
</script>