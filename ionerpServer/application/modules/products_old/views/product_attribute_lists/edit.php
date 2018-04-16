<div class="container">
<h2>Edit product attribute list</h2>
 <?php $form_attribute = array(
                "name" => "product_attribute_list",
                "id" => "product_attribute_list",
                "method" => "POST"
            );
$form_action= "/products/product_attribute_lists/edit";
 echo form_open($form_action, $form_attribute); ?>
<?php $attribute=array(
                "name" =>"product_attribute_list_id",
                "id" => "product_attribute_list_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"hidden", 
"value" => (isset($data["product_attribute_list_id"]))?$data["product_attribute_list_id"]:""
); 
echo form_error("product_attribute_list_id"); echo form_input($attribute); ?><div class = 'form-group row'>
                                <label for = 'attribute_name' class = 'col-sm-2 col-form-label'>Attribute name</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"attribute_name",
                "id" => "attribute_name",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["attribute_name"]))?$data["attribute_name"]:""
); 
echo form_error("attribute_name"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'attribute_value' class = 'col-sm-2 col-form-label'>Attribute value</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"attribute_value",
                "id" => "attribute_value",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["attribute_value"]))?$data["attribute_value"]:""
); 
echo form_error("attribute_value"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'attribute_group' class = 'col-sm-2 col-form-label'>Attribute group</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"attribute_group",
                "id" => "attribute_group",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["attribute_group"]))?$data["attribute_group"]:""
); 
echo form_error("attribute_group"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'product_id' class = 'col-sm-2 col-form-label'>Product id</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"product_id",
                "id" => "product_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $product_id=(isset($data['product_id']))?$data['product_id']:'';echo form_error("product_id");echo form_dropdown($attribute,$product_id_list,$product_id); ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_attribute_lists/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
<div class = 'col-sm-1'>
<input type="submit" id="submit" value="Update" class="btn btn-primary">
</div>
</div>
 <?= form_close() ?>
</div>