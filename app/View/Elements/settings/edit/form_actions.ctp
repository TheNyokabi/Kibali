<div class="form-actions">
    <?php echo $this->Form->submit( $submitLabel, array(
        'class' => 'btn btn-primary',
        'div' => false
    ) ); ?>
    &nbsp;
    <?php if(isset($redirectUrl)): ?>
        <?php $cancelUrl = json_decode($redirectUrl, true);?>
    <?php else:  ?>
        <?php $cancelUrl = array('action' => 'index')?>
    <?php endif; ?>

    <?php echo $this->Html->link( __( 'Cancel' ), $cancelUrl, array(
        'class' => 'btn btn-inverse'
    ) ); ?>
</div>