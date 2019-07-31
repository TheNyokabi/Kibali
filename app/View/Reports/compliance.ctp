<?php echo $this->element('modal_dashboard'); ?>

<script type="text/javascript">
var FlotErambaHorizontalDefaults = $.extend(true, {}, Plugins.getFlotDefaults(), {
	series: {
		lines: { show: false },
		points: { show: false },
		bars: {
			fillColor: { colors: [ { opacity: 1 }, { opacity: 0.7 } ] },
			horizontal: true
		}
	},
	grid:{
		hoverable: true
	},
	tooltip: true,
	tooltipOpts: {
		content: '%s: %x'
	}
});
var FlotErambaOvertimeDefault = $.extend(true, {}, Plugins.getFlotDefaults(), {
	series: {
		lines: { show: true },
		points: { show: true },
		grow: { active: true }
	},
	grid: {
		hoverable: true,
		clickable: true
	},
	tooltip: true,
	tooltipOpts: {
		content: '%s: %y'
	},
	xaxes: [ {
		mode: "time"
	} ],
	yaxes: [{
		tickFormatter: function(v, axis) {
			return v.toFixed(0) + '%';
		}
	}]
});
</script>

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="#" class="btn" onClick="javascript:window.print();"><i class="icon-print"></i> <?php echo __( 'Print/Save' ); ?></a>
				</div>
				<?php echo $this->Video->getVideoLink('ComplianceReport'); ?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'For each compliance package, we count how many items are non-applicable, non-compliant and overlooked and we sort by the biggest.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Compliance Analysis Benchmark' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if (!empty($non_applicable_item_list)) : ?>
					<div id="third_party_analysis" class="chart" style="height:500px;"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var ds = new Array();

						ds.push({
							label: "<?php echo __( 'Non Applicable Items' ); ?>",
							data: <?php echo json_encode( $non_applicable_item_list ); ?>,
							bars: {
								show: true,
								barWidth: 0.2,
								order: 2
							}
						});
						ds.push({
							label: "<?php echo __( 'Overlooked Items' ); ?>",
							data: <?php echo json_encode( $overlooked_item_list ); ?>,
							bars: {
								show: true,
								barWidth: 0.2,
								order: 3
							}
						});
						ds.push({
							label: "<?php echo __( 'Non Compliant Items' ); ?>",
							data: <?php echo json_encode( $non_compliant_item_list ); ?>,
							bars: {
								show: true,
								barWidth: 0.2,
								order: 4
							}
						});

						var third_party_names = <?php echo json_encode( $third_party_names ); ?>;

						// Initialize Chart
						$.plot("#third_party_analysis", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
							yaxes: [{
								tickFormatter: function(v, axis) {
									if ( typeof third_party_names[v] != "undefined" ) {
										//return third_party_names[v].replace(/ /gi, "<br />");
										return third_party_names[v];
									}

									return '';
								}
							}],
							xaxes: [{
								tickFormatter: function(v, axis) {
									return v.toFixed(0) + '%';
								}
							}]
						}));
					});
					</script>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'Nothing to show' )
					) ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'Percentage of compliance package items without Controls or Policies mapped.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Compliance Packages by the percentage of items without controls' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $no_controls_overtime ) ) : ?>
					<div id="no_controls_overtime" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var series_multiple = <?php echo json_encode( $no_controls_overtime ); ?>;

						// Initialize flot
						var plot = $.plot("#no_controls_overtime", series_multiple, FlotErambaOvertimeDefault);
					});
					</script>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'Nothing to show' )
					) ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'Percentage of compliance package items with failed controls.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Compliance Packages by the percentage of items with failed (audit wise) controls' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $failed_controls_overtime ) ) : ?>
					<div id="failed_controls_overtime" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var series_multiple = <?php echo json_encode( $failed_controls_overtime ); ?>;

						// Initialize flot
						var plot = $.plot("#failed_controls_overtime", series_multiple, FlotErambaOvertimeDefault);
					});
					</script>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'Nothing to show' )
					) ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'The average effectiveness for each Compliance Package.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Compliance Packages by their Average Effectiveness' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $average_effectiveness_overtime ) ) : ?>
					<div id="average_effectiveness_overtime" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var series_multiple = <?php echo json_encode( $average_effectiveness_overtime ); ?>;

						// Initialize flot
						var plot = $.plot("#average_effectiveness_overtime", series_multiple, FlotErambaOvertimeDefault);

					});
					</script>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'Nothing to show' )
					) ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'For every compliance package, we calculate the sumatory of OPEX and CAPEX for every selected control used.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'OPEX, CAPEX, Resource Utilization' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $cost_list['opex'] ) ) : ?>
					<div id="opex_capex_resource_utilization" class="chart" style="height:500px;"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var ds = new Array();

						ds.push({
							label: "<?php echo __( 'OPEX' ); ?>",
							data: <?php echo json_encode( $cost_list['opex'] ); ?>,
							bars: {
								show: true,
								barWidth: 0.2,
								order: 1
							}
						});
						ds.push({
							label: "<?php echo __( 'CAPEX' ); ?>",
							data: <?php echo json_encode( $cost_list['capex'] ); ?>,
							bars: {
								show: true,
								barWidth: 0.2,
								order: 2
							}
						});
						ds.push({
							label: "<?php echo __( 'Resource Utilization' ); ?>",
							data: <?php echo json_encode( $cost_list['resource_utilization'] ); ?>,
							bars: {
								show: true,
								barWidth: 0.2,
								order: 3
							}
						});

						var third_party_names = <?php echo json_encode( $cost_list['names'] ); ?>;

						// Initialize Chart
						$.plot("#opex_capex_resource_utilization", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
							yaxes: [{
								tickFormatter: function(v, axis) {
									if ( typeof third_party_names[v] != "undefined" ) {
										//return third_party_names[v].replace(/ /gi, "<br />");
										return third_party_names[v];
									}

									return '';
								}
							}]
						}));
					});
					</script>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'Nothing to show' )
					) ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php foreach ( $compliance_audit_overtime as $key => $compliance ) : ?>
	<?php
	if ( empty( $compliance['ComplianceAuditOvertimeGraph'] ) ) {
		continue;
	}

	$open_findings = $closed_findings = $expired_findings = $noEvidence = $waitingEvidence = $providedEvidence = array();
	foreach ( $compliance['ComplianceAuditOvertimeGraph'] as $chart ) {
		$open_findings[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['open'] );
		$closed_findings[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['closed'] );
		$expired_findings[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['expired'] );

		$noEvidence[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['no_evidence'] );
		$waitingEvidence[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['waiting_evidence'] );
		$providedEvidence[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['provided_evidence'] );
	}
	$auditStatuses = getComplianceAuditSettingStatuses(false, false, true);

	$graph = array(
		array(
			'label' => __( 'Open Audit Findings' ),
			'data' => $open_findings
		),
		array(
			'label' => __( 'Closed Audit Findings' ),
			'data' => $closed_findings
		),
		array(
			'label' => __( 'Expired Audit Findings' ),
			'data' => $expired_findings
		),
		array(
			'label' => $auditStatuses[COMPLIANCE_AUDIT_STATUS_NOT_EVIDENCE_NEEDED],
			'data' => $noEvidence
		),
		array(
			'label' => $auditStatuses[COMPLIANCE_AUDIT_STATUS_WAITING_FOR_EVIDENCE],
			'data' => $waitingEvidence
		),
		array(
			'label' => $auditStatuses[COMPLIANCE_AUDIT_STATUS_EVIDENCE_PROVIDED],
			'data' => $providedEvidence
		)
	)
	?>

	<div class="row">
		<div class="col-md-12">
			<div class="widget box">
				<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'TBD' ); ?>" data-content="<?php echo __( 'TBD' ); ?>">
					<h4><i class="icon-reorder"></i> <?php echo $compliance['ComplianceAudit']['name']; ?></h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
						</div>
					</div>
				</div>
				<div class="widget-content">
					<div id="compliance_audit_<?php echo $key; ?>" class="chart"></div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		var series_multiple = <?php echo json_encode( $graph ); ?>;

		// Initialize flot
		var plot = $.plot("#compliance_audit_<?php echo $key; ?>", series_multiple, FlotErambaOvertimeDefault);

	});
	</script>
