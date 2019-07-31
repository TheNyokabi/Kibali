<?php
App::uses('RiskClassification', 'Model');
App::uses('RiskCalculation', 'Model');

$classModel = 'RiskClassification';
if ($type == RiskClassification::TYPE_TREATMENT) {
	$classModel = 'RiskClassificationTreatment';
}

if (isset($this->data[$model][$classModel]) && is_array($this->data[$model][$classModel])) {
	$classificationIds = $this->data[$model][$classModel];
	
}
elseif (isset($this->data[$classModel])) {
	$classificationIds = [];
	foreach ($this->data[$classModel] as $object) {
		$classificationIds[] = $object['id'];
	}
}
?>

<script type="text/javascript">
jQuery(function($) {
	var changeEvent = "change.RiskCalculation.<?= $element ?>";
	$(".risk-classifications-trigger, .risk-classification-select")
		.off(changeEvent)
		.on(changeEvent, function(e) {
			reloadClassifications($("<?= $element ?>"));
		});

	<?php if (isset($justLoaded) && $justLoaded) : ?>
		$(".related-risk-item-input").trigger(changeEvent);
	<?php endif; ?>

	function reloadClassifications(ele) {
		var $ele = ele;
		App.blockUI($ele);

		var $classificationSelect = $ele.find(".risk-classification-select");

		var relatedItemIds = [];
		$.each($(".related-risk-item-input option:selected"), function(i, e) {
			relatedItemIds.push($(e).val());
		});

		var processIds = [];
		$.each($(".related-process-input option:selected"), function(i, e) {
			processIds.push($(e).val());
		});

		var classificationIds = [];
		$.each($classificationSelect, function(i, e) {
			classificationIds.push($(e).val());
		});

		var buChange = 0;
		if ($(this).is("#risk-bu-id")) {
			buChange = 1;
		}

		var postData = {
			model: "<?= $model ?>",
			type: "<?= $type ?>",
			element: "<?= $element ?>",
			relatedItemIds: JSON.stringify(relatedItemIds),
			processIds: JSON.stringify(processIds),
			classificationIds: JSON.stringify(classificationIds),
			buChange: buChange
		};

		$.ajax({
			type: "GET",
			url: "<?= Router::url(['controller' => controllerFromModel($model), 'action' => 'processClassifications']) ?>",
			data: postData
		})
		.done(function(data){
			$ele.html(data);
			Plugins.init();
			App.unblockUI($ele);

			var riskScore = $("#risk_score_scope").val();
			var riskScoreMath = $("#risk_score_math_scope").val();

			<?php if ($type == RiskClassification::TYPE_ANALYSIS) : ?>
				updateRiskScoreField(riskScore, riskScoreMath);
			<?php endif; ?>

			<?php if ($model == 'BusinessContinuity') : ?>
				var rpd = $("#rpd_scope").val();
				var mto = $("#mto_scope").val();
				var rto = $("#rto_scope").val();
				var process = $("#process_scope").val();

				$("#rpd-input").val(rpd);
				$("#mto-input").val(mto);
				$("#rto-input").val(rto);

				if (buChange) {
					$("#BusinessContinuityProcess").select2('data', JSON.parse(process));
				}
			<?php endif; ?>

			App.init();
			Eramba.Ajax.UI.attachEvents();
			$("#content").trigger("Eramba.Risk.processClassifications");
		});

		function updateRiskScoreField(riskScore, math) {
			var riskAppetite = <?= Configure::read('Eramba.Settings.RISK_APPETITE') ?>

			var $riskScoreInput = $("#risk-score-input");
			var $riskScoreMath = $("#risk-score-math");

			$riskScoreInput.val(riskScore);
			var riskMath = '';
			if (math) {
				riskMath = "<br />";
				riskMath += "<div class='alert alert-info'>";
				riskMath += math;
				riskMath += "</div>";
			}
			$riskScoreMath.html(riskMath);

			if (riskScore > riskAppetite) {
				if (!$("#risk-score-group").hasClass("has-risk-error")) {
					$("#risk-score-group").addClass("has-risk-error");
				}
			}
			else {
				$("#risk-score-group").removeClass("has-risk-error");
			}
		}
	}
});
</script>

<?php
$conds = (isset($classificationsNotPossible) && $classificationsNotPossible) || !isset($classificationIds);
$conds &= empty($this->request->data);
if ($conds) {
	echo $this->Ux->getAlert(__('Classifications cannot be configured until you select mandatory prerequisitions in this form.'), ['type' => 'danger']);

	return false;
}

// if magerit is not possible to manage at the moment
$mageritImpossible = !empty($calculationMethod);
$mageritImpossible &= $calculationMethod == RiskCalculation::METHOD_MAGERIT;
$mageritImpossible &= isset($isMageritPossible) && !$isMageritPossible;
if ($mageritImpossible) {
	echo $this->Ux->getAlert(__('While using Magerit as a calculation method you need to ensure that:<ul><li>All asset classifications have a value (Asset Management / Settings / Classifications)</li><li>All assets (Asset Management / Asset Identification) must be classified</li></ul><br />This message shows as it seems one or both conditions seem to have issues.'), ['type' => 'danger']);

	return false;
}

