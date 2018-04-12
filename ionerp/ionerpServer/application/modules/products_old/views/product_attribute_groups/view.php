<div class="container">
<h2>View product attribute groups</h2>
<div class = 'form-group row'>
                                <label for = 'pro_attr_group_name' class = 'col-sm-2 col-form-label'>Pro attr group name</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["pro_attr_group_name"]))?$data["pro_attr_group_name"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_attr_id' class = 'col-sm-2 col-form-label'>Pro attr id</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["pro_attr_id"]))?$data["pro_attr_id"]:""  ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_attribute_groups/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
</div>

</div>