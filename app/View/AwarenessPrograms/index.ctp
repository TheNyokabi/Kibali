<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Html->link('<i class="icon-plus-sign"></i>' . __( 'Add New' ), array(
						'controller' => 'awarenessPrograms',
						'action' => 'add'
					), array(
						'class' => 'btn',
						'escape' => false
					)); ?>

					<?php
					// echo $this->Html->link('<i class="icon-cog"></i>' . __('Workflow'), array(
					// 	'controller' => 'workflows',
					// 	'action' => 'edit',
					// 	$workflowSettingsId
					// ), array(
					// 	'class' => 'btn',
					// 	'escape' => false
					// ));
					?>
				</div>

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'AwarenessProgram'); ?>

				<?php
				echo $this->NotificationSystem->getIndexLink('AwarenessProgram');
				?>

				<?php echo $this->Video->getVideoLink('AwarenessProgram'); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
			</div>
		</div>
	</div>
</div>
<?php
if (isset($ldapConnection) && $ldapConnection !== true) {
	echo $this->element('ldapConnectors/ldapConnectionStatus');
	return false;
}
?>

<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'AwarenessProgram')); ?>

<div class="row">
	<div class="col-md-12">
		<?php if (!empty($data)) : ?>
			<?php foreach ($data as $item) : ?>
				<div class="widget box widget-closed">
					<div class="widget-header">
						<h4><?php echo __( 'Awareness Program:' ) . ' ' . $item['AwarenessProgram']['title']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<?php
								if ($item['AwarenessProgram']['status'] == AWARENESS_PROGRAM_STOPPED) {
									$demoUrl = array(
										'controller' => 'awarenessPrograms',
										'action' => 'demo',
										$item['AwarenessProgram']['id']
									);

									$startUrl = array(
										'controller' => 'awarenessPrograms',
										'action' => 'start',
										$item['AwarenessProgram']['id']
									);

									$this->Ajax->addToActionList(__('Demo'), $demoUrl, 'wrench', false);
									$this->Ajax->addToActionList(__('Start'), $startUrl, 'play-sign', false, array(
										'class' => 'program-start-btn'
									));
								}

								if ($item['AwarenessProgram']['status'] == AWARENESS_PROGRAM_STARTED) {
									$stopUrl = array(
										'controller' => 'awarenessPrograms',
										'action' => 'stop',
										$item['AwarenessProgram']['id']
									);

									$this->Ajax->addToActionList(__('Pause'), $stopUrl, 'pause', false);
								}

								$cleanRecordsUrl = array(
									'controller' => 'awarenessPrograms',
									'action' => 'clean',
									$item['AwarenessProgram']['id']
								);

								$this->Ajax->addToActionList(__('Clean Records'), $cleanRecordsUrl, 'eraser', false);

								$checkLdapUrl = array(
									'controller' => 'awarenessPrograms',
									'action' => 'ldapCheck',
									$item['AwarenessProgram']['id']
								);

								$this->Ajax->addToActionList(__('Check LDAP'), $checkLdapUrl, 'cog', 'custom');

								echo $this->Ajax->getActionList($item['AwarenessProgram']['id'], array(
									'notifications' => true,
									'disableEditAjax' => true,
									'item' => $item,
									'history' => true
								));
								?>
							</div>
						</div>
					</div>
					<div class="widget-subheader">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th><?php echo __('Description'); ?></th>
									<th><?php echo __('Recurrence'); ?></th>
									<th><?php echo __('Reminders Apart'); ?></th>
									<th><?php echo __('Reminders Amount'); ?></th>
									<th><?php echo __('Video'); ?></th>
									<th><?php echo __('Text File'); ?></th>
									<th><?php echo __('Policies'); ?></th>
									<th><?php echo __('Multiple Choice'); ?></th>
									<th><?php echo __('Groups Included'); ?></th>
									<th class="row-status"><?php echo __('Status'); ?></th>
									<?php /*
									<th class="align-center row-workflow"><?php echo __('Workflows'); ?></th>
									*/ ?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<?php echo $this->Eramba->getEmptyValue($item['AwarenessProgram']['description']); ?>
									</td>
									<td>
										<?php
										$recurrence = $item['AwarenessProgram']['recurrence'];
										echo sprintf(__n('%d day', '%d days', $recurrence), $recurrence);
										?>
									</td>
									<td>
										<?php
										$reminder_apart = $item['AwarenessProgram']['reminder_apart'];
										echo sprintf(__n('%d day', '%d days', $reminder_apart), $reminder_apart);
										?>
									</td>
									<td>
										<?php
										echo $item['AwarenessProgram']['reminder_amount'];
										?>
									</td>
									<td>
										<?php
										echo !empty($item['AwarenessProgram']['video']) ? __('Yes') : __('No');
										?>
									</td>
									<td>
										<?php
										echo !empty($item['AwarenessProgram']['text_file']) ? __('Yes') : __('No');
										?>
									</td>
									<td>
										<?php
										$val = implode(', ', Hash::extract($item, 'SecurityPolicy.{n}.index'));
										echo $this->Eramba->getEmptyValue($val);
										?>
									</td>
									<td>
										<?php
										echo !empty($item['AwarenessProgram']['questionnaire']) ? __('Yes') : __('No');
										?>
									</td>
									<td>
										<?php
										$groups = array();
										foreach ($item['AwarenessProgramLdapGroup'] as $group) {
											$groups[] = $group['name'];
										}

										if (!empty($groups)) {
											echo implode(', ', $groups);
										}
										else {
											echo '-';
										}
										?>
									</td>
									<td>
										<?php echo $this->AwarenessPrograms->getStatuses($item); ?>
									</td>
									<?php /*
									<td class="align-center">
									<?php
									echo $this->element('workflow/action_buttons_1', array(
										'id' => $item['AwarenessProgram']['id'],
										'item' => $this->Workflow->getActions($item['AwarenessProgram'], $item['WorkflowAcknowledgement'])
									));
									?>
									</td>
									*/ ?>
								</tr>
							</tbody>
						</table>

						<?php
						$program = $item['AwarenessProgram'];

						if ($program['stats_update_status'] == AWARENESS_PROGRAM_STATS_UPDATE_SUCCESS) :
						?>
							<table class="table table-hover table-striped table-bordered table-highlight-head">
								<thead>
									<tr>
										
								<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The current list of users included in this awareness program' ); ?>'>
								<?php echo __('Users in the Program'); ?>
								<i class="icon-info-sign"></i>
								</div>
								</th>

								<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The list of users excluded by the administrator for this awareness program. This list can be updated only if the program is edited and updated.' ); ?>'>
								<?php echo __('Users Excluded'); ?>
								<i class="icon-info-sign"></i>
								</div>
								</th>
								
								<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The list of users who have completed trainings in time.' ); ?>'>
								<?php echo __('Compliant Users'); ?>
								<i class="icon-info-sign"></i>
								</div>
								</th>
								
								<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The list of users that have not yet completed this training as required. Details on the records (emails sent to the user, etc) can be exported in CSV or reviewed on the records.' ); ?>'>
								<?php echo __('Not compliant users'); ?>
								<i class="icon-info-sign"></i>
								</div>
								</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<?php
											echo $this->AwarenessPrograms->getStatisticPart($program, 'active');
											?>
											<?php
											echo $this->AdvancedFilters->getItemFilteredLink(__('List Users'), 'AwarenessProgramUser', $program['id'], array(
									            'key' => 'awareness_program_id',
									            'param' => 'ActiveUser'
									        ));
											?>
										</td>
										<td>
											<?php
											echo $this->AwarenessPrograms->getStatisticPart($program, 'ignored');
											?>
											<?php
											echo $this->AdvancedFilters->getItemFilteredLink(__('List Users'), 'AwarenessProgramUser', $program['id'], array(
									            'key' => 'awareness_program_id',
									            'param' => 'IgnoredUser'
									        ));
											?>
										</td>
										<td>
											<?php
											echo $this->AwarenessPrograms->getStatisticPart($program, 'compliant');
											?>
											<?php
											echo $this->AdvancedFilters->getItemFilteredLink(__('List Users'), 'AwarenessProgramUser', $program['id'], array(
									            'key' => 'awareness_program_id',
									            'param' => 'CompliantUser'
									        ));
											?>
										</td>
										<td>
											<?php
											echo $this->AwarenessPrograms->getStatisticPart($program, 'not_compliant');
											?>
											<?php
											echo $this->AdvancedFilters->getItemFilteredLink(__('List Users'), 'AwarenessProgramUser', $program['id'], array(
									            'key' => 'awareness_program_id',
									            'param' => 'NotCompliantUser'
									        ));
											?>
										</td>
									</tr>
								</tbody>
							</table>
							<?php
							echo $this->Ux->getAlert(__('Statistics and filters only update once a day when the daily cron runs successfully.'), array(
								'type' => 'info'
							));
							?>
						<?php else : ?>
							<?php
							echo $this->element(
								'not_found', array(
								'message' => __('Statistics not available at the moment as they need to be re-calculated during next CRON job. Make sure that groups in this program have not been deleted or renamed and also they include one or more users.')
							));
							?>
						<?php endif; ?>
					</div>
				</div>

			<?php endforeach; ?>

			<?php echo $this->element(CORE_ELEMENT_PATH . 'pagination'); ?>
		<?php else : ?>
			<?php
			echo $this->element(
				'not_found', array(
				'message' => __('No Awareness Programs found.')
			));
			?>
		<?php endif; ?>

	</div>

</div>

<script type="text/javascript">
	jQuery(function($) {
		$(".user-list-modal").on("click", function(e) {
			e.preventDefault();

			var title = "<?php echo __('List of users'); ?>";
			if (typeof $(this).data("modal-title") != "undefined" && $(this).data("modal-title")) {
				title = $(this).data("modal-title");
			}

			var message = $(this).data("message");

			openUserListModal(title, message);
		});
	});
	function openUserListModal(title, message) {
		bootbox.dialog({
			title: title,
			message: message,
			buttons: {
				"ok": {
					label: "<?php echo __('Ok'); ?>"
				}
			}
		});
	}
</script>
