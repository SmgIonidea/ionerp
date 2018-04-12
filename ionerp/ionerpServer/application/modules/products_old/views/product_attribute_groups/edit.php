<div class="container">
<h2>Edit product attribute groups</h2>
 <?php $form_attribute = array(
                "name" => "product_attribute_groups",
                "id" => "product_attribute_groups",
                "method" => "POST"
            );
$form_action= "/products/product_attribute_groups/edit";
 echo form_open($form_action, $form_attribute); ?>
<?php $attribute=array(
                "name" =>"product_attribute_group_id",
                "id" => "product_attribute_group_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"hidden", 
"value" => (isset($data["product_attribute_group_id"]))?$data["product_attribute_group_id"]:""
); 
echo form_error("product_attribute_group_id"); echo form_input($attribute); ?><div class = 'form-group row'>
                                <label for = 'pro_attr_group_name' class = 'col-sm-2 col-form-label'>Pro attr group name</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_attr_group_name",
                "id" => "pro_attr_group_name",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_attr_group_name"]))?$data["pro_attr_group_name"]:""
); 
echo form_error("pro_attr_group_name"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_attr_id' class = 'col-sm-2 col-form-label'>Pro attr id</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_attr_id",
                "id" => "pro_attr_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $pro_attr_id=(isset($data['pro_attr_id']))?$data['pro_attr_id']:'';echo form_error("pro_attr_id");echo form_dropdown($attribute,$pro_attr_id_list,$pro_attr_id); ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_attribute_groups/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
<div class = 'col-sm-1'>
<input type="submit" id="submit" value="Update" class="btn btn-primary">
</div>
</div>
 <?= form_close() ?>
</div>