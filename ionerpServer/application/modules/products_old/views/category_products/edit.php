<div class="container">
<h2>Edit category products</h2>
 <?php $form_attribute = array(
                "name" => "category_products",
                "id" => "category_products",
                "method" => "POST"
            );
$form_action= "/products/category_products/edit";
 echo form_open($form_action, $form_attribute); ?>
<?php $attribute=array(
                "name" =>"category_product_id",
                "id" => "category_product_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"hidden", 
"value" => (isset($data["category_product_id"]))?$data["category_product_id"]:""
); 
echo form_error("category_product_id"); echo form_input($attribute); ?><div class = 'form-group row'>
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
                                <label for = 'category_id' class = 'col-sm-2 col-form-label'>Category id</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"category_id",
                "id" => "category_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $category_id=(isset($data['category_id']))?$data['category_id']:'';echo form_error("category_id");echo form_dropdown($attribute,$category_id_list,$category_id); ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/category_products/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
<div class = 'col-sm-1'>
<input type="submit" id="submit" value="Update" class="btn btn-primary">
</div>
</div>
 <?= form_close() ?>
</div>