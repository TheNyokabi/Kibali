<?php if (!empty($customFields_available)) : ?>

	<?php foreach ($customFields_data as $group) : ?>
		<?php if (empty($group['CustomField'])) continue; ?>

		<li class="default-tab pull-right">
			<a href="#<?php echo $this->CustomFields->getGroupAlias($group['CustomForm']['slug']); ?>" data-toggle="tab">
				<?php echo $group['CustomForm']['name']; ?>
			</a>
		</li>

	<?php endforeach; ?>

<?php endif; ?>