<?php endforeach; ?>

<?php foreach ($third_party_audit_overtime as $key => $tp) : ?>
	<?php
	if ( empty( $tp['ThirdPartyAuditOvertimeGraph'] ) ) {
		continue;
	}

	$open_findings = $closed_findings = $expired_findings = $noEvidence = $waitingEvidence = $providedEvidence = array();
	foreach ( $tp['ThirdPartyAuditOvertimeGraph'] as $chart ) {
		if ($chart['open'] !== null) {
			$open_findings[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['open'] );
		}
		if ($chart['closed'] !== null) {
			$closed_findings[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['closed'] );
		}
		if ($chart['expired'] !== null) {
			$expired_findings[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['expired'] );
		}

		$noEvidence[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['no_evidence'] );
		$waitingEvidence[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['waiting_evidence'] );
		$providedEvidence[] = array( (int) $chart['timestamp'] * 1000, (int) $chart['provided_evidence'] );
	}
	$auditStatuses = getComplianceAuditSettingStatuses(false, false, true);

	$graph = array();
	if (!empty($open_findings)) {
		$graph[] = array(
			'label' => __( 'Open Audit Findings' ),
			'data' => $open_findings
		);
	}

	if (!empty($closed_findings)) {
		$graph[] = array(
			'label' => __( 'Closed Audit Findings' ),
			'data' => $closed_findings
		);
	}

	if (!empty($expired_findings)) {
		$graph[] = array(
			'label' => __( 'Expired Audit Findings' ),
			'data' => $expired_findings
		);
	}

	$graph[] = array(
		'label' => $auditStatuses[COMPLIANCE_AUDIT_STATUS_NOT_EVIDENCE_NEEDED],
		'data' => $noEvidence
	);
	$graph[] = array(
		'label' => $auditStatuses[COMPLIANCE_AUDIT_STATUS_WAITING_FOR_EVIDENCE],
		'data' => $waitingEvidence
	);
	$graph[] = array(
		'label' => $auditStatuses[COMPLIANCE_AUDIT_STATUS_EVIDENCE_PROVIDED],
		'data' => $providedEvidence
	);

	/*$graph = array(
		array(
			'label' => __( 'Open Audit Findings' ),
			'data' => $open_findings
		),
		array(
			'label' => __( 'Closed Audit Findings' ),
			'data' => $closed_findings
		),
		array(
			'label' => __( 'Expired Audit Findings' ),
			'data' => $expired_findings
		),
		array(
			'label' => $auditStatuses[COMPLIANCE_AUDIT_STATUS_NOT_EVIDENCE_NEEDED],
			'data' => $noEvidence
		),
		array(
			'label' => $auditStatuses[COMPLIANCE_AUDIT_STATUS_WAITING_FOR_EVIDENCE],
			'data' => $waitingEvidence
		),
		array(
			'label' => $auditStatuses[COMPLIANCE_AUDIT_STATUS_EVIDENCE_PROVIDED],
			'data' => $providedEvidence
		)
	);*/
	?>

	<div class="row">
		<div class="col-md-12">
			<div class="widget box">
				<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'TBD' ); ?>" data-content="<?php echo __( 'TBD' ); ?>">
					<h4><i class="icon-reorder"></i> <?php echo $tp['ThirdParty']['name']; ?></h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
						</div>
					</div>
				</div>
				<div class="widget-content">
					<div id="third_party_audit_<?php echo $key; ?>" class="chart"></div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		var series_multiple = <?php echo json_encode( $graph ); ?>;

		// Initialize flot
		var plot = $.plot("#third_party_audit_<?php echo $key; ?>", series_multiple, FlotErambaOvertimeDefault);

	});
	</script>
<?php endforeach; ?>
