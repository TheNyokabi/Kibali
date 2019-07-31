<?php
$i = 0;
?>
<?php foreach ($item['CompliancePackage'] as $package) : ?>
	<?php
	$i++;
	if (empty($package['CompliancePackageItem'])) continue;
	?>
	<div class="row">
		<div class="col-xs-12">

			<div class="header">
				<div class="title">
					<h1>
						<?php echo __('Compliance analysis'); ?>
					</h1>
				</div>
				<div class="subtitle">
					<h2>
						<?php echo __('Compliance Analysis Summary'); ?>
					</h2>
				</div>
			</div>

		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">

			<div class="body">
				
					<div class="item">
						<table class="decalogue-column">
							<tr>
								<th>
									<?php echo __('Item id'); ?>
								</th>
								<th>
									<?php echo __('Name'); ?>
								</th>
								<th>
									<?php echo __('Strategy'); ?>
								</th>
								<th>
									<?php echo __('Controls'); ?>
								</th>
								<th>
									<?php echo __('Policy Items'); ?>
								</th>
								<th>
									<?php echo __('Exceptions'); ?>
								</th>
								<th>
									<?php echo __('Description'); ?>
								</th>
								<th>
									<?php echo __('Risks'); ?>
								</th>
								<th>
									<?php echo __('Assets'); ?>
								</th>
								<th>
									<?php echo __('Liabilities'); ?>
								</th>
								<th>
									<?php echo __('Owner'); ?>
								</th>
								<th>
									<?php echo __('Active Status'); ?>
								</th>
							</tr>
							
							<?php
							$id = $package['package_id'];
							$name = $package['name'];
							?>
							<?php foreach ( $package['CompliancePackageItem'] as $cpItem ) : ?>
								<tr>
									<td>
										<?php echo $id; ?> 
									</td>
									<td>
										<?php echo $name; ?>  
									</td>
									<td>
										<?php if ( ! empty( $cpItem['ComplianceManagement'] ) ) : ?>
											<?php echo $strategies[ $cpItem['ComplianceManagement']['compliance_treatment_strategy_id'] ]; ?>
										<?php else : ?>
											-
										<?php endif; ?>
									</td>
									<td>
										<?php
										$securityServices = array();
										if (!empty($cpItem['ComplianceManagement']['SecurityService'])) {
											foreach ($cpItem['ComplianceManagement']['SecurityService'] as $control) {
												$securityServices[] = $control['name'];
											}
										}
										?>
										<?php if (!empty($securityServices)) : ?>
											<?php echo __('Yes'); ?>
										<?php else : ?>
											<?php echo __('No'); ?>
										<?php endif; ?>
									</td>
									<td>
										<?php
										$securityPolicies = array();
										if (!empty($cpItem['ComplianceManagement']['SecurityPolicy'])) {
											foreach ($cpItem['ComplianceManagement']['SecurityPolicy'] as $policy) {
												$securityPolicies[] = $policy['index'];
											}
										}
										?>
										<?php if (!empty($securityPolicies)) : ?>
											<?php echo __('Yes'); ?>
										<?php else : ?>
											<?php echo __('No'); ?>
										<?php endif; ?>
									</td>
									<td>
										<?php if (!empty($cpItem['ComplianceManagement']['ComplianceException'])) : ?>
											<?php echo __('Yes'); ?>
										<?php else : ?>
											<?php echo __('No'); ?>
										<?php endif; ?>
									</td>
									<td>
										<?php echo $this->Text->truncate($cpItem['description']); ?>  
									</td>
									<td>
										<?php
										$risks = array();
										if (!empty($cpItem['ComplianceManagement'])) {
											foreach ($cpItem['ComplianceManagement']['Risk'] as $risk) {
												$risks[] = $risk['title'];
											}
											foreach ($cpItem['ComplianceManagement']['ThirdPartyRisk'] as $risk) {
												$risks[] = $risk['title'];
											}
											foreach ($cpItem['ComplianceManagement']['BusinessContinuity'] as $risk) {
												$risks[] = $risk['title'];
											}
										}
										?>
										<?php if (!empty($risks)) : ?>
											<?php echo __('Yes'); ?>
										<?php else : ?>
											<?php echo __('No'); ?>
										<?php endif; ?>
									</td>
									<td>
										<?php if (!empty($cpItem['ComplianceManagement']['Asset'])) : ?>
											<?php echo __('Yes'); ?>
										<?php else : ?>
											<?php echo __('No'); ?>
										<?php endif; ?>
									</td>
									<td>
										<?php if (!empty($cpItem['ComplianceManagement']['legal_id'])) : ?>
											<?php echo __('Yes'); ?>
										<?php else : ?>
											<?php echo __('No'); ?>
										<?php endif; ?>
									</td>
									<td>
										<?php
										if (!empty($cpItem['ComplianceManagement']['Owner'])) {
											echo $cpItem['ComplianceManagement']['Owner']['name'] . ' ' . $cpItem['ComplianceManagement']['Owner']['surname'];
										}
										else {
											echo __('None');
										}
										?>
									</td>
									<td>
										<?php
										if (!empty($cpItem['ComplianceManagement'])) {
											echo $this->ComplianceManagements->getStatuses($cpItem['ComplianceManagement'], null, true);
										}
										else {
											echo $this->ComplianceManagements->getStatuses($cpItem['ComplianceManagement']);
										}
										?>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				
			</div>

		</div>
	</div>

	<?php if ($i < count($item['CompliancePackage'])) : ?>
		<pagebreak />
	<?php endif; ?>
<?php endforeach; ?>

<!-- <div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Compliance Analysis Summary'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<div class="item">
				<table class="triple-column">
					<tr>
						<th>
							<?php echo __('Strategy'); ?>
						</th>
						<th>
							<?php echo __('Owner'); ?>
						</th>
						<th>
							<?php echo __('Liabilities'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
						<td>
							Lorem ipsum
						</td>
						<td>
							Lorem ipsum
						</td>
					</tr>

				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Mitigation controls'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Policies'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Exceptions'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Risks'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Description'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
					</tr>
				</table>
			</div>

		</div>

	</div>
</div>


<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Compliance Analysis Summary'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<div class="item">
				<table class="triple-column">
					<tr>
						<th>
							<?php echo __('Strategy'); ?>
						</th>
						<th>
							<?php echo __('Owner'); ?>
						</th>
						<th>
							<?php echo __('Liabilities'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
						<td>
							Lorem ipsum
						</td>
						<td>
							Lorem ipsum
						</td>
					</tr>

				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Mitigation controls'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Policies'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Exceptions'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Risks'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Description'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							Lorem ipsum
						</td>
					</tr>
				</table>
			</div>

		</div>

	</div>
</div> -->


<?php echo $this->element('pdf_common_data'); ?>

