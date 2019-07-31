<?php echo $this->element('modal_dashboard'); ?>

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
</script>
<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="#" class="btn" onClick="javascript:window.print();"><i class="icon-print"></i> <?php echo __( 'Print/Save' ); ?></a>
				</div>
				<?php echo $this->Video->getVideoLink('SecurityControlReport'); ?>
			</div>
		</div>
	</div>

</div>

<div class="row">
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'How many controls are asociated to each classification' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Service Classifications' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $service_classification ) ) : ?>
					<div id="service_classification" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#service_classification", <?php echo json_encode( $service_classification ); ?>, FlotErambaDefaults);
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
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'The number of controls with, without released and in draft status policies.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Security Policies' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $security_policies ) ) : ?>
					<div id="security_policies" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#security_policies", <?php echo json_encode( $security_policies ); ?>, FlotErambaDefaults);
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
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('Controls by Status'); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if (!empty($controlsByStatus)) : ?>
					<div id="controlsByStatus" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#controlsByStatus", <?php echo json_encode($controlsByStatus); ?>, FlotErambaDefaults);
					});
					</script>
				<?php else : ?>
					<?php echo $this->element('not_found', array(
						'message' => __('Nothing to show')
					)); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __('Plans by Status'); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if (!empty($plansByStatus)) : ?>
					<div id="plansByStatus" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#plansByStatus", <?php echo json_encode($plansByStatus); ?>, FlotErambaDefaults);
					});
					</script>
				<?php else : ?>
					<?php echo $this->element('not_found', array(
						'message' => __('Nothing to show')
					)); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'The top 10 list of controls based on the number of items they mitigate (Controls and Risks)' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Top 10 Controls by leverage' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $top10_controls ) ) : ?>
					<div id="top_ten_used_security_services" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var ds = <?php echo json_encode( $top10_controls ); ?>;

						// Initialize Chart
						$.plot("#top_ten_used_security_services", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
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

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'List of controls without zero mitigation object' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Unused Controls' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $unused_controls ) ) : ?>
					<table class="table table-hover table-condensed">
						<thead>
							<tr>
								<th>#</th>
								<th><?php echo __( 'Name' ); ?></th>
								<th><?php echo __( 'Opex' ); ?></th>
								<th><?php echo __( 'Capex' ); ?></th>
								<th><?php echo __( 'Resource Utilization' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $unused_controls as $key => $security_service ) : ?>
								<tr>
									<td><?php echo ++$key; ?></td>
									<td><?php echo $security_service['SecurityService']['name']; ?></td>
									<td><?php echo CakeNumber::currency( $security_service['SecurityService']['opex'] ); ?></td>
									<td><?php echo CakeNumber::currency( $security_service['SecurityService']['capex'] ); ?></td>
									<td><?php echo $security_service['SecurityService']['resource_utilization']; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'No unused Security Services found.' )
					) ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'Top 10 controls based on the percentage of failed audits' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Top 10 Controls by the amount of Failed Audits' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $failed_audits ) ) : ?>
					<div id="failed_audits" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var ds = <?php echo json_encode( $failed_audits ); ?>;

						// Initialize Chart
						$.plot("#failed_audits", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
							yaxes: [{
								tickFormatter: function(v, axis) {
									return '';
								}
							}],
							tooltipOpts: {
								content: '%s: %x%'
							}
						}));
					});
					</script>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'Nothing to show.' )
					) ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'Top 10 Controls by the amount of Missing Audits' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Missing Audits' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $missed_audits ) ) : ?>
					<div id="missing_audits" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var ds = <?php echo json_encode( $missed_audits ); ?>;

						// Initialize Chart
						$.plot("#missing_audits", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
							yaxes: [{
								tickFormatter: function(v, axis) {
									return '';
								}
							}],
							tooltipOpts: {
								content: '%s: %x%'
							}
						}));
					});
					</script>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'Nothing to show.' )
					) ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
