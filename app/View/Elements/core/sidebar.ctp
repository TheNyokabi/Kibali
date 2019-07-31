<div id="sidebar" class="sidebar-fixed">
	<div id="sidebar-content">

		<ul id="nav">
			
			<?php $angle_sub_icon = '<i class="icon-angle-right"></i>'; ?>
			<?php foreach ($menuItems as $section) : ?>
				<?php	
				$class = '';		
				if (!empty($section['url']['controller']) && $section['url']['controller'] == $this->request->params['controller']) {
					$class = 'current open';
				}
				?>
				<li class="<?php echo $class; ?>">
					<?php
					$sectionName = $section['name'];
					if (empty($section['url'])) {
						$section['url'] = 'javascript:void(0);';
					}

					echo $this->Html->link($sectionName, $section['url'], array(
						'class' => $class,
						'escape' => false
					));
					?>

					<?php if (!empty($section['children'])) : ?>
						<ul class="sub-menu">
							<?php foreach ($section['children'] as $action) : ?>
								<?php
								$class = '';
								if ($action['url']['controller'] == $this->request->params['controller'] && $action['url']['action'] == $this->request->params['action']) {
									$class = 'current';
								}

								$liClass = $class;
								$aClass = $class;

								$title = $angle_sub_icon . $action['name'];
								$url = $action['url'];
								$aOptions = [
									'class' => $aClass,
									'escape' => false
								];

								if (isset($action['enterprise']) && $action['enterprise']) {
									$url = ERAMBA_ENTERPRISE_URL;
									$liClass = 'menu-item-enterprise';
									$aOptions['target'] = '_blank';
								}
								?>
								<li class="<?php echo $liClass; ?>">
									<?php
									echo $this->Html->link( 
										$title, 
										$url,
										$aOptions
									);
									?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
	<div id="divider" class="resizeable"></div>
</div>