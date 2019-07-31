<?php 
// debug($update);
?>
<div id="updates-wrapper">
	<?php echo $this->element('updates/updateWidget'); ?>
</div>

<script type="text/javascript">
$(function() {

	function update(elem) {
		elem.attr('disabled', true);
		$('#updates-wrapper .progress').slideDown(300);

		$.ajax({
			url: elem.attr('href'),
		}).done(function(response) {
			$('#updates-wrapper .progress').slideUp(300, function() {
				$('#updates-wrapper').html(response);
			});
		}).always(function() {
		});
	}

	$('#updates-wrapper').on('click', '.btn-update', function() {
		update($(this));
		return false;
	});
});
</script>