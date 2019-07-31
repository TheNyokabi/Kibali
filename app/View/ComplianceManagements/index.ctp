<?php
App::uses('AbstractQuery', 'Lib/AdvancedFilters/Query');
App::uses('ComplianceTreatmentStrategy', 'Model');
?>
<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php
					// echo $this->Html->link( '<i class="icon-cog"></i>' . __('Workflow'), array(
					// 	'controller' => 'workflows',
					// 	'action' => 'edit',
					// 	$workflowSettingsId
					// ), array(
					// 	'class' => 'btn',
					// 	'escape' => false
					// ));
					?>
				</div>

				<?php
				echo $this->AdvancedFilters->getViewList($savedFilters, 'ComplianceManagement', true);
				?>

				<?php echo $this->Video->getVideoLink('ComplianceManagement'); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>

				<?php
				echo $this->CustomFields->getIndexLink(array(
					'ComplianceManagement' => ClassRegistry::init('ComplianceManagement')->label(['singular' => true]),
				));
				?>
			</div>
		</div>

		<?php if ( ! empty( $data ) ) : ?>
			<?php foreach ( $data as $entry ) : ?>
<?php
/*$compliance_management_count = $compliance_mitigate = $compliance_not_applicable = $compliance_overlooked = $compliance_without_controls = $controls = $compliance_not_compliant = 0;
$failed_controls = 0;
$ok_controls = 0;
$efficacy = 0;
foreach ( $entry['CompliancePackage'] as $compliance_package ) {
	foreach ( $compliance_package['CompliancePackageItem'] as $compliance_package_item ) {
		//if ( ! empty( $compliance_package_item['ComplianceManagement'] ) ) {
			$compliance_management_count++;

			if ( empty( $compliance_package_item['ComplianceManagement'] ) ) {
				$compliance_overlooked++;
				continue;
			}

			if ( $compliance_package_item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_NOT_APPLICABLE ) {
				$compliance_not_applicable++;
			}

			if ( $compliance_package_item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_NOT_COMPLIANT ) {
				$compliance_not_compliant++;
			}

			if ( $compliance_package_item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_MITIGATE ) {
				$compliance_mitigate++;

				if ( empty( $compliance_package_item['ComplianceManagement']['SecurityService'] ) || empty( $compliance_package_item['ComplianceManagement']['SecurityPolicy'] ) ) {
					$compliance_without_controls++;
				}

				$efficacy += $compliance_package_item['ComplianceManagement']['efficacy'];
			}

			foreach ( $compliance_package_item['ComplianceManagement']['SecurityService'] as $security_service ) {
				if ( ! $security_service['audits_all_done'] || ! $security_service['audits_last_passed'] || ! $security_service['maintenances_all_done'] || ! $security_service['maintenances_last_passed'] ) {
					$failed_controls++;
				} else {
					$ok_controls++;
				}

				$controls++;
			}
		//}
	}
}
//debug( $compliance_overlooked );
$overlooked_items = $not_applicable_items = $addressed_items = $no_controls_items = $failed_controls_items = $ok_controls_items = $efficacy_average = $not_compliant_item = 0;

if ( $compliance_management_count != 0 ) {
	$overlooked_items = $compliance_overlooked / $compliance_management_count;
	$not_applicable_items = $compliance_not_applicable / $compliance_management_count;
	$addressed_items = $compliance_mitigate / $compliance_management_count;
	$not_compliant_item = $compliance_not_compliant / $compliance_management_count;
	if ($compliance_mitigate == 0) {
		$no_controls_items = 0;
		$efficacy_average = 0;
	}
	else {
		$no_controls_items = $compliance_without_controls / $compliance_mitigate;
		$efficacy_average = $efficacy / $compliance_mitigate;
	}


	if ( $controls != 0 ) {
		$failed_controls_items = $failed_controls / $controls;
		$ok_controls_items = $ok_controls / $controls;
	}
}

$reviewed = CakeNumber::toPercentage(1 - $overlooked_items, 0, array('multiply' => true));
$reviewedItemsCount = $compliance_management_count - $compliance_overlooked;

$reviewedLabel = __('(%s Reviewed)', $reviewed);*/

