<!-- Header -->
<header class="header navbar" role="banner">
	<!-- Top Navigation Bar -->
	<div class="container">

		<!-- Only visible on smartphones, menu toggle -->
		<ul class="nav navbar-nav">
			<li class="nav-toggle"><a href="javascript:void(0);" title=""><i class="icon-reorder"></i></a></li>
		</ul>

		<!-- Logo -->
		<!--<a class="navbar-brand" href="<?php echo Router::url( array('plugin' => null, 'controller' => 'welcome', 'action' => 'index') ); ?>">
			<strong>E</strong>RAMBA
			<span class="version">v<?php //echo VERSION; ?></span>
		</a>-->
		<a id="logo" class="navbar-brand" href="<?php echo Router::url( array('controller' => 'pages', 'action' => 'welcome', 'admin' => false, 'plugin' => null) ); ?>">
			<?php echo $this->Eramba->getLogo(); ?>
		</a>
		<!-- /logo -->

		<!-- Sidebar Toggler
		<a href="#" class="toggle-sidebar bs-tooltip" data-placement="bottom" data-original-title="Toggle navigation">
			<i class="icon-reorder"></i>
		</a> -->
		<!-- /Sidebar Toggler -->

		<?php echo $this->element(CORE_ELEMENT_PATH . 'menu'); ?>

		<!-- Top Right Menu -->
		<ul class="nav navbar-nav navbar-right">

			<?php echo $this->element(CORE_ELEMENT_PATH . 'news'); ?>

			<?php echo $this->element(CORE_ELEMENT_PATH . 'notifications'); ?>

			<!-- Tasks
			<li class="dropdown hidden-xs hidden-sm">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="icon-tasks"></i>
					<span class="badge">7</span>
				</a>
				<ul class="dropdown-menu extended notification">
					<li class="title">
						<p>You have 7 pending tasks</p>
					</li>
					<li>
						<a href="javascript:void(0);">
							<span class="task">
								<span class="desc">Preparing new release</span>
								<span class="percent">30%</span>
							</span>
							<div class="progress progress-small">
								<div style="width: 30%;" class="progress-bar progress-bar-info"></div>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);">
							<span class="task">
								<span class="desc">Change management</span>
								<span class="percent">80%</span>
							</span>
							<div class="progress progress-small progress-striped active">
								<div style="width: 80%;" class="progress-bar progress-bar-danger"></div>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);">
							<span class="task">
								<span class="desc">Mobile development</span>
								<span class="percent">60%</span>
							</span>
							<div class="progress progress-small">
								<div style="width: 60%;" class="progress-bar progress-bar-success"></div>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);">
							<span class="task">
								<span class="desc">Database migration</span>
								<span class="percent">20%</span>
							</span>
							<div class="progress progress-small">
								<div style="width: 20%;" class="progress-bar progress-bar-warning"></div>
							</div>
						</a>
					</li>
					<li class="footer">
						<a href="javascript:void(0);">View all tasks</a>
					</li>
				</ul>
			</li>-->

			<!-- Messages
			<li class="dropdown hidden-xs hidden-sm">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="icon-envelope"></i>
					<span class="badge">1</span>
				</a>
				<ul class="dropdown-menu extended notification">
					<li class="title">
						<p>You have 3 new messages</p>
					</li>
					<li>
						<a href="javascript:void(0);">
							<span class="photo"><img src="assets/img/demo/avatar-1.jpg" alt="" /></span>
							<span class="subject">
								<span class="from">Bob Carter</span>
								<span class="time">Just Now</span>
							</span>
							<span class="text">
								Consetetur sadipscing elitr...
							</span>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);">
							<span class="photo"><img src="assets/img/demo/avatar-2.jpg" alt="" /></span>
							<span class="subject">
								<span class="from">Jane Doe</span>
								<span class="time">45 mins</span>
							</span>
							<span class="text">
								Sed diam nonumy...
							</span>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);">
							<span class="photo"><img src="assets/img/demo/avatar-3.jpg" alt="" /></span>
							<span class="subject">
								<span class="from">Patrick Nilson</span>
								<span class="time">6 hours</span>
							</span>
							<span class="text">
								No sea takimata sanctus...
							</span>
						</a>
					</li>
					<li class="footer">
						<a href="javascript:void(0);">View all messages</a>
					</li>
				</ul>
			</li> -->

			<!-- .row .row-bg Toggler
			<li>
				<a href="#" class="dropdown-toggle row-bg-toggle">
					<i class="icon-resize-vertical"></i>
				</a>
			</li> -->

			<!-- Project Switcher Button
			<li class="dropdown">
				<a href="#" class="project-switcher-btn dropdown-toggle">
					<i class="icon-folder-open"></i>
					<span>Projects</span>
				</a>
			</li>-->

			<!-- User Login Dropdown -->
			<li class="dropdown user">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<!--<img alt="" src="assets/img/avatar1_small.jpg" />-->
					<i class="icon-male"></i>
					<span class="username"><?php echo $logged['name'] .' '. $logged['surname']; ?></span>
					<i class="icon-caret-down small"></i>
				</a>
				<ul class="dropdown-menu">
					<li>
						<?php
						echo $this->Html->link(
							'<i class="icon-user"></i> '. __('My Profile'),
							array('controller' => 'users', 'action' => 'profile', 'admin' => false, 'plugin' => null),
							array('escape' => false)
						);
						?>
					</li>
					<!--<li><a href="pages_calendar.html"><i class="icon-calendar"></i> My Calendar</a></li>
					<li><a href="#"><i class="icon-tasks"></i> My Tasks</a></li>-->
					<li class="divider"></li>
					<li>
						<?php
						echo $this->Ux->logoutBtn();
						?>
					</li>
				</ul>
			</li>
			<!-- /user login dropdown -->
		</ul>
		<!-- /Top Right Menu -->
	</div>
	<!-- /top navigation bar -->

	<!--=== Project Switcher ===-->
	<!-- <div id="project-switcher" class="container project-switcher">
		<div id="scrollbar">
			<div class="handle"></div>
		</div>

		<div id="frame">
			<ul class="project-list">
				<li>
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-desktop"></i></span>
						<span class="title">Lorem ipsum dolor</span>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-compass"></i></span>
						<span class="title">Dolor sit invidunt</span>
					</a>
				</li>
				<li class="current">
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-male"></i></span>
						<span class="title">Consetetur sadipscing elitr</span>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-thumbs-up"></i></span>
						<span class="title">Sed diam nonumy</span>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-female"></i></span>
						<span class="title">At vero eos et</span>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-beaker"></i></span>
						<span class="title">Sed diam voluptua</span>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-desktop"></i></span>
						<span class="title">Lorem ipsum dolor</span>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-compass"></i></span>
						<span class="title">Dolor sit invidunt</span>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-male"></i></span>
						<span class="title">Consetetur sadipscing elitr</span>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-thumbs-up"></i></span>
						<span class="title">Sed diam nonumy</span>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-female"></i></span>
						<span class="title">At vero eos et</span>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);">
						<span class="image"><i class="icon-beaker"></i></span>
						<span class="title">Sed diam voluptua</span>
					</a>
				</li>
			</ul>
		</div>
	</div> -->
</header> <!-- /.header -->