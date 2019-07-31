<?php
// add toolbar buttons for dashboard pages
$this->Dashboard->addToolbarBtns();
?>

<?php echo $this->element('modal_dashboard'); ?>

<div class="row">

	<div class="col-md-8 col-md-offset-2">
		<div class="widget">
			<div class="widget-content">
				<div id="calendar"></div>
			</div>
		</div>
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function(){

	//===== Calendar =====//
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();

	var h = {};

	if ($('#calendar').width() <= 400) {
		h = {
			left: 'title',
			center: '',
			right: 'prev,next'
		};
	} else {
		h = {
			left: 'prev,next',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		};
	}

	$('#calendar').fullCalendar({
		disableDragging: false,
		header: h,
		editable: false,
		events: <?php echo json_encode( $calendar_events ); ?>
	});

	});
</script>