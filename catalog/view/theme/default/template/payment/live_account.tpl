<form id="payment" class="form-horizontal">
  <fieldset>
    <legend><?php echo $text_live_account; ?></legend>
    <div class="form-group required" id="la_im_wrap">
      <label class="col-sm-2 control-label" for="input-la-im"><?php echo $entry_la_im; ?></label>
      <div class="col-sm-10">
        <input type="text" name="la_im" value="" placeholder="<?php echo $entry_la_im; ?>" id="input-la-im" class="form-control" />
        <div class="text-danger" id="la_im_error" style="display: none"></div>
      </div>
    </div>
    <div id="la_sec_data" style="display: none;">
      <div class="form-group required" >
        <label class="col-sm-2 control-label" for="input-la-security-answer"><?php echo $entry_la_security_answer; ?></label>
        <div class="col-sm-10">
          <input type="password" name="la_security_answer" value="" placeholder="<?php echo $entry_la_security_answer; ?>" id="input-la-security-answer" class="form-control" />
          <div class="text-danger" id="la_security_answer_error" style="display: none"></div>
        </div>
      </div>
      <div class="form-group required" >
        <label class="col-sm-2 control-label" for="input-la-security-word"><?php echo $entry_la_security_word; ?></label>
        <div class="col-sm-10">
          <input type="password" name="la_security_word" value="" placeholder="<?php echo $entry_la_security_word; ?>" id="input-la-security_word" class="form-control" />
          <div class="text-danger" id="la_security_word_error" style="display: none"></div>
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-la-pin-code"><?php echo $entry_la_pin_code; ?></label>
        <div class="col-sm-10">
          <input type="password" name="la_pin_code" value="" placeholder="<?php echo $entry_la_pin_code; ?>" id="input-la-pin-code" class="form-control" />
          <div class="text-danger" id="la_pin_code_error" style="display: none"></div>
        </div>
      </div>
      <div class="text-danger" id="la_confirmation_error" style="display: none"></div>
      
    </div>
  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_check; ?>" id="button-check" class="btn btn-primary" />
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" style="display: none;" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-check').on('click', function() {
	$.ajax({
		url: 'index.php?route=payment/live_account/check_balance',
		type: 'post',
		data: $('#payment :input'),
		dataType: 'json',
		cache: false,		
		beforeSend: function() {
			$('#button-check').button('loading');
		},
		complete: function() {
			$('#button-check').button('reset');
		},				
		success: function(json) {
			
			if (json['error']) {
				alert(json['error']);
			}
			
			if (json['redirect']) {
				location = json['redirect'];
			}
			if(json['status'] == 1){
				  
				  $("#la_im_error").hide();
				  $("#la_sec_data").show();
				  $('#button-check').hide();
				  $('#button-confirm').show();
				  
				}else
				{
					$("#la_im_error").html(json['message']);
					$("#la_im_error").show();
					$("#la_sec_data").hide();
					
					}
		}
	});
});


$('#button-confirm').on('click', function() {
	$.ajax({
		url: 'index.php?route=payment/live_account/checkout',
		type: 'post',
		data: $('#payment :input'),
		dataType: 'json',
		cache: false,		
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},				
		success: function(json) {
			//console.log(json);
			if(json['status'] == 1){
				$("#button-confirm").attr("disabled", true);
				
  			if (json['redirect']) {
  				
  				location = json['redirect'];
  			}
			}else{
				$("#la_confirmation_error").html(json['message']);
				$("#la_confirmation_error").show();
			}
				

		}
	});
});
//--></script>