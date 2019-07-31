<?php
App::uses('ComplianceAudit', 'Model');
App::uses('AppModule', 'Lib');
?>
<div class="row">

    <div class="col-md-12">
        <div class="widget">
            <div class="btn-toolbar">
                <div class="btn-group">
                    <?php echo $this->Ajax->addAction(); ?>

                    <!-- <button class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i><?php echo __('Workflow'); ?> <span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right" style="text-align: left;">
                        <li>
                            <?php
                            // echo $this->Html->link(__('Compliance Finding'), array(
                            //     'controller' => 'workflows',
                            //     'action' => 'edit',
                            //     $complianceFindingsWorkflowSettingsId
                            // ), array(
                            //     'escape' => false
                            // ));
                            ?>
                        <li>
                            <?php
                            // echo $this->Html->link(__('Audit Settings'), array(
                            //     'controller' => 'workflows',
                            //     'action' => 'edit',
                            //     $auditSettingsWorkflowSettingsId
                            // ), array(
                            //     'escape' => false
                            // ));
                            ?>
                        </li>
                    </ul> -->
                </div>

                <?php echo $this->AdvancedFilters->getViewList($savedFilters, 'ComplianceAudit'); ?>

                <div class="btn-group group-merge">
                    <button class="btn dropdown-toggle" data-toggle="dropdown"><?php echo __('Settings'); ?> <span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <?php
                            echo $this->Html->link(__('Feedback'), array(
                                'controller' => 'complianceAuditFeedbacks',
                                'action' => 'index'
                            ), array(
                                'escape' => false,
                                'data-ajax-action' => 'index'
                            ));
                            ?>
                        </li>
                    </ul>
                </div>

                <?php
                echo $this->NotificationSystem->getIndexLink(array(
                    'ComplianceAudit' => __('Third Party Audit'),
                    'ComplianceFinding' => __('Audit Finding')
                ));
                ?>
                
                <?php echo $this->Video->getVideoLink('ComplianceAudit'); ?>

                <?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
            </div>
        </div>

        <?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'ComplianceAnalysisFinding')); ?>

        <?php if ( ! empty( $data ) ) : ?>
            <?php foreach ( $data as $entry ) : ?>
                <?php
                /* 
                    $today = CakeTime::format( 'Y-m-d', CakeTime::fromString( 'now' ) );
                    $findings_count = 0;
                    $assesed_count = 0;
                    $total_count = 0;
                    foreach ($entry['ComplianceFinding'] as $finding) {
                        if ($finding['type'] == COMPLIANCE_FINDING_AUDIT) {
                            $findings_count++;
                        }
                        else {
                            $assesed_count++;
                        }

                        $total_count++;
                    }

                    $open_count = 0;
                    $closed_count = 0;
                    $expired_count = 0;
                    foreach ( $entry['ComplianceFinding'] as $finding ) {
                        if ( $finding['compliance_finding_status_id'] == COMPLIANCE_FINDING_OPEN ) {
                            $open_count++;
                        }
                        if ( $finding['compliance_finding_status_id'] == COMPLIANCE_FINDING_CLOSED ) {
                            $closed_count++;
                        }

                        $status = 1;
                        if ($finding['compliance_finding_status_id'] == COMPLIANCE_FINDING_CLOSED) {
                            $status = 0;
                        }

                        if ($this->App->isExpired($finding['deadline']) && $finding['compliance_finding_status_id'] == COMPLIANCE_FINDING_OPEN &&  $finding['type'] == COMPLIANCE_FINDING_AUDIT) {
                            $expired_count++;
                        }
                    }

                    $settingsCount = count($entry['ComplianceAuditSetting']);
                    $noEvidenceNeeded = $waitingForEvidence = $evidenceProvided = 0;
                    foreach ($entry['ComplianceAuditSetting'] as $setting) {
                        if ($setting['status'] == COMPLIANCE_AUDIT_STATUS_NOT_EVIDENCE_NEEDED) {
                            $noEvidenceNeeded++;
                        }
                        if ($setting['status'] == COMPLIANCE_AUDIT_STATUS_WAITING_FOR_EVIDENCE) {
                            $waitingForEvidence++;
                        }
                        if ($setting['status'] == COMPLIANCE_AUDIT_STATUS_EVIDENCE_PROVIDED) {
                            $evidenceProvided++;
                        }
                    }

                    $assessed_percentage = $open_percentage = $closed_percentage = $expired_percentage = $noEvidence = $waitingEvidence = $providedEvidence = CakeNumber::toPercentage( 0, 2 );

                    if ($settingsCount) {
                        $noEvidencePrecentage = CakeNumber::toPercentage($noEvidenceNeeded/$settingsCount, 2, array('multiply' => true));
                        $waitingEvidencePercentage = CakeNumber::toPercentage($waitingForEvidence/$settingsCount, 2, array('multiply' => true));
                        $providedEvidencePercentage = CakeNumber::toPercentage($evidenceProvided/$settingsCount, 2, array('multiply' => true));
                    }

                    if ( $total_count ) {
                        $assessedDistinct = null;
                        if (isset($entry['ComplianceFindingDistinctAssessed'][0]['ComplianceFindingDistinctAssessed'][0]['count'])) {
                            $assessedDistinct = $entry['ComplianceFindingDistinctAssessed'][0]['ComplianceFindingDistinctAssessed'][0]['count'];
                        }
                        
                        $assessed_percentage = CakeNumber::toPercentage($assessedDistinct / count($entry['ComplianceAuditSetting']), 2, array('multiply' => true));
                        $open_percentage = CakeNumber::toPercentage( $open_count / $findings_count, 2, array( 'multiply' => true ) );
                        $closed_percentage = CakeNumber::toPercentage( $closed_count / $findings_count, 2, array( 'multiply' => true ) );
                        $expired_percentage = CakeNumber::toPercentage( $expired_count / $findings_count, 2, array( 'multiply' => true ) );
                    }
                */

                $auditInfo = getComplianceAuditCalculatedData($entry);
                // debug($auditInfo);
                ?>
                <div class="widget box widget-closed">
                    <div class="widget-header">
                        <h4><?php echo __( 'Audit' ); ?>: <?php echo $entry['ComplianceAudit']['name']; ?></h4>

                        <div class="toolbar no-padding">
                            <div class="btn-group">
                                <span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
                                <span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
                                </span>
                                <?php
                                $auditUrl = array(
                                    'controller' => 'complianceAudits',
                                    'action' => 'analyze',
                                    $entry['ComplianceAudit']['id']
                                );
                                $this->Ajax->addToActionList(__('Audit'), $auditUrl, 'search', 'index');

                                $disabled = true;
                                $notificationsUrl = '#';
                                if ($entry['ComplianceAudit']['hasAuditees']) {
                                    $disabled = false;
                                    $notificationsUrl = array(
                                        'controller' => 'complianceAuditSettings',
                                        'action' => 'sendNotifications',
                                        $entry['ComplianceAudit']['id']
                                    );
                                }
                                $this->Ajax->addToActionList(__('Send Notifications'), $notificationsUrl, 'envelope', false, array(
                                    'disabled' => $disabled
                                ));

                                $cloneUrl = array(
                                    'controller' => 'complianceAudits',
                                    'action' => 'duplicate',
                                    $entry['ComplianceAudit']['id']
                                );
                                $this->Ajax->addToActionList(__('Clone'), $cloneUrl, 'copy', false);

                                $exportUrl = array(
                                    'action' => 'export',
                                    $entry['ComplianceAudit']['id']
                                );
                                $this->Ajax->addToActionList(__('Export CSV'), $exportUrl, 'file', false);

                                $exportUrl = array(
                                    'action' => 'exportPdf',
                                    $entry['ComplianceAudit']['id']
                                );

                                $this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

                                if ($entry['ComplianceAudit']['status'] == COMPLIANCE_AUDIT_STOPPED) {
                                    $toggleUrl = array(
                                        'controller' => 'complianceAudits',
                                        'action' => 'start',
                                        $entry['ComplianceAudit']['id']
                                    );
                                    $this->Ajax->addToActionList(__('Start'), $toggleUrl, 'play-sign', false);
                                }

                                if ($entry['ComplianceAudit']['status'] == COMPLIANCE_AUDIT_STARTED) {
                                    $toggleUrl = array(
                                        'controller' => 'complianceAudits',
                                        'action' => 'stop',
                                        $entry['ComplianceAudit']['id']
                                    );
                                    $this->Ajax->addToActionList(__('Finish'), $toggleUrl, 'check', false);
                                }
                                
                                
                                echo $this->Ajax->getActionList($entry['ComplianceAudit']['id'], array(
                                    'item' => $entry,
                                    'notifications' => true,
                                    'history' => true,
                                    AppModule::instance('Visualisation')->getAlias() => true
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="widget-subheader">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered table-highlight-head table-head-wordwrap">
                                <thead>
                                    <tr>
                                        <th><?php echo __( 'Start Date' ); ?></th>
                                        <th><?php echo __( 'End Date' ); ?></th>
                                        <th><?php echo __( 'Auditor' ); ?></th>
                                        <th><?php echo __( 'Third Party Contact' ); ?></th>
                                        <th><?php echo __( 'Compliance Package' ); ?></th>
					<th>
                                          <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'This is the URL auditees will use to login at eramba in order to reply your questions. This URL will be included on email notifications.' ); ?>'>
                                                    <?php echo __( 'Portal URL' ); ?>
                                                    <i class="icon-info-sign"></i>
                                                </div>
					</th>
					<th>
                                          <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'You can edit audit settings using filters (clicking on this link) or at Manage / Audit.' ); ?>'>
                                                    <?php echo __( 'Audit Settings' ); ?>
                                                    <i class="icon-info-sign"></i>
                                                </div>
					</th>
                                        <th><?php echo __( 'Status' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $entry['ComplianceAudit']['start_date']; ?></td>
                                        <td><?php echo $entry['ComplianceAudit']['end_date']; ?></td>
                                        <td>
                                            <?php
                                            echo $this->Eramba->getEmptyValue($entry['Auditor']['full_name']);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $this->Eramba->getEmptyValue($entry['ThirdPartyContact']['full_name']);
                                            ?>
                                        </td>
                                        <td><?php echo $entry['ThirdParty']['name']; ?></td>
                                        <td>
                                        	<?php
                                            echo $this->ComplianceAudits->getAnalyzeLink($entry['ComplianceAudit']['id']);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $this->ComplianceAudits->outputSettingsLink($entry['ComplianceAudit']['id']);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $this->ComplianceAudits->getStatuses($entry, true);
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="widget-content" style="display:none;">
                        <div class="widget box widget-closed">
                            <div class="widget-header">
                                <h4><?php echo __('Evidence Statistics'); ?></h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group">
                                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content" style="display:none;">
                                <table class="table table-hover table-striped table-bordered table-highlight-head table-head-wordwrap">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Percentage of items that are under the scope of this audit and that stil have not the required evidence' ); ?>'>
                                                    <?php echo __( 'Waiting for Evidence' ); ?>
                                                    <i class="icon-info-sign"></i>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Percentage of items that are under the scope of this audit and that already have the required evidence' ); ?>'>
                                                    <?php echo __( 'Evidence Provided' ); ?>
                                                    <i class="icon-info-sign"></i>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Percentage of items that are under the scope of this audit that do not require evidence' ); ?>'>
                                                    <?php echo __( 'No Evidence Needed' ); ?>
                                                    <i class="icon-info-sign"></i>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $auditInfo['waitingEvidencePercentage'] . ' (' . $auditInfo['waitingForEvidence'] . ')'; ?></td>
                                            <td><?php echo $auditInfo['providedEvidencePercentage'] . ' (' . $auditInfo['evidenceProvided'] . ')'; ?></td>
                                            <td><?php echo $auditInfo['noEvidencePrecentage'] . ' (' . $auditInfo['noEvidenceNeeded'] . ')'; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="widget box widget-closed">
                            <div class="widget-header">
                                <h4><?php echo __( 'Audit Findings' ); ?></h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group">
                                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content" style="display:none;">
                                <?php if (!empty($auditInfo['findings_count'])) : ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The title for this non-compliance finding' ); ?>'>
                                                            <?php echo __( 'Title' ); ?>
                                                            <i class="icon-info-sign"></i>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Compliance item asociated with the Audit Findings' ); ?>'>
                                                            <?php echo __( 'Item' ); ?>
                                                            <i class="icon-info-sign"></i>
                                                        </div>
                                                    </th>
                                                    <th><?php echo __('Compliance Exception'); ?></th>
                                                    <th><?php echo __('Third Party Risk'); ?></th>
                                                    <th><?php echo __( 'Description' ); ?></th>
                                                    <th>
                                                        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The date by which this finding is expected to be corrected' ); ?>'>
                                                            <?php echo __( 'Deadline' ); ?>
                                                            <i class="icon-info-sign"></i>
                                                        </div>
                                                    </th>
                                                    <th><?php echo __('Labels'); ?></th>
                                                    <th>
                                                        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The status for an audit finding can be: "Expired (Red)" - when the date set is in the past. A system record is generated on the exception when that happens.' ); ?>'>
                                                            <?php echo __( 'Status' ); ?>
                                                            <i class="icon-info-sign"></i>
                                                        </div>
                                                    </th>
                                    <th class="align-center">
                                        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Use these icons in order to view the details of this object, system records such as when the item was created or modified, add or review comments or simply delete the item.' ); ?>'>
                                    <?php echo __( 'Actions' ); ?>
                                            <i class="icon-info-sign"></i>
                                        </div>
                                    </th>
                                    <!-- @todo When new workflows are available -->
                                    <?php /*
                                    <th class="align-center hidden">
                                        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Workflows define the approvals required to create, modify or delete an item. Approved items are valid throughout the system, Draft items require approval and Pending Approvals or Validations means that the workflow is still in process and is pending user interaction.' ); ?>'>
                                    <?php echo __( 'Workflows' ); ?>
                                            <i class="icon-info-sign"></i>
                                        </div>
                                    </th>
                                    */ ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ( $entry['ComplianceFinding'] as $finding ) : ?>
                                                    <?php if ($finding['type'] != COMPLIANCE_FINDING_AUDIT) continue; ?>
                                                    <tr>
                                                        <td><?php echo $finding['title']; ?></td>
                                                        <td><?php echo $finding['CompliancePackageItem']['item_id'] . ' - ' . $finding['CompliancePackageItem']['name']; ?></td>
                                                        <td>
                                                            <?php
                                                            $exceptions = Hash::extract($finding, 'ComplianceException.{n}.title');
                                                            echo $this->Eramba->getEmptyValue(implode(', ', $exceptions));
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $risks = Hash::extract($finding, 'ThirdPartyRisk.{n}.title');
                                                            echo $this->Eramba->getEmptyValue(implode(', ', $risks));
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            // echo $this->Eramba->getEmptyValue($finding['description']);
                                                            echo $this->Eramba->getTruncatedTooltip($finding['description'], array(
                                                                'title' => __('Description')
                                                            ));
                                                            ?>
                                                        </td>
                                                        <td><?php echo $finding['deadline']; ?></td>
                                                        <td>
                                                            <?php
                                                            $labels = array();
                                                            foreach ($finding['Classification'] as $label) {
                                                                $labels[] = $this->Html->tag('span', $label['name'], array(
                                                                    'class' => 'label label-primary'
                                                                ));
                                                            }
                                                            echo implode(' ', $labels);
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo $this->ComplianceFindings->getStatuses($finding);
                                                            ?>
                                                        </td>
                                                        <td class="align-center">
                                                            <?php
                                                            $exportUrl = array(
                                                                'controller' => 'complianceFindings',
                                                                'action' => 'exportPdf',
                                                                $finding['id']
                                                            );
                                                            $this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);
                                                            echo $this->Ajax->getActionList($finding['id'], array(
                                                                'style' => 'icons',
                                                                'controller' => 'complianceFindings',
                                                                'model' => 'ComplianceFinding',
                                                                'item' => $finding,
                                                                'notifications' => true
                                                            ));
                                                            ?>
                                                        </td>

                                                        <!-- @todo When new workflows are available -->
                                                        <?php /*
                                                        <td class="text-center hidden">
                                                            <?php
                                                            echo $this->element('workflow/action_buttons_1', array(
                                                                'id' => $finding['id'],
                                                                'item' => $this->Workflow->getActions($finding, $finding['WorkflowAcknowledgement']),
                                                                'currentModel' => 'ComplianceFinding'
                                                            ));
                                                            ?>
                                                        </td>
                                                        */ ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else : ?>
                                    <?php
                                    echo $this->element('not_found', array(
                                        'message' => __('Nothing found.')
                                    ));
                                    ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php /*
                        <div class="widget box widget-closed">
                            <div class="widget-header">
                                <h4><?php echo __('Finding Stats'); ?></h4>
                                <div class="toolbar no-padding">
                                    <div class="btn-group">
                                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content" style="display:none;">
                                <table class="table table-hover table-striped table-bordered table-highlight-head table-head-wordwrap">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'How many Audit findings (not compliant items) have been identified for this Audit' ); ?>'>
                                                    <?php echo __( 'Findings' ); ?>
                                                    <i class="icon-info-sign"></i>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Percentage of those open audit findings which have expired while still open' ); ?>'>
                                                    <?php echo __( 'Expired Findings' ); ?>
                                                    <i class="icon-info-sign"></i>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Assessed Items' ); ?>" data-content='<?php echo __( 'How many Assessed (audited and compliant items) items have been identified for this Audit' ); ?>'>
                                                    <?php echo __( 'Assessed Items' ); ?>
                                                    <i class="icon-info-sign"></i>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo __n( '%d Item', '%d Items', $auditInfo['findings_count'], $auditInfo['findings_count'] ); ?></td>
                                            <td><?php echo $auditInfo['expired_percentage'] . ' (' . $auditInfo['expired_count'] . ')'; ?></td>
                                            <td>
                                                <?php
                                                echo __n('%d Item', '%d Items', $auditInfo['assesed_count'], $auditInfo['assesed_count']) . ' (' . $auditInfo['assessed_percentage'] .')';
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        */ ?>
						
						<?php if (!empty($auditInfo['auditeeFeedbacks'])) : ?>
	                        <div class="widget box widget-closed">
	                            <div class="widget-header">
	                                <h4><?php echo __('Answers Statistics'); ?></h4>
	                                <div class="toolbar no-padding">
	                                    <div class="btn-group">
	                                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="widget-content" style="display:none;">
	                                <table class="table table-hover table-striped table-bordered table-highlight-head table-head-wordwrap">
	                                    <thead>
	                                        <tr>
	                                        	<?php foreach ($auditInfo['auditeeFeedbacks'] as $feedbackItem) : ?>
	                                        		<th><?php echo $feedbackItem['name']; ?></th>
	                                        	<?php endforeach; ?>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                        <tr>
	                                        	<?php foreach ($auditInfo['auditeeFeedbacks'] as $feedbackItem) : ?>
	                                        		<td>
	                                        			<?php
	                                        			$label = $feedbackItem['percentage'] . ' ' . __n('(%d Item)', '(%d Items)', $feedbackItem['count'], $feedbackItem['count']);
	                                        			if (empty($feedbackItem['id'])) {
                                        					echo $label;
	                                        			}
	                                        			else {
	                                        				echo $this->AdvancedFilters->getItemFilteredLink($label, 'ComplianceAuditSetting', null, array(
																'query' => array(
																	'compliance_audit_id' => $entry['ComplianceAudit']['id'],
																	'feedback_answers' => $feedbackItem['id']
																)
															));
														}
	                                        			?>
	                                        		</td>
	                                        	<?php endforeach; ?>
	                                        </tr>
	                                    </tbody>
	                                </table>
	                            </div>
	                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <?php echo $this->element( 'not_found', array(
                'message' => __( 'No Compliance Audits found.' )
            ) ); ?>
        <?php endif; ?>

    </div>

</div>
