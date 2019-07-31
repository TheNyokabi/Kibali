<?php echo $this->element('modal_dashboard'); ?>

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<!-- <a href="#" class="btn" onClick="javascript:window.print();"><i class="icon-print"></i> <?php echo __( 'Print/Save' ); ?></a> -->
				</div>
				<?php echo $this->Video->getVideoLink('AwarenessReport'); ?>
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
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'What does it mean?' ); ?>" data-content="<?php echo __( 'We look who didnt do trainigs (check your directory against our records) to build the graph for each training.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Missing Trainings' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $awareness_stats['missing'] ) ) : ?>
					<div id="missing" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var series_multiple = <?php echo json_encode( $awareness_stats['missing'] ); ?>;

						// Initialize flot
						var plot = $.plot("#missing", series_multiple, FlotErambaOvertimeDefault);
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
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'What does it mean?' ); ?>" data-content="<?php echo __( 'Basically the opposite of the previous graph' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Compliant with trainings' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $awareness_stats['doing'] ) ) : ?>
					<div id="doing" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var series_multiple = <?php echo json_encode( $awareness_stats['doing'] ); ?>;

						// Initialize flot
						var plot = $.plot("#doing", series_multiple, FlotErambaOvertimeDefault);
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
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'What does it mean?' ); ?>" data-content="<?php echo __( 'For each training questionnaire we look the scores obtained and graph the average' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Questionnaire average score' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $awareness_stats['average'] ) ) : ?>
					<div id="average" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var series_multiple = <?php echo json_encode( $awareness_stats['average'] ); ?>;

						// Initialize flot
						var plot = $.plot("#average", series_multiple, FlotErambaOvertimeDefault);
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
