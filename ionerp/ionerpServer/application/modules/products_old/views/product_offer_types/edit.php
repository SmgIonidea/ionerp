<div class="container">
<h2>Edit product offer types</h2>
 <?php $form_attribute = array(
                "name" => "product_offer_types",
                "id" => "product_offer_types",
                "method" => "POST"
            );
$form_action= "/products/product_offer_types/edit";
 echo form_open($form_action, $form_attribute); ?>
<?php $attribute=array(
                "name" =>"offer_type_id",
                "id" => "offer_type_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"hidden", 
"value" => (isset($data["offer_type_id"]))?$data["offer_type_id"]:""
); 
echo form_error("offer_type_id"); echo form_input($attribute); ?><div class = 'form-group row'>
                                <label for = 'off_typ_name' class = 'col-sm-2 col-form-label'>Off typ name</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"off_typ_name",
                "id" => "off_typ_name",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["off_typ_name"]))?$data["off_typ_name"]:""
); 
echo form_error("off_typ_name"); echo form_input($attribute); ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_offer_types/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
<div class = 'col-sm-1'>
<input type="submit" id="submit" value="Update" class="btn btn-primary">
</div>
</div>
 <?= form_close() ?>
</div>