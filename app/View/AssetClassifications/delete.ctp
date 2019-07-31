<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-content">
                <?php
                echo $this->Form->create($model, array(
                    'url' => array('controller' => $controller, 'action' => 'delete', $data[$model]['id']),
                    'class' => 'form-horizontal row-border',
                    'novalidate' => true
                ));

                echo $this->Form->input('_delete', array(
                    'type' => 'hidden',
                    'value' => 1
                ));
                ?>

                <div class="alert alert-danger">
                    <?php
                    echo __('Are you really sure you want to delete %s?', $data[$model][$displayField]);
                    ?>
                    <?php if (!empty($isUsed)) : ?>
						<br /><strong><?php echo __('This Classification is in use and deleting it will recalculate all Risks!'); ?></strong>
					<?php endif; ?>
                </div>

                <div class="form-actions">
                    <?php echo $this->Form->submit(__('Delete'), array(
                        'class' => 'btn btn-danger',
                        'div' => false
                    )); ?>
                    &nbsp;
                    <?php echo $this->Ajax->cancelBtn($model);?>
                </div>

                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>