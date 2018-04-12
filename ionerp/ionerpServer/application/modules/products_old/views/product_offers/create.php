<div class="container">
<h2>Create product offers</h2>
 <?php $form_attribute = array(
                "name" => "product_offers",
                "id" => "product_offers",
                "method" => "POST"
            );
$form_action= "/products/product_offers/create";
 echo form_open($form_action, $form_attribute); ?>
<div class = 'form-group row'>
                                <label for = 'off_name' class = 'col-sm-2 col-form-label'>Off name</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_name",
                "id" => "off_name",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_name"]))?$data["off_name"]:""
); 
echo form_error("off_name"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_percent' class = 'col-sm-2 col-form-label'>Off percent</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_percent",
                "id" => "off_percent",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_percent"]))?$data["off_percent"]:""
); 
echo form_error("off_percent"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_flat_amount' class = 'col-sm-2 col-form-label'>Off flat amount</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_flat_amount",
                "id" => "off_flat_amount",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_flat_amount"]))?$data["off_flat_amount"]:""
); 
echo form_error("off_flat_amount"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_valid_from' class = 'col-sm-2 col-form-label'>Off valid from</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_valid_from",
                "id" => "off_valid_from",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_valid_from"]))?$data["off_valid_from"]:""
); 
echo form_error("off_valid_from"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_valid_to' class = 'col-sm-2 col-form-label'>Off valid to</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_valid_to",
                "id" => "off_valid_to",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_valid_to"]))?$data["off_valid_to"]:""
); 
echo form_error("off_valid_to"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_extended_hours' class = 'col-sm-2 col-form-label'>Off extended hours</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_extended_hours",
                "id" => "off_extended_hours",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_extended_hours"]))?$data["off_extended_hours"]:""
); 
echo form_error("off_extended_hours"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_offer_type_id' class = 'col-sm-2 col-form-label'>Off offer type id</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_offer_type_id",
                "id" => "off_offer_type_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $off_offer_type_id=(isset($data['off_offer_type_id']))?$data['off_offer_type_id']:'';echo form_error("off_offer_type_id");echo form_dropdown($attribute,$off_offer_type_id_list,$off_offer_type_id); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_status' class = 'col-sm-2 col-form-label'>Off status</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_status",
                "id" => "off_status",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_status"]))?$data["off_status"]:""
); 
echo form_error("off_status"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_created' class = 'col-sm-2 col-form-label'>Off created</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_created",
                "id" => "off_created",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_created"]))?$data["off_created"]:""
); 
echo form_error("off_created"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_created_by' class = 'col-sm-2 col-form-label'>Off created by</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_created_by",
                "id" => "off_created_by",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_created_by"]))?$data["off_created_by"]:""
); 
echo form_error("off_created_by"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_modified' class = 'col-sm-2 col-form-label'>Off modified</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_modified",
                "id" => "off_modified",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_modified"]))?$data["off_modified"]:""
); 
echo form_error("off_modified"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_modified_by' class = 'col-sm-2 col-form-label'>Off modified by</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_modified_by",
                "id" => "off_modified_by",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_modified_by"]))?$data["off_modified_by"]:""
); 
echo form_error("off_modified_by"); echo form_input($attribute); ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_offers/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
<div class = 'col-sm-1'>
<input type="submit" id="submit" value="Save" class="btn btn-primary">
</div>
</div>
 <?= form_close() ?>
</div>