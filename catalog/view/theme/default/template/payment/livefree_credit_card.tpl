<form id="payment" class="form-horizontal" action="https://livefreeltd.com/liveshop/liveshopCreditCard" method="post"> 
 <form id="payment" class="form-horizontal" action="http://localhost/new_livefree/public/liveshop/liveshopCreditCard" method="post"> -->

<fieldset>
    <legend><?php echo $text_credit_card; ?></legend>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-cc-card-type"><?php echo $entry_card_type; ?></label>
      <div class="col-sm-10">
        
        <input type="radio" name="ls_card_type" value="master_card" id="input-cc-master-card"  checked="checked"/><?php echo $entry_master_card; ?>
        <input type="radio" name="ls_card_type" value="visa_card" id="input-cc-visa-card" /><?php echo $entry_visa_card; ?>

      </div>
    </div>
    <div class="form-group required">
      <div class="col-sm-10">
        
        <input type="hidden" name="ls_amount" value="<?php echo $x_amount;?>" id="input-cc-amount" />
        <input type="hidden" name="ls_currency_code" value="<?php echo $x_currency_code;?>" id="input-cc-currency-code" />
        <input type="hidden" name="ls_currency_rate" value="<?php echo $x_currency_rate;?>" id="input-cc-currency-rate" />
        <input type="hidden" name="ls_product_name" value="<?php echo $x_product_name;?>" id="input-cc-product-name" />
        <input type="hidden" name="ls_product_category" value="<?php echo $x_category_name;?>" id="input-cc-product-name" />
        <input type="hidden" name="ls_products_count" value="<?php echo $x_products_count;?>" id="input-cc-product-name" />
        <input type="hidden" name="ls_order_id" value="<?php echo $x_order_id;?>" id="input-cc-order-id" />
        <input type="hidden" name="ls_user_im" value="<?php echo $x_user_im;?>" id="input-cc-user-im" />
        
       
      </div>
    </div>

  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$('#payment').submit();
// 	$.ajax({
// 		url: 'index.php?route=payment/livefree_credit_card/send',
// 		type: 'post',
// 		data: $('#payment').serialize(),
// 		dataType: 'json',
// 		cache: false,		
// 		beforeSend: function() {
// 			$('#button-confirm').button('loading');
// 		},
// 		complete: function() {
// 			$('#button-confirm').button('reset');
// 		},				
// 		success: function(json) {
// 			if (json['error']) {
// 				alert(json['error']);
// 			}
			
// 			if (json['redirect']) {
// 				location = json['redirect'];
// 			}
// 		}
// 	});
});
//--></script>