<?php 
App::uses('DataAssetInstance', 'Model');
App::uses('DataAsset', 'Model');
App::uses('Country', 'Model');
App::uses('Hash', 'Utility');
// debug($data);
// exit;
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
					<button class="btn dropdown-toggle" data-toggle="dropdown"><?php echo __( 'Actions' ); ?> <span class="caret"></span></button>
					<ul class="dropdown-menu pull-left" style="text-align: left;">
						<li><a href="<?php echo Router::url( array( 'action' => 'export' ) ); ?>"><i class="icon-file"></i> <?php echo __( 'Export CSV' ); ?></a></li>
					</ul>
				</div>

				<?php echo $this->Video->getVideoLink('DataAsset'); ?>

				<?php echo $this->CustomFields->getIndexLink(array(
					'DataAsset' => __('Data Assets'),
				)); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
			</div>
		</div>
	</div>

</div>
<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'DataAsset')); ?>
<div class="row">
	<div class="col-md-12">
		<?php if ( ! empty( $data ) ) : ?>
			<?php foreach ( $data as $entry ) : ?>
				<?php //debug($entry['Asset']); ?>
				<div class="widget box <?php echo empty($entry['DataAsset'])?'widget-closed':'' ?>">
					<div class="widget-header">
						<h4><?php echo __( 'Asset' ); ?>: <?php echo $entry['Asset']['name']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<ul class="dropdown-menu pull-right">
									<li><?php echo $this->Html->link( '<i class="icon-cog"></i> ' . __( 'General Attributes' ), array(
										'controller' => 'dataAssetSettings',
										'action' => 'setup',
										$entry['DataAssetInstance']['id']
									), array(
										'escape' => false,
										'data-ajax-action' => 'add'
									) ); ?></li>
									<?php if ($entry['DataAssetInstance']['analysis_unlocked'] == DataAssetInstance::ANALYSIS_STATUS_UNLOCKED) : ?>
										<li><?php echo $this->Html->link( '<i class="icon-plus-sign"></i> ' . __( 'Add new analysis' ), array(
											'controller' => 'dataAssets',
											'action' => 'add',
											$entry['DataAssetInstance']['id']
										), array(
											'escape' => false,
											'data-ajax-action' => 'add'
										) ); ?></li>
									<?php endif; ?>
								</ul>
							</div>
						</div>
					</div>
					<div class="widget-subheader">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th><?php echo __( 'Description' ); ?></th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'What type of asset is this, remember you can define further types at Asset Management / Settings / Asset Types' ); ?>'>
											<?php echo __( 'Type' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'What labels apply to this asset. For example: Confidential, Restricited, Public, Etc' ); ?>'>
											<?php echo __( 'Label' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The liabilities that are asociated with this asset. This is a rather important field as those liabilites mapped to an asset will magnify all risks scores asociated with it.' ); ?>'>
											<?php echo __( 'Liabilities' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
								        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Assets must be reviewed at regular points in time to ensure they remain relevant and updated to the business. Notifications are triggered (optionaly) when this date arrives' ); ?>'>
											<?php echo __( 'Review Date' ); ?>
									        <i class="icon-info-sign"></i>
								        </div>
									</th>
									<th>
										<?php echo __('Status'); ?>
									</th>
									<th>
										<?php echo __('GDPR'); ?>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<?php
										echo $this->Eramba->getEmptyValue($entry['Asset']['description']);
										?>
									</td>
									<td><?php echo isset( $entry['Asset']['AssetMediaType']['name'] ) ? $entry['Asset']['AssetMediaType']['name'] : ''; ?></td>
									<td><?php echo isset( $entry['Asset']['AssetLabel']['name'] ) ? $entry['Asset']['AssetLabel']['name'] : ''; ?></td>
									<td>
										<?php
										$legals = array();
										foreach ($entry['Asset']['Legal'] as $legal) {
											$legals[] = $legal['name'];
										}
										echo implode(', ', $legals);
										?>
									</td>
									<td><?php echo $entry['Asset']['review']; ?></td>
									<td>
										<?php
										echo $this->Assets->getStatuses($entry, true);
										?>
									</td>
									<td>
										<?php echo getStatusFilterOption()[(empty($entry['DataAssetSetting']['gdpr_enabled'])) ? 0 : 1] ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="widget-content">
						<?php if (!empty($entry['DataAssetSetting']['gdpr_enabled'])) : ?>
							<table class="table table-hover table-striped table-bordered table-highlight-head">
								<thead>
									<tr>
										<th>
											<?php echo __('DPO Role'); ?>
										</th>
										<th>
											<?php echo __('Processor Role'); ?>
										</th>
										<th>
											<?php echo __('Controller Role'); ?>
										</th>
										<th>
											<?php echo __('Controller Representative'); ?>
										</th>
										<th>
											<?php echo __('Supervisory Authority'); ?>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<?php echo implode(Hash::extract($entry, 'DataAssetSetting.Dpo.{n}.full_name'), ', ') ?>
										</td>
										<td>
											<?php echo implode(Hash::extract($entry, 'DataAssetSetting.Processor.{n}.full_name'), ', ') ?>
										</td>
										<td>
											<?php echo implode(Hash::extract($entry, 'DataAssetSetting.Controller.{n}.full_name'), ', ') ?>
										</td>
										<td>
											<?php echo implode(Hash::extract($entry, 'DataAssetSetting.ControllerRepresentative.{n}.full_name'), ', ') ?>
										</td>
										<td>
											<?php
											$countryIds = Hash::extract($entry, 'DataAssetSetting.SupervisoryAuthority.{n}.country_id');
											$countries = [];
											foreach ($countryIds as $countryId) {
												$countries[] = Country::countries()[$countryId];
											}
											echo implode($countries, ', ');
											?>
										</td>
									</tr>
								</tbody>
							</table>
						<?php endif; ?>

						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Select from the list of business unit, which one is the one owning the asset.' ); ?>'>
											<?php echo __( 'Owner' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Select from the list of business unit, which one is in charge of maintening the asset.' ); ?>'>
											<?php echo __( 'Guardian' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Select from the list of business unit, which one is using the asset. You can optionally choose "Everyone".' ); ?>'>
											<?php echo __( 'User' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo (!empty( $entry['Asset']['AssetOwner'])) ? $entry['Asset']['AssetOwner']['name'] : ''; ?></td>
									<td><?php echo (!empty( $entry['Asset']['AssetGuardian'])) ? $entry['Asset']['AssetGuardian']['name'] : ''; ?></td>
									<td><?php echo (!empty( $entry['Asset']['asset_user_id'])) ? $entry['Asset']['AssetUser']['name'] : __('Everyone'); ?></td>
								</tr>
							</tbody>
						</table>

						<?php foreach (DataAsset::statuses() as $statusId => $status) : ?>
							<?php 
							$dataAssets = Hash::extract($entry['DataAsset'], '{n}[data_asset_status_id=' . $statusId . ']');
							?>
							<div class="widget box widget-closed">
								<div class="widget-header">
									<h4><?php echo __('Stage') . ': ' . $status ?></h4>
									<div class="toolbar no-padding">
										<div class="btn-group">
											<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
										</div>
									</div>
								</div>
								<div class="widget-content" style="display:none;">
									<?php if (!empty($dataAssets)) : ?>
										<table class="table table-hover table-striped">
											<thead>
												<tr>
													<th>
														<?php echo __('Title'); ?>
													</th>
													<th>
														<?php echo __('Business Unit'); ?>
													</th>
													<th>
														<?php echo __('Third Parties'); ?>
													</th>
													<th>
														<?php echo __('Risks'); ?>
													</th>
													<th>
														<?php echo __('Controls'); ?>
													</th>
													<th>
														<?php echo __('Policies'); ?>
													</th>
													<th>
														<?php echo __('Projects'); ?>
													</th>
													<th>
														<?php echo __('GDPR'); ?>
													</th>
													<th>
														<?php echo __('Custom Fields'); ?>
													</th>
													<th class="align-center"><?php echo __('Action'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($dataAssets as $dataAsset) : ?>
													<tr>
														<td><?php echo $dataAsset['title'] ?></td>
														<td><?php echo $this->Eramba->getEmptyValue(implode(Hash::extract($dataAsset, 'BusinessUnit.{n}.name'), ', ')) ?></td>
														<td><?php echo $this->Eramba->getEmptyValue(implode(Hash::extract($dataAsset, 'ThirdParty.{n}.name'), ', ')) ?></td>
														<td>
															<?php echo $this->AdvancedFilters->getItemFilteredLink(__('List'), 'Risk', null, ['query' => [
																'data_asset_id' => $dataAsset['id'],
															]]); ?>
														</td>
														<td>
															<?php echo $this->AdvancedFilters->getItemFilteredLink(__('List'), 'SecurityService', null, ['query' => [
																'data_asset_id' => $dataAsset['id'],
															]]); ?>
														</td>
														<td>
															<?php echo $this->AdvancedFilters->getItemFilteredLink(__('List'), 'SecurityPolicy', null, ['query' => [
																'data_asset_id' => $dataAsset['id'],
															]]); ?>
														</td>
														<td>
															<?php echo $this->AdvancedFilters->getItemFilteredLink(__('List'), 'Project', null, ['query' => [
																'data_asset_id' => $dataAsset['id'],
															]]); ?>
														</td>
														<td>
															<?php echo $this->AdvancedFilters->getItemFilteredLink(__('List'), 'SecurityService', null, ['query' => [
																'data_asset_id' => $dataAsset['id'],
															]]); ?>
														</td>
														<td>TBD</td>
														<td class="align-center">
															<?php
															echo $this->Ajax->getActionList($dataAsset['id'], array(
																'style' => 'icons',
																'item' => $dataAsset
															));
															?>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									<?php else : ?>
										<?php echo $this->Eramba->getNotificationBox(__('TBD Empty Message')); ?>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>

						<?php //if (!empty($entry['DataAsset'])) : ?>
							<!-- <table class="table table-hover table-striped">
								<thead>
									<tr>
										<th><?php echo __('Stage'); ?></th>
										<th><?php echo __('Controls'); ?></th>
										<th><?php echo __('Third Parties'); ?></th>
										<th><?php echo __('Business Units'); ?></th>
										<th><?php echo __('Projects'); ?></th>
										<th><?php echo __('Status'); ?></th>
										<th class="align-center"><?php echo __('Action'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php $usedStatuses = array(); ?>
									<?php foreach ($entry['DataAsset'] as $dataAsset) : ?>
										<?php
										$usedStatuses[] = $dataAsset['data_asset_status_id'];

										$controls = array();
										foreach ($dataAsset['SecurityService'] as $control) {
											$controls[] = $control['name'];
										}

										$thirdParties = array();
										foreach ($dataAsset['ThirdParty'] as $thirdParty) {
											$thirdParties[] = $thirdParty['name'];
										}

										$businessUnits = array();
										foreach ($dataAsset['BusinessUnit'] as $businessUnit) {
											$businessUnits[] = $businessUnit['name'];
										}

										$projects = array();
										foreach ($dataAsset['Project'] as $project) {
											$projects[] = $project['title'];
										}
										?>
										<tr>
											<td>
												<?php if (!empty($dataAsset['description'])) : ?>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Description'); ?>"
														data-content='<?php echo nl2br($dataAsset['description']); ?>'>

														<?php echo $dataAsset['DataAssetStatus']['name']; ?>
														<i class="icon-info-sign"></i>
													</div>
												<?php else : ?>
													<?php echo $dataAsset['DataAssetStatus']['name']; ?>
												<?php endif; ?>
											</td>
											<td><?php echo implode('<br />', $controls); ?></td>
											<td><?php echo implode('<br />', $thirdParties); ?></td>
											<td><?php echo implode('<br />', $businessUnits); ?></td>
											<td><?php echo implode('<br />', $projects); ?></td>
											<td>
												<?php
												echo $this->DataAssets->getStatuses($dataAsset, true);
												?>
											</td>
											<td class="align-center">
												<?php
												echo $this->Ajax->getActionList($dataAsset['id'], array(
													'style' => 'icons',
													'item' => $dataAsset
												));
												?>
											</td>
										</tr>
									<?php endforeach; ?>

									<?php foreach ($dataAssetStatuses as $key => $status) : ?>
										<?php if (in_array($key, $usedStatuses)) continue; ?>
										<tr>
											<td><?php echo $status; ?></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>
												<?php
												echo $this->DataAssets->missingStatus();
												?>
											</td>
											<td></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table> -->
						<?php /*else : ?>
							<?php
							echo $this->element('not_found', array(
								'message' => __('No Data Assets found.')
							));
							?>
						<?php endif;*/ ?>
					</div>

					<!-- <div class="widget-content" style="display:<?php echo empty($entry['DataAsset'])?'none':'block' ?>;"> -->
						<?php /*if ( ! empty( $entry['DataAsset'] ) ) : ?>
							<?php foreach ( $entry['DataAsset'] as $status => $data_assets ) : ?>

								<div class="widget box widget-closed">
									<div class="widget-header">
										<h4><?php echo $status ?></h4>
										<div class="toolbar no-padding">
											<div class="btn-group">
												<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
											</div>
										</div>
									</div>

								<?php foreach ($data_assets as $key => $data_asset): ?>

									<div class="widget-content" style="display:none;">

										<div class="widget box widget-closed">
											<div class="widget-header">
												<h4> <?php echo $data_asset['description']?$this->Text->truncate($data_asset['description'], 50, array('ellipsis' => '...', 'exact' => true)):__('Untitled')  ?></h4>
												<div class="toolbar no-padding">
													<div class="btn-group">
														<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
															<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
														</span>
														<ul class="dropdown-menu pull-right">
															<li>
																<?php
																echo $this->Html->link( '<i class="icon-pencil"></i> ' . __( 'Edit' ), array(
																	'controller' => 'dataAssets',
																	'action' => 'edit',
																	$data_asset['id']
																), array(
																	'escape' => false
																));
																?>
															</li>
															<li>
																<?php
																echo $this->Html->link('<i class="icon-cog"></i> ' . __('Records'), array(
																	'controller' => 'systemRecords',
																	'action' => 'index',
																	'DataAsset',
																	$data_asset['id']
																), array(
																	'escape' => false
																));
																?>
															</li>
															<li>
																<?php
																echo $this->Html->link( '<i class="icon-trash"></i> ' . __( 'Delete' ), array(
																	'controller' => 'dataAssets',
																	'action' => 'delete',
																	$data_asset['id']
																), array(
																	'escape' => false
																));
																?>
															</li>
														</ul>
													</div>
												</div>
											</div>
											<div class="widget-subheader">
												<table class="table table-hover table-striped table-bordered table-highlight-head">
													<thead>
														<tr>
															<th><?php echo __( 'Flow Description' ); ?></th>
															<th class="text-center"><?php echo __('Workflow'); ?></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><?php echo $data_asset['description']; ?></td>

															<td class="text-center">
																<?php
																echo $this->element('workflow/action_buttons_1', array(
																	'id' => $data_asset['id'],
																	'item' => $this->Workflow->getActions($data_asset, $data_asset['WorkflowAcknowledgement'])
																));
																?>
															</td>
														</tr>
													</tbody>
												</table>
											</div>

										</div>

									</div>
								<?php endforeach; ?>


									<div class="widget-content" style="display:none;">
										<?php if ( ! empty( $data_asset['SecurityService'] ) ) : ?>
										<table class="table table-hover table-striped">
											<thead>
												<tr>
													<th><?php echo __( 'Security Control' ); ?></th>
													<th><?php echo __( 'Involved Business Unit' ); ?></th>
													<th><?php echo __( 'Involved Third Party' ); ?></th>
													<th><?php echo __( 'Involved Projects' ); ?></th>
													<th>
														<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Status' ); ?>" data-content='<?php echo __( 'Controls with missing or failed (only the last one) audits, tagged as not in production are considered tainted controls that require fix. Missing audits or maintenances tag controls in Orange, failed or non-productive controls are tagged in Red.' ); ?>'>
															<?php echo __( 'Status' ); ?>
															<i class="icon-info-sign"></i>
														</div>
													</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($data_assets as $key => $data_asset) : ?>
												<?php foreach ( $data_asset['SecurityService'] as $security_service ) : ?>
													<?php
														$extra_class = '';
														if ( $security_service['audits_status'] == 1 || ! $security_service['audits_all_done'] ) {
															$extra_class = 'row-warning';
														}
														if ( $security_service['audits_status'] == 2 || ! $security_service['audits_last_passed'] ) {
															$extra_class = 'row-alert';
														}
													?>
													<tr class="<?php //echo $extra_class; ?>">
														<td><?php echo $this->Html->link( $security_service['name'], array(
															'controller' => 'securityServices',
															'action' => 'edit',
															$security_service['id']
														) ); ?></td>
														<td>
															<?php
															$bus = array();
															foreach ($data_asset['BusinessUnit'] as $bu) {
																$bus[] = $bu['name'];
															}
															echo implode(', ', $bus);
															?>
														</td>
														<td>
															<?php
															$tps = array();
															foreach ($data_asset['ThirdParty'] as $tp) {
																$tps[] = $tp['name'];
															}
															echo implode(', ', $tps);
															?>
														</td>
														<td>
															<?php
															$projects = array();
															foreach ($data_asset['Project'] as $project) {
																$projects[] = $project['title'];
															}
															echo implode(', ', $projects);
															?>
														</td>
														<td><?php
															$msg = array();
															if ( ! $security_service['audits_all_done'] ) {
																$msg[] = '<span class="label label-warning">' . __( 'Missing audits.' ) . '</span>';
															}
															if ( ! $security_service['audits_last_passed'] ) {
																$msg[] = '<span class="label label-danger">' . __( 'Last audit failed.' ) . '</span>';
															}

															if ( $security_service['audits_all_done'] && $security_service['audits_last_passed'] ) {
																$msg[] = '<span class="label label-success">' . __( 'No audit issues.' ) . '</span>';
															}

															echo implode( '<br />', $msg );
														?></td>
													</tr>
												<?php endforeach; ?>
												<?php endforeach; ?>
											</tbody>
										</table>
										<?php else : ?>
											<?php echo $this->element( 'not_found', array(
												'message' => __( 'No Security Controls found.' )
											) ); ?>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>

						<?php else : ?>
							<?php echo $this->element( 'not_found', array(
								'message' => __( 'No Data Assets found.' )
							) ); ?>
						<?php endif;*/ ?>

					<!-- </div> -->
				</div>

			<?php endforeach; ?>

			<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
		<?php else : ?>
			<?php echo $this->element( 'not_found', array(
				'message' => __( 'No Assets found.' )
			) ); ?>
		<?php endif; ?>

	</div>

</div>