$stats = $this->ComplianceManagements->getPackagesStats($entry['CompliancePackage']);
// debug($stats);
?>
				<div class="widget box widget-closed">
					<div class="widget-header">
						<h4><?php echo __( 'Third Party' ); ?>: <?php echo $entry['ThirdParty']['name']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<ul class="dropdown-menu pull-right">
									<li><?php echo $this->Html->link( '<i class="icon-search"></i> ' . __( 'Analyze' ), array(
										'controller' => 'complianceManagements',
										'action' => 'analyze',
										$entry['ThirdParty']['id']
									), array(
										'escape' => false,
										'data-ajax-action' => 'index'
									) ); ?></li>
									<li><?php echo $this->Html->link( '<i class="icon-file"></i> ' . __( 'Export CSV' ), array(
										'controller' => 'complianceManagements',
										'action' => 'export',
										$entry['ThirdParty']['id']
									), array(
										'escape' => false
									) ); ?></li>
									<li><?php echo $this->Html->link( '<i class="icon-file"></i> ' . __( 'Export PDF' ), array(
										'controller' => 'complianceManagements',
										'action' => 'exportPdf',
										$entry['ThirdParty']['id']
									), array(
										'escape' => false
									) ); ?></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="widget-subheader">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th>
										<div>
											<?php echo __('All Items'); ?>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'From all the items in this compliance package, how many have been configured as being "Compliant".' ); ?>'>
											<?php echo __( 'Compliant Items' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'From all the items in this compliance package, how many have not been reviewed at all' ); ?>'>
											<?php echo __( 'Overlooked Items' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'From all the items that have been reviewed, how many have been configured as "Not Applicable"' ); ?>'>
											<?php echo __( 'Not Applicable Items' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
										<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'From all the items that have been reviewed (Addressed), how many are tagged as "Non Compliant"' ); ?>'>
											<?php echo __( 'Non Compliant Items' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( '' ); ?>'>
											<?php echo __( 'Involved Assets' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Items for which Security Services are used and they miss audits' ); ?>'>
											<?php echo __( 'Controls Missing Audits' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Items for which Security Services are used and they have failed audits' ); ?>'>
											<?php echo __( 'Controls Failed Audits' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Items for which Policies are used and they miss reviews' ); ?>'>
											<?php echo __( 'Policies Missing Reviews' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<?php
										echo $entry['stats']['compliance_management_count'];
										echo ' - ' . $this->AdvancedFilters->getItemFilteredLink(__('List'), 'ComplianceManagement', null, ['query' => [
											'third_party' => $entry['ThirdParty']['id'],
											'third_party__comp_type' => AbstractQuery::COMPARISON_IN
										]]);
										?>
									</td>
									<td>
										<?php
										echo $entry['stats']['compliance_mitigate'];
										echo ' - ' . $this->AdvancedFilters->getItemFilteredLink(__('List'), 'ComplianceManagement', null, ['query' => [
											'third_party' => $entry['ThirdParty']['id'],
											'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
											'compliance_treatment_strategy_id' => ComplianceTreatmentStrategy::STRATEGY_COMPLIANT,
											'compliance_treatment_strategy_id__comp_type' => AbstractQuery::COMPARISON_IN
										]]);
										?>
									</td>
									<td>
										<?php
										echo $entry['stats']['compliance_overlooked'];
										echo ' - ' . $this->AdvancedFilters->getItemFilteredLink(__('List'), 'ComplianceManagement', null, ['query' => [
											'third_party' => $entry['ThirdParty']['id'],
											'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
											'compliance_treatment_strategy_id__comp_type' => AbstractQuery::COMPARISON_IS_NULL,
										]]);
										?>
									</td>
									<td>
										<?php
										echo $entry['stats']['compliance_not_applicable'];
										echo ' - ' . $this->AdvancedFilters->getItemFilteredLink(__('List'), 'ComplianceManagement', null, ['query' => [
											'third_party' => $entry['ThirdParty']['id'],
											'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
											'compliance_treatment_strategy_id' => ComplianceTreatmentStrategy::STRATEGY_NOT_APPLICABLE,
											'compliance_treatment_strategy_id__comp_type' => AbstractQuery::COMPARISON_IN
										]]);
										?>
									</td>
									<td>
										<?php
										echo $entry['stats']['compliance_not_compliant'];
										echo ' - ' . $this->AdvancedFilters->getItemFilteredLink(__('List'), 'ComplianceManagement', null, ['query' => [
											'third_party' => $entry['ThirdParty']['id'],
											'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
											'compliance_treatment_strategy_id' => ComplianceTreatmentStrategy::STRATEGY_NOT_COMPLIANT,
											'compliance_treatment_strategy_id__comp_type' => AbstractQuery::COMPARISON_IN
										]]);
										?>
									</td>
									<td>
										<?php
										echo $entry['stats']['assets'];
										echo ' - ' . $this->AdvancedFilters->getItemFilteredLink(__('List'), 'ComplianceManagement', null, ['query' => [
											'third_party' => $entry['ThirdParty']['id'],
											'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
											'asset_id__comp_type' => AbstractQuery::COMPARISON_IS_NOT_NULL
										]]);
										?>
									</td>
									<td>
										<?php
										echo $entry['stats']['compliance_without_controls'];
										echo ' - ' . $this->AdvancedFilters->getItemFilteredLink(__('List'), 'ComplianceManagement', null, ['query' => [
											'third_party' => $entry['ThirdParty']['id'],
											'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
											'security_service_audits_last_missing' => true,
										]]);
										?>
									</td>
									<td>
										<?php
										echo $entry['stats']['failed_controls'];
										echo ' - ' . $this->AdvancedFilters->getItemFilteredLink(__('List'), 'ComplianceManagement', null, ['query' => [
											'third_party' => $entry['ThirdParty']['id'],
											'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
											'security_service_audits_last_passed' => false,
										]]);
										?>
									</td>
									<td>
										<?php
										echo $entry['stats']['missing_review'];
										echo ' - ' . $this->AdvancedFilters->getItemFilteredLink(__('List'), 'ComplianceManagement', null, ['query' => [
											'third_party' => $entry['ThirdParty']['id'],
											'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
											'security_policy_expired_reviews' => true,
										]]);
										?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="widget-content" style="display:none;">
						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __('Compliance Exceptions'); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content table-check-empty" style="display:none;">
								<table class="table table-hover table-striped table-bordered table-highlight-head">
									<thead>
										<tr>
											<th><?php echo __( 'Compliance Package Item' ); ?></th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The title for the compliance exception' ); ?>'>
	<?php echo __( 'Exception Title' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The date when the compliance exception expired and must be reviewed' ); ?>'>
	<?php echo __( 'Expiration' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Complaince status can be: "Expired (red)" - when the date set is in the past. A system record is generated on the exception when that happens.' ); ?>'>
	<?php echo __( 'Status' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
										</tr>
									</thead>

									<tbody>
									<?php
									$usedIds = array();
									?>
									<?php foreach ($entry['CompliancePackage'] as $package) : ?>
										<?php foreach ($package['CompliancePackageItem'] as $item) : ?>
											<?php foreach ($item['ComplianceManagement']['ComplianceException'] as $exception) : ?>
												<?php
												if (in_array($exception['id'], $usedIds)) continue;
												$usedIds[] = $exception['id'];
												?>
												<tr>
													<td>
														<?php
														echo $item['item_id'] . ' - ' . $item['name'];
														?>
													</td>
													<td><?php echo $exception['title']; ?></td>
													<td><?php echo $exception['expiration']; ?></td>
													<td>
														<?php
														echo $this->ComplianceExceptions->getStatuses(['ComplianceException' => $exception]);
														?>
														<?php //echo $this->App->getExpiredLabel($mgt['ComplianceException']['expiration'], $mgt['ComplianceException']['status']); ?>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php endforeach; ?>
									<?php endforeach; ?>
									</tbody>

								</table>
							</div>
						</div>
					</div>

					<div class="widget-content" style="display:none;">
						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __('Improvement Projects'); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content table-check-empty" style="display:none;">
								<table class="table table-hover table-striped table-bordered table-highlight-head">
									<thead>
										<tr>
											<th><?php echo __( 'Compliance Package Item' ); ?></th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The project title' ); ?>'>
	<?php echo __( 'Title' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Project status can be: - "Over Budget (Red)" - when the current budget is higher than the set on the project. a system record is generated on the project showing both numbers. - "Expired Tasks (Yellow)" - when one or more tasks expiration date is in the past. a system record is generated on the project with the name of the task.- "Expired Project (Red)" - when the project deadline is in the past. a system record is generated when that happens.' ); ?>'>
	<?php echo __( 'Status' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
										</tr>
									</thead>

									<tbody>
									<?php
									$usedIds = array();
									?>
									<?php foreach ($entry['CompliancePackage'] as $package) : ?>

										<?php foreach ($package['CompliancePackageItem'] as $item) : ?>
												<?php if (empty($item['ComplianceManagement']['Project'])) continue; ?>

												<?php foreach ($item['ComplianceManagement']['Project'] as $project) : ?>
													<?php if (in_array($project['id'], $usedIds)) continue; ?>
													<?php $usedIds[] = $project['id']; ?>

													<tr>
														<td>
															<?php
															echo $item['item_id'] . ' - ' . $item['name'];
															?>
														</td>
														<td><?php echo $project['title']; ?></td>
														<td>
															<?php
															echo $this->Projects->getStatuses($project, true);
															?>
															<?php
															//echo $this->App->getExpiredLabel($project['deadline']);
															?>
														</td>
													</tr>
												<?php endforeach; ?>
										<?php endforeach; ?>

									<?php endforeach; ?>
									</tbody>

								</table>
							</div>
						</div>
					</div>

					<div class="widget-content" style="display:none;">
						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __( 'Requirements with mitigation Issues' ); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content table-check-empty" style="display:none;">
								<table class="table table-hover table-striped table-bordered table-highlight-head">
									<thead>
										<tr>
											<th><?php echo __( 'Compliance Package Item' ); ?></th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Type refer to the type of object that has been mapped to a compliance requirement but has some type of issue. Type can be a Compliance Exception or a Security Service' ); ?>'>
	<?php echo __( 'Type' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name of the Compliance Exception or Security Service' ); ?>'>
	<?php echo __( 'Name' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The status indicates the issues identified with this object' ); ?>'>
	<?php echo __( 'Status' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
										</tr>
									</thead>

									<?php echo $this->element( 'compliance_management_issues', array(
										'compliance_packages' => $entry['CompliancePackage']
									) ); ?>

								</table>
							</div>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

			<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
		<?php else : ?>
			<?php echo $this->element( 'not_found', array(
				'message' => __( 'Nothing found.' )
			) ); ?>
		<?php endif; ?>

	</div>

</div>

<script type="text/javascript">
jQuery(function($) {
	var msg = "<?php echo addslashes($this->element('not_found', array('message' => __('Nothing found.')))); ?>";
	$(".table-check-empty").each(function(i,e) {
		if (!$(this).find("table tbody tr").length) {
			$(this).empty();
			$(this).html(msg);
		}
	});
});
</script>