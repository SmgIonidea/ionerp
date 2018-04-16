<div class="container">
<h2>Edit product meta keywords</h2>
 <?php $form_attribute = array(
                "name" => "product_meta_keywords",
                "id" => "product_meta_keywords",
                "method" => "POST"
            );
$form_action= "/products/product_meta_keywords/edit";
 echo form_open($form_action, $form_attribute); ?>
<?php $attribute=array(
                "name" =>"meta_keyword_id",
                "id" => "meta_keyword_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"hidden", 
"value" => (isset($data["meta_keyword_id"]))?$data["meta_keyword_id"]:""
); 
echo form_error("meta_keyword_id"); echo form_input($attribute); ?><div class = 'form-group row'>
                                <label for = 'met_char_set' class = 'col-sm-2 col-form-label'>Met char set</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"met_char_set",
                "id" => "met_char_set",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["met_char_set"]))?$data["met_char_set"]:""
); 
echo form_error("met_char_set"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'met_content' class = 'col-sm-2 col-form-label'>Met content</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"met_content",
                "id" => "met_content",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $value=(isset($data["met_content"]))?$data["met_content"]:"";
echo form_error("met_content");echo form_textarea($attribute,$value); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'met_http_equiv' class = 'col-sm-2 col-form-label'>Met http equiv</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"met_http_equiv",
                "id" => "met_http_equiv",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $value=(isset($data["met_http_equiv"]))?$data["met_http_equiv"]:"";
echo form_error("met_http_equiv");echo form_textarea($attribute,$value); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'met_name' class = 'col-sm-2 col-form-label'>Met name</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"met_name",
                "id" => "met_name",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["met_name"]))?$data["met_name"]:""
); 
echo form_error("met_name"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'met_product_id' class = 'col-sm-2 col-form-label'>Met product id</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"met_product_id",
                "id" => "met_product_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $met_product_id=(isset($data['met_product_id']))?$data['met_product_id']:'';echo form_error("met_product_id");echo form_dropdown($attribute,$met_product_id_list,$met_product_id); ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_meta_keywords/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
<div class = 'col-sm-1'>
<input type="submit" id="submit" value="Update" class="btn btn-primary">
</div>
</div>
 <?= form_close() ?>
</div>