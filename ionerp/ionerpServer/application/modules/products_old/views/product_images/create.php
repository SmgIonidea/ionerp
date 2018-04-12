<div class="container">
<h2>Create product images</h2>
 <?php $form_attribute = array(
                "name" => "product_images",
                "id" => "product_images",
                "method" => "POST"
            );
$form_action= "/products/product_images/create";
 echo form_open($form_action, $form_attribute); ?>
<div class = 'form-group row'>
                                <label for = 'ima_path' class = 'col-sm-2 col-form-label'>Ima path</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"ima_path",
                "id" => "ima_path",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["ima_path"]))?$data["ima_path"]:""
); 
echo form_error("ima_path"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'ima_priority' class = 'col-sm-2 col-form-label'>Ima priority</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"ima_priority",
                "id" => "ima_priority",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"number", 
"value" => (isset($data["ima_priority"]))?$data["ima_priority"]:""
); 
echo form_error("ima_priority"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'ima_status' class = 'col-sm-2 col-form-label'>Ima status</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"ima_status",
                "id" => "ima_status",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $ima_status=(isset($data['ima_status']))?$data['ima_status']:'';echo form_error("ima_status");echo form_dropdown($attribute,$ima_status_list,$ima_status); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'ima_product_id' class = 'col-sm-2 col-form-label'>Ima product id</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"ima_product_id",
                "id" => "ima_product_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $ima_product_id=(isset($data['ima_product_id']))?$data['ima_product_id']:'';echo form_error("ima_product_id");echo form_dropdown($attribute,$ima_product_id_list,$ima_product_id); ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_images/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
<div class = 'col-sm-1'>
<input type="submit" id="submit" value="Save" class="btn btn-primary">
</div>
</div>
 <?= form_close() ?>
</div>