<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-shippings" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-shippings" class="form-horizontal">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <tbody>
                    <tr>
                      <td class="text-left"><?php echo $entry_shippings; ?></td>
                      <td class="text-left"><select name="shipping" class="form-control">
                          <option value=""></option>
                          <?php foreach ($shippings as $shipping) { ?>
                          <?php if (isset($extension_id) && $extension_id == $shipping['extension_id']) { ?>
                          <option value="<?php echo $shipping['extension_id']; ?>" selected="selected"><?php echo $shipping['code']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $shipping['extension_id']; ?>"><?php echo $shipping['code']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php foreach ($stores as $store) { ?>
                    <tr>
                      <td class="text-left"><?php echo $entry_stores; ?></td>
                      <td class="text-left">
                        <div class="well well-sm" style="height: 150px; overflow: auto;">
                          <div class="checkbox">
                            <label>
                            <?php if (in_array(0, $shipping_store)) { ?>
                              <input type="checkbox" name="shipping_store[]" value="0" checked="checked" />
                              <?php echo $text_default; ?>
                            <?php } else { ?>
                              <input type="checkbox" name="shipping_store[]" value="0" />
                              <?php echo $text_default; ?>
                            <?php } ?>
                            </label>
                          </div>
                          <?php foreach ($stores as $store) { ?>
                            <div class="checkbox">
                              <label>
                              <?php if (in_array($store['store_id'], $shipping_store)) { ?>
                                <input type="checkbox" name="shipping_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                <?php echo $store['name']; ?>
                              <?php } else { ?>
                                <input type="checkbox" name="shipping_store[]" value="<?php echo $store['store_id']; ?>" />
                                <?php echo $store['name']; ?>
                              <?php } ?>
                              </label>
                            </div>
                          <?php } ?>
                        </div>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
        </form>
      </div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>