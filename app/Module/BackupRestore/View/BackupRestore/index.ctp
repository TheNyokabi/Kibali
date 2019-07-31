<div class="row">

    <div class="col-md-9">
        <div class="widget">
            <div class="btn-toolbar">
                <div class="btn-group">
                    <a href="<?php echo Router::url( array( 'action' => 'getBackup' ) ); ?>" class="btn">
                        <i class="icon-file"></i>
                        <?php echo __( 'Download Backup File' ); ?>
                    </a>
                </div>
                <?php echo $this->Video->getVideoLink('BackupRestore'); ?>
            </div>
        </div>
    </div>

</div>

<?php echo $this->Eramba->getNotificationBox('Keep in mind backups only include the system database - you must backup attachments separately (from app/webroot/files/ directory) from your operating system.'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="widget box" id="backup-restore-container">
            <div class="widget-content">

                <?php
                echo $this->Form->create( 'BackupRestore', array(
                    'url' => array( 'controller' => 'backupRestore', 'action' => 'index' ),
                    'class' => 'form-horizontal row-border',
                    'type' => 'file',
                    'id' => 'backup-restore-form'
                ) );

                $submit_label = __( 'Restore' );
                ?>

                <div class="form-group form-group form-group-first">
                    <label class="col-md-2 control-label"><?php echo __( 'Restore Database' ); ?>:</label>
                    <div class="col-md-10">
                        <?php echo $this->Form->input( 'ZipFile', array(
                            'type' => 'file',
                            'label' => false,
                            'div' => false,
                            'class' => false,
                            'data-style' => 'fileinput',
                            'required' => false
                        ) ); ?>
                        <span class="help-block">
                            <?php echo __( 'Upload your ZIP file here.' ); ?><br />
                            <?php echo __('Maximum filesize for upload is configured to %s.', ini_get('post_max_size')); ?>
                        </span>
                    </div>
                </div>

                <div class="form-actions">
                    <?php echo $this->Form->submit( $submit_label, array(
                        'class' => 'btn btn-primary',
                        'div' => false
                    ) ); ?>
                    &nbsp;
                    <?php
                    echo $this->Html->link(__('Cancel'), array(
                        'controller' => 'settings',
                        'action' => 'index',
                        'plugin' => null
                    ), array(
                        'class' => 'btn btn-inverse'
                    ));
                    ?>
                </div>

                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(function($) {
        var $form = $("#backup-restore-form");

        $form.on("submit", function(e) {
            setPseudoNProgress();
            App.blockUI($("#backup-restore-container"));
        });
    });
</script>