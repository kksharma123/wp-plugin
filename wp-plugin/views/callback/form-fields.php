<?php error_reporting(0);?>
<?php if( $args['type'] == 'text' ): ?>
<input 
    type="text" 
    name="<?php echo esc_attr( 'booking_form_settings' ); ?>[<?= $args['option_name'] ?>]" 
    class="widefat" 
    value="<?= esc_attr( get_option('booking_form_settings')[$args['option_name']] ) ?>" 
    id ="<?= $args['option_name']; ?>"
>
<?php endif; ?>

<?php if( $args['type'] == 'number' ): ?>
<input 
    type="number" 
    name="<?php echo esc_attr( 'booking_form_settings' ); ?>[<?= $args['option_name'] ?>]" 
    class="widefat" 
    value="<?= esc_attr( get_option('booking_form_settings')[$args['option_name']] ) ?>" 
    id ="<?= $args['option_name']; ?>"
    min = '0'
    step = '0.01'
>
<?php endif; ?>