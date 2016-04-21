<?php if($user_type == 'livefree') { ?>
<form id="payment" class="form-horizontal">
  <fieldset>
    <legend><?php echo $text_live_points; ?></legend>
    <div class="form-group ">
      <label class="col-sm-2 control-label" for="input-lp-im"><?php echo $lp_im; ?></label>
      <div class="col-sm-10">
        <input type="text" name="lp_im" value="<?php echo $lp_im_val?>" id="input-lp-im" class="form-control" readonly="readonly" />
        
        <div class="text-danger" id="lp_error" style="display: none"></div>
        
      </div>
    </div>
    <div id="lp_wrap_data" style="display: none;">
      <div class="form-group ">
        <label class="col-sm-2 control-label" for="input-lp-points-balance"><?php echo $lp_points_balance; ?></label>
        <div class="col-sm-10">
          <input type="text" name="lp_points_balance" value=""  id="input-lp-points-balance" class="form-control" readonly="readonly"/>
        </div>
      </div>
      <div class="form-group ">
        <label class="col-sm-2 control-label" for="input-lp-total-cart"><?php echo $lp_total_cart; ?></label>
        <div class="col-sm-10">
          <input type="text" name="lp_total_cart" value=""  id="input-lp-total-cart" class="form-control" readonly="readonly"/>
        </div>
      </div>
      <div class="form-group ">
        <label class="col-sm-2 control-label" for="input-lp-remaining-balance"><?php echo $lp_remaining_balance; ?></label>
        <div class="col-sm-10">
          <input type="text" name="lp_remaining_balance" value="" id="input-lp-remaining-balance" class="form-control" readonly="readonly" />
        </div>
      </div>
    </div>
     <div class="form-group " >
        <div class="col-sm-10">
        <div class="text-danger" id="lp_confirmation_error" style="display: none"></div>
        </div>
      </div>
  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_check; ?>" id="button-check" class="btn btn-primary" />
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" style="display: none;" />
  </div>
</div>
<?php } else if($user_type == 'livetoursplus') { ?>
<!-- Barclay Form -->
<form id="payment_points_barclay" class="form-horizontal" method="post" action="https://www.livetoursplus.com/Home/BuyPointsGatewayConfirm">
<fieldset>
    <legend><?php echo $text_live_points; ?></legend>
    <div id="barclay_data">
      <div class="form-group required">
      	<label class="col-sm-2 control-label" for="input-lp-im"><?php echo $lp_im; ?></label>
      	<div class="col-sm-10">
        	<input type="text" name="IMAccount" value="<?php echo $lp_im_val?>" id="input-lp-im" class="form-control" readonly="readonly" />
        	<div class="text-danger" id="lp_error" style="display: none"></div>
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-lp-points-balance"><?php echo $lp_points_balance; ?></label>
        <div class="col-sm-4">
          <input type="number" name="lp_points_balance" value="<?php echo $available_points; ?>"  id="input-available_points" class="form-control" min="1" pattern="^[0-9]" max="<?php echo $available_points;?>" required readonly="readonly" />
          <div class="text-danger" id="available_points_error" style="display: none"></div>
        </div>
        <label class="col-sm-2 control-label" for="input-lp-remaining-balance"><?php echo $lp_remaining_balance; ?></label>
        <div class="col-sm-4">
          <input type="text" name="lp_remaining_balance" value="<?php echo ($total_cart > $available_points ? 0 : $available_points - $total_cart); ?>" id="input-remaining_balance" class="form-control" readonly="readonly" />
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-paid-in-points"><?php echo $text_paid_in_points; ?></label>
        <div class="col-sm-4">
          <input type="number" min="1" pattern="^[0-9]" max="<?php echo ($total_cart > $available_points ? $available_points : $total_cart);?>" name="paid_in_points" value="<?php echo ($total_cart > $available_points ? $available_points : $total_cart);?>" id="input-paid-in-points" class="form-control" />
        </div>
        <label class="col-sm-2 control-label" for="input-paid-in-usd"><?php echo $text_paid_in_usd; ?></label>
          <div class="col-sm-4">
            <input type="text" name="totalAmount" value="0.0" id="input-paid-in-usd" class="form-control" readonly="readonly" />
          </div>
      </div>
      <div id="bank_information" style="display:none;">
        <legend><?php echo $text_barclay_information; ?></legend>
        <input type="hidden" name="torderID" value="<?php echo $orderID;?>" />
        <input type="hidden" name="buyCurrency" value="<?php echo $buy_currency;?>" />
        <input type="hidden" name="CCode" value="" />
        
        <div class="form-group required">
        	<label class="col-sm-2 control-label" for="input-customer_name"><?php echo $text_customer_name; ?></label>
        	<div class="col-sm-10">
          	<input type="text" name="CName" value="<?php echo $user_full_name?>" id="input-customer_name" class="form-control" readonly="readonly" required/>
          	<div class="text-danger" id="user_name_error" style="display: none"></div>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-customer_email"><?php echo $text_customer_email; ?></label>
          <div class="col-sm-10">
            <input type="text" name="CEmail" value="<?php echo $user_email;?>"  id="input-customer_email" class="form-control" readonly="readonly" required />
            <div class="text-danger" id="user_email_error" style="display: none"></div>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-customer_telephone"><?php echo $text_customer_telephone; ?></label>
          <div class="col-sm-10">
            <input type="text" name="CTelephone" value="<?php echo $user_phone;?>"  id="input-customer_telephone" class="form-control" required />
            <div class="text-danger" id="user_telephone_error" style="display: none"></div>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-customer_address"><?php echo $text_customer_address; ?></label>
          <div class="col-sm-10">
            <input type="text" name="CAddress" value="<?php echo $user_address;?>"  id="input-customer_address" class="form-control" required />
            <div class="text-danger" id="user_address_error" style="display: none"></div>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-customer_country"><?php echo $text_customer_country; ?></label>
          <div class="col-sm-10">
            <select name="CCountry" id="input-customer_country" class="form-control" required>
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($countries as $country) { ?>
            <?php if ($country['country_id'] == $country_id) { ?>
            <option value="<?php echo $country['name']; ?>" selected="selected"><?php echo $country['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $country['name']; ?>"><?php echo $country['name']; ?></option>
            <?php } ?>
            <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-customer_city"><?php echo $text_customer_city; ?></label>
          <div class="col-sm-10">
            <input type="text" name="CCity" value="<?php echo $user_city;?>"  id="input-customer_city" class="form-control" required />
            <div class="text-danger" id="user_city_error" style="display: none"></div>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-customer_zip"><?php echo $text_customer_zip; ?></label>
          <div class="col-sm-10">
            <input type="text" name="CZip" value="<?php echo $user_zip;?>"  id="input-customer_zip" class="form-control" required />
            <div class="text-danger" id="user_zip_error" style="display: none"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group " >
      <div class="col-sm-10">
        <div class="text-danger" id="lp_confirmation_error" style="display: none"></div>
      </div>
    </div>

</fieldset>
<div class="buttons" style="width:100%">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-proceed" class="btn btn-primary" style="display:none;" />
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm-ltp" class="btn btn-primary" />

  </div>
</div>
</form>
<!-- End Barclay From -->


<?php } ?>

<script type="text/javascript"><!--
$('#button-check').on('click', function() {
	$.ajax({
		url: 'index.php?route=payment/live_points/check_balance',
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
				  
				  $("#lp_error").hide();
				  
					$("#input-lp-points-balance").val(json['total_balance']);
					$("#input-lp-total-cart").val(json['total_cart']);
					$("#input-lp-remaining-balance").val(json['remaining_points']);
					
				  $("#lp_wrap_data").show('slow');
				  $('#button-check').hide();
				  $('#button-confirm').show();
				  
			}else{
				$("#lp_error").html(json['message']);
				$("#lp_error").show();
				$("#lp_wrap_data").hide();
					
			}
		}
	});
});


