
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
     
<?php if($server == 'test'){
	?>
	 <script type="text/javascript">
voucher_number = <?= $voucher_number?>;
request_id = <?= $request_id?>;
lang = 'en';
</script>
<script type="text/javascript">
var _rnd = Math.random() ;
var _proto = (("https:" == document.location.protocol) ? "https:" : "http:");
document.write(unescape("%3Cscript src='" + _proto + "//sandbox.payfort.com/payat/show_voucher.js?" + _rnd + "'type='text/javascript'%3E%3C/script%3E"));
</script> 
	
<?php }else{
?>	

<script type="text/javascript">
voucher_number = <?= $voucher_number?>;
request_id = <?= $request_id?>;
lang = 'en';
</script>
<script type="text/javascript">
var _rnd = Math.random() ;
var _proto = (("https:" == document.location.protocol) ? "https:" : "http:");
document.write(unescape("%3Cscript src='" + _proto + "//account.payfort.com/payat/show_voucher.js?" + _rnd + "'type='text/javascript'%3E%3C/script%3E"));
</script>
<?php }?>


      
     
     


<!-- test -->


  
<!-- production -->
    <!--  

      
  -->
      <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
