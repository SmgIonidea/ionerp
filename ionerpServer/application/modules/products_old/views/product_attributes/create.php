<div class="container">
<h2>Create product attributes</h2>
 <?php $form_attribute = array(
                "name" => "product_attributes",
                "id" => "product_attributes",
                "method" => "POST"
            );
$form_action= "/products/product_attributes/create";
 echo form_open($form_action, $form_attribute); ?>
<div class = 'form-group row'>
                                <label for = 'pro_att_name' class = 'col-sm-2 col-form-label'>Pro att name</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_att_name",
                "id" => "pro_att_name",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_att_name"]))?$data["pro_att_name"]:""
); 
echo form_error("pro_att_name"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_att_type' class = 'col-sm-2 col-form-label'>Pro att type</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_att_type",
                "id" => "pro_att_type",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_att_type"]))?$data["pro_att_type"]:""
); 
echo form_error("pro_att_type"); echo form_input($attribute); ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_attributes/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
<div class = 'col-sm-1'>
<input type="submit" id="submit" value="Save" class="btn btn-primary">
</div>
</div>
 <?= form_close() ?>
</div>