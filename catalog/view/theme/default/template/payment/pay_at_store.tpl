<form id="payment" class="form-horizontal" action="index.php?route=payment/pay_at_store/send" method="post">
<!--   <form id="payment" class="form-horizontal"  method="post"> -->
  
  <fieldset>
    <legend><?php echo $text_credit_card; ?></legend>
    <div class="form-group required">
      <div class="col-sm-10">
        <input type="hidden" name="pas_amount" value="<?php echo $x_amount;?>" id="pas_amount"  />
        <input type="hidden" name="pas_currency_code" value="<?php echo $x_currency_code;?>" id="pas_currency_code" />
        <input type="hidden" name="pas_currency_rate" value="<?php echo $x_currency_rate;?>" id="pas_currency_rate" />
        <input type="hidden" name="pas_product_name" value="<?php echo $x_product_name;?>" id="pas_product_name" />
        
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-pas-first-name"><?php echo $entry_pas_first_name; ?></label>
      <div class="col-sm-10">
        <input type="text" name="pas_first_name" value="<?php echo $x_first_name;?>" placeholder="<?php echo $entry_pas_first_name; ?>" id="pas_first_name" class="form-control" />
        <div class="text-danger" id="error_first_name"></div>
        
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-pas-last-name"><?php echo $entry_pas_last_name; ?></label>
      <div class="col-sm-10">
        <input type="text" name="pas_last_name" value="<?php echo $x_last_name;?>" placeholder="<?php echo $entry_pas_last_name; ?>" id="pas_last_name" class="form-control" />
        <div class="text-danger" id="error_last_name"></div>
        
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-pas-email"><?php echo $entry_pas_email; ?></label>
      <div class="col-sm-10">
        <input type="text" name="pas_email" value="<?php echo $x_email;?>" placeholder="<?php echo $entry_pas_email; ?>" id="pas_email" class="form-control" />
         <div class="text-danger" id="error_email"></div>
        
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-pas-phone"><?php echo $entry_pas_phone; ?></label>
      <div class="col-sm-10">
        <input type="text" name="pas_phone" value="<?php echo $x_phone;?>" placeholder="<?php echo $entry_pas_phone; ?>" id="pas_phone" class="form-control" />
        <div class="" id=""><?php echo $entry_kindly_phone_number;?></div>
        
        <div class="text-danger" id="error_phone"></div>
        
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-pas-address"><?php echo $entry_pas_address; ?></label>
      <div class="col-sm-10">
        <input type="text" name="pas_address" value="<?php echo $x_address;?>" placeholder="<?php echo $entry_pas_address; ?>" id="pas_address" class="form-control" />
        <div class="text-danger" id="error_address"></div>
        
      </div>
    </div>
   <!--  <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-pas-country"><?php echo $entry_pas_country; ?></label>
      <div class="col-sm-10">
        <input type="text" name="pas_country" value="<?php echo $x_country;?>" placeholder="<?php echo $entry_pas_country; ?>" id="pas_country" class="form-control" />
        <div class="text-danger" id="error_country"></div>
        
      </div>
    </div> 
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-pas-state"><?php echo $entry_pas_state; ?></label>
      <div class="col-sm-10">
        <input type="text" name="pas_state" value="<?php echo $x_state;?>" placeholder="<?php echo $entry_pas_state; ?>" id="pas_state" class="form-control" />
        <div class="text-danger" id="error_state"></div>
        
      </div>
    </div> -->
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-pas-city"><?php echo $entry_pas_city; ?></label>
      <div class="col-sm-10">
        <input type="text" name="pas_city" value="<?php echo $x_city;?>" placeholder="<?php echo $entry_pas_city; ?>" id="pas_state" class="form-control" />
        <div class="text-danger" id="error_city"></div>
        
      </div>
    </div>    
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-pas-post-code"><?php echo $entry_pas_post_code; ?></label>
      <div class="col-sm-10">
        <input type="text" name="pas_post_code" value="<?php echo $x_zip;?>" placeholder="<?php echo $entry_pas_post_code; ?>" id="pas_post_code" class="form-control" />
        <div class="text-danger" id="error_post_code"></div>
        
      </div>
    </div>
  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>

<div class="voucher">






</div>


<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	//$("#payment").submit();
	$.ajax({
		url: 'index.php?route=payment/pay_at_store/validate',
		type: 'post',
		data: $('#payment').serialize(),
		dataType: 'json',
		cache: false,		
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},				
		success: function(json) {
			if (json['error']) {
				console.log(json['error']);
				//console.log(json['error']['phone']);
				//alert(json['error']);
				$("#error_first_name").html(json['error']['first_name']);
				$("#error_last_name").html(json['error']['last_name']);
				$("#error_email").html(json['error']['email']);
				$("#error_phone").html(json['error']['phone']);
				$("#error_address").html(json['error']['address']);
				$("#error_country").html(json['error']['country']);
				$("#error_state").html(json['error']['state']);
				$("#error_city").html(json['error']['city']);
				$("#error_post_code").html(json['error']['post_code']);
				
			}
			
			if (json['redirect']) {
				$("#payment").submit();
			}
		}
	});
});
//--></script>
