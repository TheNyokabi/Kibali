<div class="row">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-content">

                <?php
                App::uses('SystemHealthLib', 'Lib');
                $backupsWritable = SystemHealthLib::writeBackups();

                $systemHealthIssue = false;
                ?>
                <?php if (empty($backupsWritable)) : ?>
                    <div class="alert alert-danger">
                        <?php
                        $systemHealthIssue = true;

                        $systemHealthLink = $this->Html->link(__('System Health'), array(
                            'controller' => 'settings',
                            'action' => 'systemHealth'
                        ));

                        echo __('Your backups folder is not accessible or writable by the system! Please go to %s and fix this issue.', $systemHealthLink);
                        ?>
                    </div>
                <?php endif; ?>

                <?php
                    echo $this->Form->create( 'Setting', array(
                        'url' => array( 'controller' => 'settings', 'action' => 'edit', $slug ),
                        'class' => 'form-horizontal row-border'
                    ) );

                    $submit_label = __( 'Edit' );
                ?>

                <?php foreach ($settingGroup['Setting'] as $key => $setting): ?>
                    <?php
                    $formGroupClass = 'form-group';
                    if ($key == 0) {
                        $formGroupClass .= ' form-group-first';
                    }
                    ?>
                    <div class="<?php echo $formGroupClass; ?>">
                        <label class="col-md-2 control-label"><?php echo $setting['name'] ; ?>:</label>
                        <div class="col-md-2">
                            <?php echo $this->element('settings/edit/input', array('setting' => $setting)); ?>

                            <span class="help-block"><?php echo isset($notes[$setting['variable']])?$notes[$setting['variable']]:''; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php
                // additional group elements for specific functionality
                $groupElement = 'settings/edit/group/' . $settingGroup['SettingGroup']['slug'];
                if($this->elementExists($groupElement)) {
                    echo $this->element($groupElement);
                }
                ?>

                <?php if(isset($redirectUrl)): ?>
                <?php echo $this->Form->hidden('redirectUrl', array(
                    'value' => $redirectUrl
                ));?>
                <?php endif;  ?>

                <?php echo $this->element('settings/edit/form_actions', array('submitLabel' => $submit_label)); ?>

                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(function($) {
        var $backupEnableToggle = $("#SettingBACKUPSENABLED");

        <?php if ($systemHealthIssue) : ?>

            // we disable the "Enable backups" checbkox in case the system health has issues
            $backupEnableToggle.prop("disabled", true);
            $.uniform.update($backupEnableToggle);

        <?php endif; ?>

        $("#SettingEditForm").find("select").removeClass("form-control").addClass("col-md-12").select2({
            minimumResultsForSearch: -1
        });
    });
</script>