if (!isset($classificationIds)) {
	return false;
}

if (!empty($classifications)) : ?>
	<?php foreach ($classifications as $key => $classification_type) : ?>
		<?php
		$selected = null;
		if (isset($classificationIds) && isset($classificationIds[$key])) {
			$selected = $classificationIds[$key];
		}

		$fieldOptions = [
			'classification_type' => $classification_type,
			'key' => $key,
			'model' => $model,
			'classModel' => $classModel,
			'selected' => $selected
		];
		?>

		<div class="row form-group">
			<?php if (!empty($calculationMethod) && $calculationMethod == RiskCalculation::METHOD_MAGERIT) : ?>
				<label class="col-md-3 control-label asset-max-val">
					<?php
					if (isset($otherData['assetMaxVal'])) {
						$val = null;
						if (!empty($otherData['assetMaxVal'][$key]['maxValue'])) {
							$val = $otherData['assetMaxVal'][$key]['maxValue'];
						}

						$val = $this->Ux->text($val);
						$text = sprintf('%s: %s', $otherData['assetMaxVal'][$key]['assetType'], $val);

						echo $this->Ux->text($text);
					}
					?>
				</label>
				<div class="col-md-5">
					<?php
					echo $this->element('risks/risk_classifications/classification_item_ajax', $fieldOptions);
					?>
				</div>

				<div class="col-md-3 col-md-offset-1 math-classification math-item">
					<?php
					if (isset($otherData['classificationsPartMath'])) {
						$text = null;
						if (!empty($otherData['classificationsPartMath'][$key])) {
							$text = $otherData['classificationsPartMath'][$key];
						}

						echo $this->Ux->getAlert($this->Ux->text($text), [
							'type' => 'info'
						]);
					}
					?>
				</div>
			<?php else : ?>
				<div class="col-md-12">
					<?php
					echo $this->element('risks/risk_classifications/classification_item_ajax', $fieldOptions);
					?>
				</div>
			<?php endif; ?>
		</div>

	<?php endforeach; ?>
<?php else : ?>
	<?php
	echo $this->Ux->getAlert(__('We haven\'t seen any classification method defined, go to Settings / Classification and define a classification criteria for your risks.'), ['type' => 'info']);
	?>
<?php endif; ?>

<?php
// final math
$finalMath = null;
if (isset($otherData['classificationsSecondPartMath'])) {
	$finalMath = $this->Ux->getAlert($this->Ux->text($otherData['classificationsSecondPartMath']), [
		'type' => 'info'
	]);

	$finalMath = $this->Html->div('math-item', $finalMath, [
		'id' => 'classification-final-math',
		'escape' => false
	]);
}
?>

<?php if (isset($specialClassificationTypeData)) : ?>
	<div class="row form-group">
		<div class="col-md-5">
			<?php
			$selected = $classificationIds[$key+1];

			echo $this->element('risks/risk_classifications/classification_item_ajax', array(
				'classification_type' => $specialClassificationTypeData,
				'key' => $key+1,
				'model' => $model,
				'classModel' => $classModel,
				'selected' => $selected
			));
			?>
		</div>
		<div class="col-md-7">
			<?= $finalMath; ?>
		</div>
	</div>
<?php else : ?>
	<?= $finalMath; ?>
<?php endif; ?>

<?php if ($appetiteMethod == RiskAppetite::TYPE_THRESHOLD) : ?>
<div class="risk-appetite-threshold-note">
	<div class='alert threshold-alert label-' style='background-color:<?= $riskAppetiteThreshold['data']['color']; ?>'>
		<strong><?= $riskAppetiteThreshold['data']['title'] ?></strong><br />
		<?= $riskAppetiteThreshold['data']['description'] ?>
	</div>
</div>
<?php endif; ?>

<?php
if ($this->Form->isFieldError($model . '.' . $classModel)) {
	echo $this->Form->error($model . '.' . $classModel);
}
?>

<?php
// fields that should update outside of the ajax scope

echo $this->Form->input('risk_score_scope', [
	'type' => 'hidden',
	'value' => $riskScore
]);

echo $this->Form->input('risk_score_math_scope', [
	'type' => 'hidden',
	'value' => $riskCalculationMath
]);

if ($model == 'BusinessContinuity') {
	echo $this->Form->input('rpd_scope', [
		'type' => 'hidden',
		'value' => $rpd
	]);

	echo $this->Form->input('mto_scope', [
		'type' => 'hidden',
		'value' => $mto
	]);

	echo $this->Form->input('rto_scope', [
		'type' => 'hidden',
		'value' => $rto
	]);

	echo $this->Form->input('process_scope', [
		'type' => 'hidden',
		'value' => json_encode($process)
	]);
}
?>
