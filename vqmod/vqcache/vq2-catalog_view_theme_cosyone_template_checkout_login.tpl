<div class="row">

				<?php if($store_id != 0 && $store_id !=1) { ?>	
			
  <div class="col-sm-6 margin-b">
    <h3><b><?php echo $text_new_customer; ?></b></h3>

                <!--
			
    <p class="contrast_font"><?php echo $text_checkout; ?></p>
    <div class="radio">
      <label>
        <?php if ($account == 'register') { ?>
        <input type="radio" name="account" value="register" checked="checked" />
        <?php } else { ?>
        <input type="radio" name="account" value="register" />
        <?php } ?>
        <?php echo $text_register; ?></label>
    </div>
    
    <?php if ($checkout_guest) { ?>
    <div class="radio">
      <label>
        <?php if ($account == 'guest') { ?>
        <input type="radio" name="account" value="guest" checked="checked" />
        <?php } else { ?>
        <input type="radio" name="account" value="guest" />
        <?php } ?>
        <?php echo $text_guest; ?></label>
    </div>
    <?php } ?>
    

                -->
			
    <p class="contrast_font"><?php echo $text_register_account; ?></p>
    <input type="button" value="<?php echo 
                $checkout_register_account
			; ?>" id="button-account" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>

				<?php } ?>	
			
  <div class="col-sm-6">
    <h3><b><?php echo $text_returning_customer; ?></b></h3>
    <p class="contrast_font"><?php echo $text_i_am_returning_customer; ?></p>
    <div class="form-group">
      
				<label class="control-label" for="input-username"><?php echo $entry_username; ?></label>
                <input type="hidden" name="email" />
			
      
			    <input type="text" name="username" value="" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
			
    </div>
    <div class="form-group">
      <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
      <input type="password" name="password" value="" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />

				<?php if($store_id != 0 && $store_id !=1) { ?>	
			
      <a class="pull-right login-forgotten" href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>

				<?php } ?>
				<?php if($store_id == 0 || $store_id == 1) { ?>
				</div>
				<?php } ?>	
			
    <input type="button" value="<?php echo $button_login; ?>" id="button-login" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