$('#button-confirm').on('click', function() {
	$.ajax({
		url: 'index.php?route=payment/live_points/checkout_order',
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
			console.log(json);
			if(json['status'] == 0){
				$("#lp_confirmation_error").html(json['message']);
				$("#lp_confirmation_error").show();

			}else{
				$("#button-confirm").attr("disabled", true);
				
  				//location = 'http://www.livemegastore.ae/index.php?route=checkout/success';
  				location = json['redirect'];
				//if (json['redirect']) {
  					//location = json['redirect'];
  			        //}	  			
			}
				

		}
	});
});


$('#button-confirm-ltp').on('click', function() {
  $.ajax({
    url: 'index.php?route=payment/live_points/checkout_order',
    type: 'post',
    data: $('#payment_points_barclay :input'),
    dataType: 'json',
    cache: false,   
    beforeSend: function() {
      $('#button-confirm-ltp').button('loading');
    },
    complete: function() {
      $('#button-confirm-ltp').button('reset');
    },        
    success: function(json) {
      console.log(json);
      if(json['status'] == 0){
        $("#lp_confirmation_error").html(json['message']);
        $("#lp_confirmation_error").show();

      }else{
        $("#button-confirm-ltp").attr("disabled", true);
        location = json['redirect'];  
      }
    }
  });
});

$('#input-paid-in-points').on('change keyup mouseup', function() {
  changeAmounts();
});

var changeAmounts = function () { 
  if($('#input-paid-in-points').val() < <?php echo $total_cart;?>) {
    $('#bank_information').show('slow');
    $('#input-remaining_balance').val(<?php echo $available_points;?> - $('#input-paid-in-points').val());
    $('#button-confirm-ltp').hide();
    $('#button-proceed').show();
    $('#input-paid-in-usd').attr('value', ((<?php echo $total_cart;?> - $('#input-paid-in-points').val())/<?php echo $x_currency_rate;?>).toFixed(2));
  } else {
    $('#bank_information').hide('slow');
    $('#input-remaining_balance').val(<?php echo $available_points - $total_cart;?>);
    $('#button-confirm-ltp').show();
    $('#button-proceed').hide();
    //$('#input-paid-in-usd').val('0.0');
  }
}
$('#button-proceed').on('click', function() { 

    if($('#input-available_points').val() < 1) {
      $('#available_points_error').show();
      $('#available_points_error').html('Amount must be greater than 0 !');
    } else {
      $.ajax({ 
        url: 'index.php?route=payment/live_barclay/pay',
        type: 'post',
        data: $('#payment_points_barclay :input'),
        dataType: 'json',
        cache: false,
        beforeSend: function() {
          $('#button-proceed').button('loading');
        },
        complete: function() { 
          //$(this).submit();
        },
        success: function(json) { // your success handler
          $('input[name=CCode]').val(json['CCode']);
          $('#payment_points_barclay').submit();
        }
      });
    }
});

//--></script>
