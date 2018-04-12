<div class="container">
<h2>View product attribute list</h2>
<div class = 'form-group row'>
                                <label for = 'attribute_name' class = 'col-sm-2 col-form-label'>Attribute name</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["attribute_name"]))?$data["attribute_name"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'attribute_value' class = 'col-sm-2 col-form-label'>Attribute value</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["attribute_value"]))?$data["attribute_value"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'attribute_group' class = 'col-sm-2 col-form-label'>Attribute group</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["attribute_group"]))?$data["attribute_group"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'product_id' class = 'col-sm-2 col-form-label'>Product id</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["product_id"]))?$data["product_id"]:""  ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_attribute_lists/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
</div>

</div>