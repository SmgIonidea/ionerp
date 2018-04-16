<div class="container">
<h2>Create product offer types</h2>
 <?php $form_attribute = array(
                "name" => "product_offer_types",
                "id" => "product_offer_types",
                "method" => "POST"
            );
$form_action= "/products/product_offer_types/create";
 echo form_open($form_action, $form_attribute); ?>
<div class = 'form-group row'>
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
<input type="submit" id="submit" value="Save" class="btn btn-primary">
</div>
</div>
 <?= form_close() ?>
</div>