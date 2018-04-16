<div class="container">
<h2>Edit products</h2>
 <?php $form_attribute = array(
                "name" => "products",
                "id" => "products",
                "method" => "POST"
            );
$form_action= "/products/products/edit";
 echo form_open($form_action, $form_attribute); ?>
<?php $attribute=array(
                "name" =>"product_id",
                "id" => "product_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"hidden", 
"value" => (isset($data["product_id"]))?$data["product_id"]:""
); 
echo form_error("product_id"); echo form_input($attribute); ?><div class = 'form-group row'>
                                <label for = 'pro_sku' class = 'col-sm-2 col-form-label'>Pro sku</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_sku",
                "id" => "pro_sku",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_sku"]))?$data["pro_sku"]:""
); 
echo form_error("pro_sku"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_name' class = 'col-sm-2 col-form-label'>Pro name</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_name",
                "id" => "pro_name",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_name"]))?$data["pro_name"]:""
); 
echo form_error("pro_name"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_model' class = 'col-sm-2 col-form-label'>Pro model</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_model",
                "id" => "pro_model",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_model"]))?$data["pro_model"]:""
); 
echo form_error("pro_model"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_model_no' class = 'col-sm-2 col-form-label'>Pro model no</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_model_no",
                "id" => "pro_model_no",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_model_no"]))?$data["pro_model_no"]:""
); 
echo form_error("pro_model_no"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'product_mrp' class = 'col-sm-2 col-form-label'>Product mrp</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"product_mrp",
                "id" => "product_mrp",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["product_mrp"]))?$data["product_mrp"]:""
); 
echo form_error("product_mrp"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_sell_price' class = 'col-sm-2 col-form-label'>Pro sell price</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_sell_price",
                "id" => "pro_sell_price",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_sell_price"]))?$data["pro_sell_price"]:""
); 
echo form_error("pro_sell_price"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'vat/cst' class = 'col-sm-2 col-form-label'>Vat/cst</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"vat/cst",
                "id" => "vat/cst",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["vat/cst"]))?$data["vat/cst"]:""
); 
echo form_error("vat/cst"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'sipping_charge' class = 'col-sm-2 col-form-label'>Sipping charge</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"sipping_charge",
                "id" => "sipping_charge",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["sipping_charge"]))?$data["sipping_charge"]:""
); 
echo form_error("sipping_charge"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_weight' class = 'col-sm-2 col-form-label'>Pro weight</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_weight",
                "id" => "pro_weight",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_weight"]))?$data["pro_weight"]:""
); 
echo form_error("pro_weight"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_color' class = 'col-sm-2 col-form-label'>Pro color</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_color",
                "id" => "pro_color",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_color"]))?$data["pro_color"]:""
); 
echo form_error("pro_color"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_note' class = 'col-sm-2 col-form-label'>Pro note</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_note",
                "id" => "pro_note",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $value=(isset($data["pro_note"]))?$data["pro_note"]:"";
echo form_error("pro_note");echo form_textarea($attribute,$value); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_location' class = 'col-sm-2 col-form-label'>Pro location</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_location",
                "id" => "pro_location",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_location"]))?$data["pro_location"]:""
); 
echo form_error("pro_location"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_stock' class = 'col-sm-2 col-form-label'>Pro stock</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_stock",
                "id" => "pro_stock",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_stock"]))?$data["pro_stock"]:""
); 
echo form_error("pro_stock"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_max_buy' class = 'col-sm-2 col-form-label'>Pro max buy</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_max_buy",
                "id" => "pro_max_buy",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"number", 
"value" => (isset($data["pro_max_buy"]))?$data["pro_max_buy"]:""
); 
echo form_error("pro_max_buy"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_cart_desc' class = 'col-sm-2 col-form-label'>Pro cart desc</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_cart_desc",
                "id" => "pro_cart_desc",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $value=(isset($data["pro_cart_desc"]))?$data["pro_cart_desc"]:"";
echo form_error("pro_cart_desc");echo form_textarea($attribute,$value); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_short_desc' class = 'col-sm-2 col-form-label'>Pro short desc</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_short_desc",
                "id" => "pro_short_desc",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $value=(isset($data["pro_short_desc"]))?$data["pro_short_desc"]:"";
echo form_error("pro_short_desc");echo form_textarea($attribute,$value); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_long_desc' class = 'col-sm-2 col-form-label'>Pro long desc</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_long_desc",
                "id" => "pro_long_desc",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $value=(isset($data["pro_long_desc"]))?$data["pro_long_desc"]:"";
echo form_error("pro_long_desc");echo form_textarea($attribute,$value); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_status' class = 'col-sm-2 col-form-label'>Pro status</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_status",
                "id" => "pro_status",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $pro_status=(isset($data['pro_status']))?$data['pro_status']:'';echo form_error("pro_status");echo form_dropdown($attribute,$pro_status_list,$pro_status); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_catagory_id' class = 'col-sm-2 col-form-label'>Pro catagory id</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_catagory_id",
                "id" => "pro_catagory_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"number", 
"value" => (isset($data["pro_catagory_id"]))?$data["pro_catagory_id"]:""
); 
echo form_error("pro_catagory_id"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_brand_id' class = 'col-sm-2 col-form-label'>Pro brand id</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_brand_id",
                "id" => "pro_brand_id",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
); $pro_brand_id=(isset($data['pro_brand_id']))?$data['pro_brand_id']:'';echo form_error("pro_brand_id");echo form_dropdown($attribute,$pro_brand_id_list,$pro_brand_id); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_page_title' class = 'col-sm-2 col-form-label'>Pro page title</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_page_title",
                "id" => "pro_page_title",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_page_title"]))?$data["pro_page_title"]:""
); 
echo form_error("pro_page_title"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_title' class = 'col-sm-2 col-form-label'>Pro title</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_title",
                "id" => "pro_title",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"text", 
"value" => (isset($data["pro_title"]))?$data["pro_title"]:""
); 
echo form_error("pro_title"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_created_date' class = 'col-sm-2 col-form-label'>Pro created date</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_created_date",
                "id" => "pro_created_date",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"datetime ", 
"value" => (isset($data["pro_created_date"]))?$data["pro_created_date"]:""
); 
echo form_error("pro_created_date"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_created_by' class = 'col-sm-2 col-form-label'>Pro created by</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_created_by",
                "id" => "pro_created_by",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"number", 
"value" => (isset($data["pro_created_by"]))?$data["pro_created_by"]:""
); 
echo form_error("pro_created_by"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_modified_date' class = 'col-sm-2 col-form-label'>Pro modified date</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_modified_date",
                "id" => "pro_modified_date",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"datetime ", 
"value" => (isset($data["pro_modified_date"]))?$data["pro_modified_date"]:""
); 
echo form_error("pro_modified_date"); echo form_input($attribute); ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_modified_by' class = 'col-sm-2 col-form-label'>Pro modified by</label>
                                <div class = 'col-sm-3'>
                                    <?php $attribute=array(
                "name" =>"pro_modified_by",
                "id" => "pro_modified_by",
                "class" => "form-control",
                "title" => "",
"required"=>"", 
"type"=>"number", 
"value" => (isset($data["pro_modified_by"]))?$data["pro_modified_by"]:""
); 
echo form_error("pro_modified_by"); echo form_input($attribute); ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/products/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
<div class = 'col-sm-1'>
<input type="submit" id="submit" value="Update" class="btn btn-primary">
</div>
</div>
 <?= form_close() ?>
</div>