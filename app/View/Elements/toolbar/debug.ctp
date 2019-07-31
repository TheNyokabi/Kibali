<?php
$hasIssues = 0;
$queryLog = $this->Eramba->getQueryLogs();		
$extraClass1 = '';
if ((int) $queryLog['count'] > 1000 || $queryLog['time'] > 1000) {
	$hasIssues++;
	$extraClass1 = 'warning';
}

$scriptExecutionTime = scriptExecutionTime()*1000;
$extraClass2 = '';
if ($scriptExecutionTime > 2500) {
	$hasIssues++;
	$extraClass2 = 'warning';
}

$memoryWarning = 55000000; //55mb
App::uses('DebugMemory', 'DebugKit.Lib');
$memory1 = DebugMemory::getCurrent();
$extraClass3 = '';
if ($memory1 > $memoryWarning) {
	$hasIssues++;
	$extraClass3 = 'warning';
}

$memory2 = DebugMemory::getPeak();		
$extraClass4 = '';
if ($memory2 > $memoryWarning) {
	$hasIssues++;
	$extraClass4 = 'warning';
}

$debug = [
	__('SQL Queries') => [
		'icon' => 'icon-tasks',
		'issue' => (bool) $extraClass1,
		'value' => sprintf('%d queries (%sms)', $queryLog['count'], $queryLog['time'])
	],
	__('Request Time') => [
		'icon' => 'icon-time',
		'issue' => (bool) $extraClass2,
		'value' => sprintf('%sms', CakeNumber::precision($scriptExecutionTime, 0))
	],
	__('Current Memory') => [
		'icon' => 'icon-bolt',
		'issue' => (bool) $extraClass3,
		'value' => CakeNumber::toReadableSize($memory1)
	],
	__('Peak Memory') => [
		'icon' => 'icon-linux',
		'issue' => (bool) $extraClass4,
		'value' => CakeNumber::toReadableSize($memory2)
	],
];
?>
<li class="dropdown debug-dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
		<i class="icon-warning-sign"></i> 
		<?php
		$class = 'badge';
		if ($hasIssues != 0) {
			$class = 'badge badge-danger';
		}
		?>
		<span class="<?php echo $class; ?>"><?php echo $hasIssues; ?></span> 
	</a>
	<ul class="dropdown-menu extended pull-right">
		<li class="title">
			<p><?php echo sprintf(__n('You have %d issue', 'You have %d issues', $hasIssues), $hasIssues); ?></p>
		</li>
		<li>
			<?php foreach ($debug as $title => $data) : ?>
				<a href="javascript:void(0);">
					<span class="label label-<?php echo $data['issue'] ? 'danger' : 'info'; ?>">
						<i class="<?php echo $data['icon']; ?>"></i>
					</span> 
					<span class="message"><?php echo $title; ?></span> 
					<span class="time <?php echo $data['issue'] ? 'time-danger' : ''; ?>"><?php echo $data['value']; ?></span>
				</a>
			<?php endforeach; ?>
		</li>

		<li class="footer">
			<a href="#" disabled="disabled">View all</a>
		</li>
	</ul>
</li>

<script type="text/javascript">
	jQuery(function($) {
		$(".debug-dropdown").detach().appendTo($(".toolbar-buttons"));
	});
</script>