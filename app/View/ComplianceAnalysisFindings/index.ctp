<?php
App::uses('AppModule', 'Lib');
?>
<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Ajax->addAction(); ?>
				</div>

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'ComplianceAnalysisFinding'); ?>

				<?php echo $this->NotificationSystem->getIndexLink('ComplianceAnalysisFinding'); ?>

				<?php
				$ComplianceAnalysisFinding = _getModelInstance('ComplianceAnalysisFinding');

				echo $this->CustomFields->getIndexLink(array(
					'ComplianceAnalysisFinding' => $ComplianceAnalysisFinding->label(),
				));
				?>
				
				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'ComplianceAnalysisFinding')); ?>

<div class="row">
	<div class="col-md-12">
			<?php if ( ! empty( $data ) ) : ?>
				<?php foreach ( $data as $item ) : ?>

					<div class="widget box widget-closed">
						<div class="widget-header">
							<h4><?php echo __('Compliance Analysis Finding'); ?>: <?php echo $item['ComplianceAnalysisFinding']['title']; ?></h4>
							<div class="toolbar no-padding">
								<div class="btn-group">
									<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
										<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
									</span>
									<?php
									$exportUrl = array(
										'action' => 'exportPdf',
										$item['ComplianceAnalysisFinding']['id']
									);

									$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

									echo $this->Ajax->getActionList($item['ComplianceAnalysisFinding']['id'], array(
										'notifications' => true,
										'item' => $item,
										'history' => true,
										AppModule::instance('Visualisation')->getAlias() => true
									));
									?>
								</div>
							</div>
						</div>

						<div class="widget-subheader">
							<table class="table table-hover table-striped table-bordered table-highlight-head">
								<thead>
									<tr>
										<th>
											<?php echo __('Owners'); ?>
										</th>
										<th>
											<?php echo __('Collaborators'); ?>
										</th>
										<th>
											<?php echo __('Tag'); ?>
										</th>
										<th>
											<?php echo __('Due Date'); ?>
										</th>
										<th>
											<?php echo __('Status'); ?>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<?= $this->UserField->showUserFieldRecords($item['Owner']); ?>
										</td>
										<td>
											<?= $this->UserField->showUserFieldRecords($item['Collaborator']); ?>
										</td>
										<td>
											<?php
											echo $this->ComplianceAnalysisFindings->getTags($item);
											?>
										</td>
										<td>
											<?php
											echo $this->ComplianceAnalysisFindings->getDueDate($item);
											?>
										</td>
										<td class="text-center">
											<?php
											echo $this->ComplianceAnalysisFindings->getStatuses($item);
											?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
							<div class="widget-subheader">
							<table class="table table-hover table-striped table-bordered table-highlight-head">
								<thead>
									<tr>
										<th><?php echo __( 'Description' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<?php
											echo $this->ComplianceAnalysisFindings->getDescription($item);
											?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="widget-content" style="display:block;">
							<div class="widget box widget-closed">
								<div class="widget-header">
									<h4><?php echo __('Associated Compliance Package Items'); ?></h4>
									<div class="toolbar no-padding">
										<div class="btn-group">
											<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
										</div>
									</div>
								</div>
								<div class="widget-content" style="display:none;">
									<?php
									$assocData = $this->ComplianceAnalysisFindings->getAssociatedData($item);
									echo $this->element('ux/table', array(
										'tableData' => $assocData,
										'notFound' => __('No Compliance items has been linked to this finding.')
									));
									?>
								</div>
							</div>
						</div>

						<div class="widget-subheader">
							<?php
							echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
								'item' => $item // single database item in a variable
							));
							?>
					    </div>

					</div>
				<?php endforeach; ?>

				<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
			<?php else : ?>
				<?php echo $this->element( 'not_found', array(
					'message' => __( 'No Findings found.' )
				) ); ?>
			<?php endif; ?>
		</div>

	</div>
</div>
