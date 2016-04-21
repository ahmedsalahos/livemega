
<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
    <div class="row">
		  <div class="span12">
        <h4>
        <font color="red">
         <?php echo $text_kindly_note1.' ['.count($vouchers).'] '.$text_kindly_note2.', '.$text_kindly_note3.' ['.count($vouchers).'] '.$text_kindly_note4 ;?>
        </font>
        </h4>
        
      </div>
      </br>
      </br>
    </div>
     
<?php
$y = 1;
foreach ( $vouchers as $voucher_item ) {
$action = $request_url.'&voucher_number='.$voucher_item['voucher_number'].'&request_id='.$voucher_item['request_id'];


  ?>
      <div class='vouchers<?=$y?>' id="voucher<?= $voucher_item['voucher_number']?>">
  			<span class="Invoicespan"><?=$text_amount?> :</span>
  			<span class="Invoicespan"><?php echo $voucher_item['amount']?></span>
  			<span class="Invoicespan2">
  			 <a href="<?=$action;?>" title="pay" onClick="hideval(<?= $voucher_item['voucher_number']?>)" target="_BLANK"><?php echo $text_generate_invoice; ?></a>
  			</span>
    	</div>
    	</br> 
  
  <?php $y++; } ?></br> </br> 
     
       <div class="row">
      <div class="span12">
        <div class="alert alert-success fade in" style="display: none;">
          <a class="close" data-dismiss="alert" href="#">×</a>
          <?php echo $text_kindly_note3.' ['.count($vouchers).'] '.$text_kindly_note4 ;?> 
                    
        </div>
			</div>
		</div>  
     
     
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>

		<script>
function hideval(id){
	$("#voucher"+id+"").hide();
	
	 var count = <?=count($vouchers)?>;
		if($(".vouchers"+count).is(':hidden')){
		  $('.alert-success').show('slow');
		}
}

</script>


  

