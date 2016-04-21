<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
    <div id="social_login_content_holder"></div>
  <h1><?php echo $heading_title; ?></h1>
  <?php echo $content_top; ?>
      <div class="row">

				<?php if($store_id != 0 && $store_id != 1) { ?>	
			
        <div class="col-sm-6 margin-b">
            <div class="box-heading"><?php echo $text_new_customer; ?></div>
            <p><?php echo $text_register_account; ?></p>
            <a href="<?php echo $register; ?>" class="button"><?php echo $text_register; ?></a>
        </div>
        

				<?php } ?>	
			
        <div class="col-sm-6">
            <div class="box-heading"><?php echo $text_returning_customer; ?></div>
            <p><?php echo $text_i_am_returning_customer; ?></p>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                
				<label class="control-label" for="input-username"><?php echo $entry_username; ?></label>
                <input type="hidden" name="email" />
			
                
				<input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
			
              </div>
              <div class="form-group">
                <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />

				<?php if($store_id != 0 && $store_id != 1) { ?>	
			
                <a href="<?php echo $forgotten; ?>" class="pull-right login-forgotten"><?php echo $text_forgotten; ?></a></div>

				<?php } ?>
				<?php if($store_id == 0 || $store_id ==1) { ?>
				</div>
				<?php } ?>	
			
              <input type="submit" value="<?php echo $button_login; ?>" class="button" />
              <?php if ($redirect) { ?>
              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
              <?php } ?>
            </form>
        </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>