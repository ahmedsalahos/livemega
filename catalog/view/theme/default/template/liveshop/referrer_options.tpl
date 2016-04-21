<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>

<p><?php echo $text_checkout_set_referrer; ?></p>
<div class="radio">
  <label>
    <input type="radio" name="referrer_options" value="with-referrer" />
    <?php echo $entry_buy_with_referrer; ?>
  </label>
</div>
<div class="radio">  
  <label>
    <input type="radio" name="referrer_options" value="without-referrer" checked="checked"  />
    <?php echo $entry_buy_without_referrer; ?>
  </label>
</div>

<div class="form-group">
    <div class="col-sm-2 referrer">
      <input type="text" name="referrer" value="<?php echo $referrer; ?>" placeholder="<?php echo $entry_referrer; ?>" id="input-referrer" class="form-control" />
    </div>
  </div>
    <div class="buttons">
        <div class="right">
            <input id="check_live_account" class="button" type="button" value="Check">
        </div>
    </div>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-referrer-options" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<input type="hidden" id="ref_flag" value="true" />
<script>
$(document).ready(function(){
      $('#input-referrer,#check_live_account').hide();
});

$(document).on('change', 'input[name=\'referrer_options\']', function() {
  if(this.value == 'with-referrer') {
    $('#input-referrer,#check_live_account').show();
    $('#ref_flag').val('false');
  } else if (this.value == 'without-referrer') {
    $('#input-referrer,#check_live_account').hide();
    $('#input-referrer').val('');
    $('#ref_flag').val('true');
  }
});
</script>
