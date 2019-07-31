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
				<?php echo $this->Video->getVideoLink('RiskReport'); ?>
			</div>
		</div>
	</div>

</div>


<div class="row">
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Risk by Mitigation Strategy' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if (!empty($mitigationStrategies)) : ?>
					<div id="mitigationStrategies" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#mitigationStrategies", <?php echo json_encode($mitigationStrategies); ?>, FlotErambaDefaults);
					});
					</script>
				<?php else : ?>
					<?php
					echo $this->element('not_found', array(
						'message' => __('Nothing to show')
					));
					?>
				<?php
				endif; ?>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Risk by Status' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if (!empty($riskByStatus)) : ?>
					<div id="riskByStatus" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#riskByStatus", <?php echo json_encode($riskByStatus); ?>, FlotErambaDefaults);
					});
					</script>
				<?php else : ?>
					<?php
					echo $this->element('not_found', array(
						'message' => __('Nothing to show')
					));
					?>
				<?php
				endif; ?>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'For each identified asset, we show the distribution according to their labels' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Asset by Labels' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $asset_label ) ) : ?>
					<div id="asset_label" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#asset_label", <?php echo json_encode( $asset_label ); ?>, FlotErambaDefaults);
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
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'For each identified asset, we graph the distribution of liabiliites they carry' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Assets by Legal Constraints' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $legal ) ) : ?>
					<div id="legal_constrains" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#legal_constrains", <?php echo json_encode( $legal ); ?>, FlotErambaDefaults);
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
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'The sum of all Risk and Residaul Scores' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Assets: Risk and Residual Score' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $residual_score_overtime_list ) && ! empty( $risk_score_overtime_list ) ) : ?>
					<div id="risk_residual" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var series_multiple = [
							{
								label: "<?php echo __( 'Residual Risk' ); ?>",
								data: <?php echo json_encode( $residual_score_overtime_list ); ?>,
								color: App.getLayoutColorCode('red'),
								lines: {
									fill: true
								},
								points: {
									show: false
								}
							},{
								label: "<?php echo __( 'Risk Score' ); ?>",
								data: <?php echo json_encode( $risk_score_overtime_list ); ?>,
								color: App.getLayoutColorCode('blue')
							}
						];

						// Initialize flot
						var plot = $.plot("#risk_residual", series_multiple, $.extend(true, {}, Plugins.getFlotDefaults(), {
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
							xaxes: [ {mode: "time"} ]
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
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'For each Third Party Risks the sum of risk and residual scores.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Third Party: Risk and Residual Score' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $tp_residual_score_overtime_list ) && ! empty( $tp_risk_score_overtime_list ) ) : ?>
					<div id="tp_risk_residual" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var series_multiple = [
							{
								label: "<?php echo __( 'Residual Risk' ); ?>",
								data: <?php echo json_encode( $tp_residual_score_overtime_list ); ?>,
								color: App.getLayoutColorCode('red'),
								lines: {
									fill: true
								},
								points: {
									show: false
								}
							},{
								label: "<?php echo __( 'Risk Score' ); ?>",
								data: <?php echo json_encode( $tp_risk_score_overtime_list ); ?>,
								color: App.getLayoutColorCode('blue')
							}
						];

						// Initialize flot
						var plot = $.plot("#tp_risk_residual", series_multiple, $.extend(true, {}, Plugins.getFlotDefaults(), {
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
							xaxes: [ {mode: "time"} ]
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
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'The list of Asset risks sorted by the sum of their risk and residual score.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Asset Risks: Top Ten' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $top10_risk_score ) && ! empty( $top10_residual_score ) ) : ?>
					<div id="top_ten_risks" class="chart" style="height:500px;"></div>
					<script type="text/javascript">
					$(document).ready(function(){
					var ds = new Array();

					ds.push({
						label: "<?php echo __( 'Risk Score' ); ?>",
						data: <?php echo json_encode( $top10_risk_score ); ?>,
						bars: {
							show: true,
							barWidth: 0.2,
							order: 1
						}
					});
					ds.push({
						label: "<?php echo __( 'Residual Score' ); ?>",
						data: <?php echo json_encode( $top10_residual_score ); ?>,
						bars: {
							show: true,
							barWidth: 0.2,
							order: 2
						}
					});

					var top10_risk_names = <?php echo json_encode( $top10_risk_names ); ?>;

					// Initialize Chart
					$.plot("#top_ten_risks", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
						yaxes: [{
							tickFormatter: function(v, axis) {
								if ( typeof top10_risk_names[v] != "undefined" ) {
									//return top10_risk_names[v].replace(/ /gi, "<br />");
									return top10_risk_names[v];
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
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'The list of Third Party risks sorted by the sum of their risk and residual score.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Third Party Risks: Top Ten' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $top10_tp_risk_score ) && ! empty( $top10_tp_residual_score ) ) : ?>
					<div id="top_ten_tp_risks" class="chart" style="height:500px;"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						var ds = new Array();

						ds.push({
							label: "<?php echo __( 'Risk Score' ); ?>",
							data: <?php echo json_encode( $top10_tp_risk_score ); ?>,
							bars: {
								show: true,
								barWidth: 0.2,
								order: 1
							}
						});
						ds.push({
							label: "<?php echo __( 'Residual Score' ); ?>",
							data: <?php echo json_encode( $top10_tp_residual_score ); ?>,
							bars: {
								show: true,
								barWidth: 0.2,
								order: 2
							}
						});

						var top10_risk_names = <?php echo json_encode( $top10_tp_risk_names ); ?>;

						// Initialize Chart
						$.plot("#top_ten_tp_risks", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
							yaxes: [{
								tickFormatter: function(v, axis) {
									if ( typeof top10_risk_names[v] != "undefined" ) {
										//return top10_risk_names[v].replace(/ /gi, "<br />");
										return top10_risk_names[v];
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

<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Top 10 Risk Exceptions' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if (!empty($topRiskExceptions)) : ?>
					<?php
					$names = $riskScore = $residualScore = $totalScore = array();
					$key = 0;
					foreach ($topRiskExceptions as $item) {
						$names[$key] = $item['title'];
						$riskScore[$key] = array((int) $item['totalRisk'], (int) $key);
						$residualScore[$key] = array((int) $item['totalResidual'], (int) $key);
						// $totalScore[$key] = array((int) $item['totalScore'], (int) $key);

						$key++;
					}

					?>
					<div id="topRiskExceptions" class="chart" style="height:500px;"></div>
					<script type="text/javascript">
					$(document).ready(function(){
					var ds = new Array();

					ds.push({
						label: "<?php echo __('Risk Score'); ?>",
						data: <?php echo json_encode($riskScore); ?>,
						bars: {
							show: true,
							barWidth: 0.2,
							order: 1
						}
					});
					ds.push({
						label: "<?php echo __('Residual Score'); ?>",
						data: <?php echo json_encode($residualScore); ?>,
						bars: {
							show: true,
							barWidth: 0.2,
							order: 2
						}
					});

					var names = <?php echo json_encode($names); ?>;

					// Initialize Chart
					$.plot("#topRiskExceptions", ds, $.extend(true, {}, FlotErambaHorizontalDefaults, {
						yaxes: [{
							tickFormatter: function(v, axis) {
								if ( typeof names[v] != "undefined" ) {
									//return names[v].replace(/ /gi, "<br />");
									return names[v];
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

<div class="row">
	<div class="col-md-6">
		<div class="widget box">
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'We look at each assets asociated with each risk and which business units are asociated with this assets. We then split the amount of risk in equal parts to each business unit. Example: if a risk has a score of 10 and it\'s asociated assets point to two business units. We then assign 5 to each one of them. Only Asset Based Risks are considered.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Risk Originators by BU' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $risk_score ) ) : ?>
					<div id="risk_score" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#risk_score", <?php echo json_encode( $risk_score ); ?>, FlotErambaDefaults);
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
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'We look at the controls used to mitigate asset based risks and their resource utilization (days of work they require in order to work). We then look at which business units are using this assets and we split, in equal parts the amount of days.' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Consumed Resources by BU ' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $resource_utilization ) ) : ?>
					<div id="resource_utilization" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#resource_utilization", <?php echo json_encode( $resource_utilization ); ?>, FlotErambaDefaults);
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
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'How much OPEX is spent to mitigate Risk. We calculate this from the controls used to mitigate risk and their opex cost' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Consumed Opex by BU' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $opex ) ) : ?>
					<div id="opex" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#opex", <?php echo json_encode( $opex ); ?>, $.extend(true, {}, FlotErambaDefaults, {
							series: {
								pie: {
									label: {
										formatter: function(label, series) {
											return '<div class="pie-chart-label">' + label[0] + '<br/>' + label[1] + ' (' + Math.round(series.percent) + '%)</div>';
										}
									}
								}
							}
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
			<div class="widget-header bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Calculation' ); ?>" data-content="<?php echo __( 'How much CAPEX is spent to mitigate Risk. We calculate this from the controls used to mitigate risk and their capex cost' ); ?>">
				<h4><i class="icon-reorder"></i> <?php echo __( 'Consumed Capex by BU' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if ( ! empty( $capex ) ) : ?>
					<div id="capex" class="chart"></div>
					<script type="text/javascript">
					$(document).ready(function(){
						$.plot("#capex", <?php echo json_encode( $capex ); ?>, $.extend(true, {}, FlotErambaDefaults, {
							series: {
								pie: {
									label: {
										formatter: function(label, series) {
											return '<div class="pie-chart-label">' + label[0] + '<br/>' + label[1] + ' (' + Math.round(series.percent) + '%)</div>';
										}
									}
								}
							}
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