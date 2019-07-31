<?php echo $this->element('modal_dashboard'); ?>

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="#" class="btn" onClick="javascript:window.print();"><i class="icon-print"></i> <?php echo __( 'Print/Save' ); ?></a>
				</div>
				<?php echo $this->Video->getVideoLink('SecurityOperationReport'); ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var FlotErambaDefaults = $.extend(true, {}, Plugins.getFlotDefaults(), {
	series: {
		pie: {
			show: true,
			radius: 1,
			label: {
				show: true,
				formatter: function(label, series) {
					return '<div class="pie-chart-label">' + label + '<br/>' + series.data[0][1] + ' (' + Math.round(series.percent) + '%)</div>';
				}
			}
		}
	},
	grid: {
		hoverable: true
	},
	tooltip: true,
	tooltipOpts: {
		content: '%p.0%, %s', // show percentages, rounding to 2 decimal places
		shifts: {
			x: 20,
			y: 0
		}
	}
});
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
	xaxes: [ {mode: "time"} ],
});
</script>

<div class="row">
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'List of open projects by the number of days that passed after the expiration day' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Expired Projects' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $ongoing_project_list1 ) ) : ?>
					<div id="ongoing_project_list1" class="chart"></div>

					<script type="text/javascript">
					$(document).ready(function(){
						var ds = <?php echo json_encode( $ongoing_project_list1 ); ?>;

						// Initialize Chart
						$.plot("#ongoing_project_list1", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
							yaxes: [{
								tickFormatter: function(v, axis) {
									return '';
								}
							}],
							xaxes: [{
								tickFormatter: function(v, axis) {
									return v + ' days';
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

	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'List of open projects by the number of days until the expiration day' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Days to deadline' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $ongoing_project_list2 ) ) : ?>
					<div id="ongoing_project_list2" class="chart"></div>

					<script type="text/javascript">
					$(document).ready(function(){
						var ds = <?php echo json_encode( $ongoing_project_list2 ); ?>;

						// Initialize Chart
						$.plot("#ongoing_project_list2", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
							yaxes: [{
								tickFormatter: function(v, axis) {
									return '';
								}
							}],
							xaxes: [{
								tickFormatter: function(v, axis) {
									return v + ' days';
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
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'The number of Incidents by their status' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Security Incident by status' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $security_incident_statuses ) ) : ?>
					<div id="security_incident_statuses" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#security_incident_statuses", <?php echo json_encode( $security_incident_statuses ); ?>, FlotErambaDefaults);
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

	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Classification' ); ?>" data-content="<?php echo __( 'The number of incidents by their classification' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Classification' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $security_incident_classification ) ) : ?>
					<div id="security_incident_classification" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#security_incident_classification", <?php echo json_encode( $security_incident_classification ); ?>, FlotErambaDefaults);
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
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Classification' ); ?>" data-content="<?php echo __( 'The number of incidents asociated to each controls' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Controls Involved' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $controls_incidents ) ) : ?>
					<div id="controls_incidents" class="chart"></div>

					<script type="text/javascript">
					$(document).ready(function(){
						var ds = <?php echo json_encode( $controls_incidents ); ?>;

						// Initialize Chart
						$.plot("#controls_incidents", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
							yaxes: [{
								tickFormatter: function(v, axis) {
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
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'The number of incidents asociated to each asset' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Assets Involved' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $assets_incidents ) ) : ?>
					<div id="assets_incidents" class="chart"></div>

					<script type="text/javascript">
					$(document).ready(function(){
						var ds = <?php echo json_encode( $assets_incidents ); ?>;

						// Initialize Chart
						$.plot("#assets_incidents", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
							yaxes: [{
								tickFormatter: function(v, axis) {
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
