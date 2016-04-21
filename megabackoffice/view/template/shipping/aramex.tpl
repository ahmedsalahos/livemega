<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-aramex" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-aramex" class="form-horizontal">
          <!-- <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-key"><?php echo $entry_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="aramex_key" value="<?php echo $aramex_key; ?>" placeholder="<?php echo $entry_key; ?>" id="input-key" class="form-control" />
              <?php if ($error_key) { ?>
              <div class="text-danger"><?php echo $error_key; ?></div>
              <?php } ?>
            </div>
          </div> -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-account"><?php echo $entry_account; ?></label>
            <div class="col-sm-10">
              <input type="text" name="aramex_account" value="<?php echo $aramex_account; ?>" placeholder="<?php echo $entry_account; ?>" id="input-account" class="form-control" />
              <?php if ($error_account) { ?>
              <div class="text-danger"><?php echo $error_account; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-pin"><?php echo $entry_pin; ?></label>
            <div class="col-sm-10">
              <input type="text" name="aramex_pin" value="<?php echo $aramex_pin; ?>" placeholder="<?php echo $entry_pin; ?>" id="input-pin" class="form-control" />
              <?php if ($error_pin) { ?>
              <div class="text-danger"><?php echo $error_pin; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
            <div class="col-sm-10">
              <input type="text" name="aramex_username" value="<?php echo $aramex_username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
              <?php if ($error_username) { ?>
              <div class="text-danger"><?php echo $error_username; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-10">
              <input type="text" name="aramex_password" value="<?php echo $aramex_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
            </div>
          </div>
           <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-countrycode"><?php echo $entry_countrycode; ?></label>
            <div class="col-sm-10">
              <input type="text" name="aramex_countrycode" value="<?php echo $aramex_countrycode; ?>" placeholder="<?php echo $entry_countrycode; ?>" id="input-countrycode" class="form-control" />
              <?php if ($error_countrycode) { ?>
              <div class="text-danger"><?php echo $error_countrycode; ?></div>
              <?php } ?>
            </div>
          </div>
           <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-entity"><?php echo $entry_entity; ?></label>
            <div class="col-sm-10">
              <input type="text" name="aramex_entity" value="<?php echo $aramex_entity; ?>" placeholder="<?php echo $entry_entity; ?>" id="input-entity" class="form-control" />
              <?php if ($error_entity) { ?>
              <div class="text-danger"><?php echo $error_entity; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-version"><?php echo $entry_version; ?></label>
            <div class="col-sm-10">
              <input type="text" name="aramex_version" value="<?php echo $aramex_version; ?>" placeholder="<?php echo $entry_version; ?>" id="input-version" class="form-control" />
              <?php if ($error_version) { ?>
              <div class="text-danger"><?php echo $error_version; ?></div>
              <?php } ?>
            </div>
          </div> <!--
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-meter"><?php echo $entry_meter; ?></label>
            <div class="col-sm-10">
              <input type="text" name="aramex_meter" value="<?php echo $aramex_meter; ?>" placeholder="<?php echo $entry_meter; ?>" id="input-meter" class="form-control" />
              <?php if ($error_meter) { ?>
              <div class="text-danger"><?php echo $error_meter; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-postcode"><?php echo $entry_postcode; ?></label>
            <div class="col-sm-10">
              <input type="text" name="aramex_postcode" value="<?php echo $aramex_postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
              <?php if ($error_postcode) { ?>
              <div class="text-danger"><?php echo $error_postcode; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_test; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($aramex_test) { ?>
                <input type="radio" name="aramex_test" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="aramex_test" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$aramex_test) { ?>
                <input type="radio" name="aramex_test" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="aramex_test" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_service; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($services as $service) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($service['value'], $aramex_service)) { ?>
                    <input type="checkbox" name="aramex_service[]" value="<?php echo $service['value']; ?>" checked="checked" />
                    <?php echo $service['text']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="aramex_service[]" value="<?php echo $service['value']; ?>" />
                    <?php echo $service['text']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-length"><?php echo $entry_dimension; ?></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-4">
                  <input type="text" name="aramex_length" value="<?php echo $aramex_length; ?>" placeholder="<?php echo $entry_length; ?>" id="input-length" class="form-control" />
                </div>
                <div class="col-sm-4">
                  <input type="text" name="aramex_width" value="<?php echo $aramex_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
                </div>
                <div class="col-sm-4">
                  <input type="text" name="aramex_height" value="<?php echo $aramex_height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
                </div>
              </div>
              <?php if ($error_dimension) { ?>
              <div class="text-danger"><?php echo $error_dimension; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-length-class"><span data-toggle="tooltip" title="<?php echo $help_length_class; ?>"><?php echo $entry_length_class; ?></span></label>
            <div class="col-sm-10">
              <select name="aramex_length_class_id" id="input-length-class" class="form-control">
                <?php foreach ($length_classes as $length_class) { ?>
                <?php if ($length_class['length_class_id'] == $aramex_length_class_id) { ?>
                <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-dropoff-type"><?php echo $entry_dropoff_type; ?></label>
            <div class="col-sm-10">
              <select name="aramex_dropoff_type" id="input-dropoff-type" class="form-control">
                <?php if ($aramex_dropoff_type == 'REGULAR_PICKUP') { ?>
                <option value="REGULAR_PICKUP" selected="selected"><?php echo $text_regular_pickup; ?></option>
                <?php } else { ?>
                <option value="REGULAR_PICKUP"><?php echo $text_regular_pickup; ?></option>
                <?php } ?>
                <?php if ($aramex_dropoff_type == 'REQUEST_COURIER') { ?>
                <option value="REQUEST_COURIER" selected="selected"><?php echo $text_request_courier; ?></option>
                <?php } else { ?>
                <option value="REQUEST_COURIER"><?php echo $text_request_courier; ?></option>
                <?php } ?>
                <?php if ($aramex_dropoff_type == 'DROP_BOX') { ?>
                <option value="DROP_BOX" selected="selected"><?php echo $text_drop_box; ?></option>
                <?php } else { ?>
                <option value="DROP_BOX"><?php echo $text_drop_box; ?></option>
                <?php } ?>
                <?php if ($aramex_dropoff_type == 'BUSINESS_SERVICE_CENTER') { ?>
                <option value="BUSINESS_SERVICE_CENTER" selected="selected"><?php echo $text_business_service_center; ?></option>
                <?php } else { ?>
                <option value="BUSINESS_SERVICE_CENTER"><?php echo $text_business_service_center; ?></option>
                <?php } ?>
                <?php if ($aramex_dropoff_type == 'STATION') { ?>
                <option value="STATION" selected="selected"><?php echo $text_station; ?></option>
                <?php } else { ?>
                <option value="STATION"><?php echo $text_station; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-packaging-type"><?php echo $entry_packaging_type; ?></label>
            <div class="col-sm-10">
              <select name="aramex_packaging_type" id="input-packaging-type" class="form-control">
                <?php if ($aramex_packaging_type == 'ARAMEX_ENVELOPE') { ?>
                <option value="ARAMEX_ENVELOPE" selected="selected"><?php echo $text_aramex_envelope; ?></option>
                <?php } else { ?>
                <option value="ARAMEX_ENVELOPE"><?php echo $text_aramex_envelope; ?></option>
                <?php } ?>
                <?php if ($aramex_packaging_type == 'ARAMEX_PAK') { ?>
                <option value="ARAMEX_PAK" selected="selected"><?php echo $text_aramex_pak; ?></option>
                <?php } else { ?>
                <option value="ARAMEX_PAK"><?php echo $text_aramex_pak; ?></option>
                <?php } ?>
                <?php if ($aramex_packaging_type == 'ARAMEX_BOX') { ?>
                <option value="ARAMEX_BOX" selected="selected"><?php echo $text_aramex_box; ?></option>
                <?php } else { ?>
                <option value="ARAMEX_BOX"><?php echo $text_aramex_box; ?></option>
                <?php } ?>
                <?php if ($aramex_packaging_type == 'ARAMEX_TUBE') { ?>
                <option value="ARAMEX_TUBE" selected="selected"><?php echo $text_aramex_tube; ?></option>
                <?php } else { ?>
                <option value="ARAMEX_TUBE"><?php echo $text_aramex_tube; ?></option>
                <?php } ?>
                <?php if ($aramex_packaging_type == 'ARAMEX_10KG_BOX') { ?>
                <option value="ARAMEX_10KG_BOX" selected="selected"><?php echo $text_aramex_10kg_box; ?></option>
                <?php } else { ?>
                <option value="ARAMEX_10KG_BOX"><?php echo $text_aramex_10kg_box; ?></option>
                <?php } ?>
                <?php if ($aramex_packaging_type == 'ARAMEX_25KG_BOX') { ?>
                <option value="ARAMEX_25KG_BOX" selected="selected"><?php echo $text_aramex_25kg_box; ?></option>
                <?php } else { ?>
                <option value="ARAMEX_25KG_BOX"><?php echo $text_aramex_25kg_box; ?></option>
                <?php } ?>
                <?php if ($aramex_packaging_type == 'YOUR_PACKAGING') { ?>
                <option value="YOUR_PACKAGING" selected="selected"><?php echo $text_your_packaging; ?></option>
                <?php } else { ?>
                <option value="YOUR_PACKAGING"><?php echo $text_your_packaging; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-rate-type"><?php echo $entry_rate_type; ?></label>
            <div class="col-sm-10">
              <select name="aramex_rate_type" id="input-rate-type" class="form-control">
                <?php if ($aramex_rate_type == 'LIST') { ?>
                <option value="LIST" selected="selected"><?php echo $text_list_rate; ?></option>
                <?php } else { ?>
                <option value="LIST"><?php echo $text_list_rate; ?></option>
                <?php } ?>
                <?php if ($aramex_rate_type == 'ACCOUNT') { ?>
                <option value="ACCOUNT" selected="selected"><?php echo $text_account_rate; ?></option>
                <?php } else { ?>
                <option value="ACCOUNT"><?php echo $text_account_rate; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_display_time; ?>"><?php echo $entry_display_time; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($aramex_display_time) { ?>
                <input type="radio" name="aramex_display_time" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="aramex_display_time" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$aramex_display_time) { ?>
                <input type="radio" name="aramex_display_time" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="aramex_display_time" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_display_weight; ?>"><?php echo $entry_display_weight; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($aramex_display_weight) { ?>
                <input type="radio" name="aramex_display_weight" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="aramex_display_weight" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$aramex_display_weight) { ?>
                <input type="radio" name="aramex_display_weight" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="aramex_display_weight" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-weight-class"><span data-toggle="tooltip" title="<?php echo $help_weight_class; ?>"><?php echo $entry_weight_class; ?></span></label>
            <div class="col-sm-10">
              <select name="aramex_weight_class_id" id="input-weight-class" class="form-control">
                <?php foreach ($weight_classes as $weight_class) { ?>
                <?php if ($weight_class['weight_class_id'] == $aramex_weight_class_id) { ?>
                <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
            <div class="col-sm-10">
              <select name="aramex_tax_class_id" id="input-tax-class" class="form-control">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $aramex_tax_class_id) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="aramex_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $aramex_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div> -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="aramex_status" id="input-status" class="form-control">
                <?php if ($aramex_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="aramex_sort_order" value="<?php echo $aramex_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>