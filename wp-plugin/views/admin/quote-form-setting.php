<div class="ctbp-wrapper ctbp-formWrapper">
    <div class="ctbp-header">
    </div>
    <div class="ctbp-content">
       <div id="gallery-list" class="ctbp-gallery-list">
         <div class="ctbp-section-right">
           <div class="ctbp-galllery-wrapper">
               <form method="post" action="<?php echo admin_url( 'options.php' ); ?>">
               <?php settings_fields( 'booking_form_settings' ); ?>
               <?php do_settings_sections('cs_1'); ?>
               <?php submit_button( 'Save Form' ); ?>
              </form>
            </div>
            </div>

            
        </div>
        </div>
    </div>
</div>
 

