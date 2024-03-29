<?php
echo $this->Html->script('demo/charts/chart_filled_blue', array('inline' => false));
?>
<div class="row">
	<div class="col-md-12">
		<!-- Tabs-->
		<div class="tabbable tabbable-custom tabbable-full-width">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_overview" data-toggle="tab">Overview</a></li>
				<li><a href="#tab_edit_account" data-toggle="tab">Edit Account</a></li>
			</ul>
			<div class="tab-content row">
				<!--=== Overview ===-->
				<div class="tab-pane active" id="tab_overview">

					<div class="col-md-12">
						<div class="row profile-info">
							<div class="col-md-7">
								<div class="alert alert-info">Notification message box.</div>

								<dl class="dl-horizontal">
									<dt><?php echo __('Name'); ?></dt>
									<dd><?php echo $data['Legal']['name']; ?></dd>
									<dt><?php echo __('Description'); ?></dt>
									<dd><?php echo $data['Legal']['description']; ?></dd>
									<dt><?php echo __('Risk Magnifier'); ?></dt>
									<dd><?php echo $data['Legal']['risk_magnifier']; ?></dd>
									<dt><?php echo __('Legal Advisor'); ?></dt>
									<dd>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</dd>
								</dl>

								<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt laoreet dolore magna aliquam tincidunt erat volutpat laoreet dolore magna aliquam tincidunt erat volutpat.</p>
							</div>
							<div class="col-md-5">
								<div class="widget">
									<div class="widget-header">
										<h4><i class="icon-reorder"></i> Sales</h4>
									</div>
									<div class="widget-content">
										<div id="chart_filled_blue" class="chart"></div>
									</div>
								</div>
							</div>

						</div> <!-- /.row -->
					</div> <!-- /.col-md-9 -->
				</div>
				<!-- /Overview -->

				<!--=== Edit Account ===-->
				<div class="tab-pane" id="tab_edit_account">
					<form class="form-horizontal" action="#">
						<div class="col-md-12">
							<div class="widget">
								<div class="widget-header">
									<h4>General Information</h4>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label">First name:</label>
												<div class="col-md-8"><input type="text" name="regular" class="form-control" value="John"></div>
											</div>

											<div class="form-group">
												<label class="col-md-4 control-label">Last name:</label>
												<div class="col-md-8"><input type="text" name="regular" class="form-control" value="Doe"></div>
											</div>
										</div>


										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label">Gender:</label>
												<div class="col-md-8">
													<select class="form-control">
														<option value="male" selected="selected">Male</option>
														<option value="female">Female</option>
													</select>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-4 control-label">Age:</label>
												<div class="col-md-8"><input type="text" name="regular" class="form-control input-width-small" value="28"></div>
											</div>
										</div>
									</div> <!-- /.row -->
								</div> <!-- /.widget-content -->
							</div> <!-- /.widget -->
						</div> <!-- /.col-md-12 -->

						<div class="col-md-12 form-vertical no-margin">
							<div class="widget">
								<div class="widget-header">
									<h4>Settings</h4>
								</div>
								<div class="widget-content">
									<div class="row">
										<div class="col-md-4 col-lg-2">
											<strong class="subtitle padding-top-10px">Permanent username</strong>
											<p class="text-muted">Dolor sit amet.</p>
										</div>

										<div class="col-md-8 col-lg-10">
											<div class="form-group">
												<label class="control-label padding-top-10px">Username:</label>
												<input type="text" name="username" class="form-control" value="john.doe" disabled="disabled">
											</div>
										</div>
									</div> <!-- /.row -->
									<div class="row">
										<div class="col-md-4 col-lg-2">
											<strong class="subtitle">Change password</strong>
											<p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
										</div>

										<div class="col-md-8 col-lg-10">
											<div class="form-group">
												<label class="control-label">Old password:</label>
												<input type="password" name="old_password" class="form-control" placeholder="Leave empty for no password-change">
											</div>

											<div class="form-group">
												<label class="control-label">New password:</label>
												<input type="password" name="new_password" class="form-control" placeholder="Leave empty for no password-change">
											</div>

											<div class="form-group">
												<label class="control-label">Repeat new password:</label>
												<input type="password" name="new_password_repeat" class="form-control" placeholder="Leave empty for no password-change">
											</div>
										</div>
									</div> <!-- /.row -->
								</div> <!-- /.widget-content -->
							</div> <!-- /.widget -->

							<div class="form-actions">
								<input type="submit" value="Update Account" class="btn btn-primary pull-right">
							</div>
						</div> <!-- /.col-md-12 -->
					</form>
				</div>
				<!-- /Edit Account -->
			</div> <!-- /.tab-content -->
		</div>
		<!--END TABS-->
	</div>
</div>