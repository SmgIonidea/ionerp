<div class="container">
<h2>View product attributes</h2>
<div class = 'form-group row'>
                                <label for = 'pro_att_name' class = 'col-sm-2 col-form-label'>Pro att name</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["pro_att_name"]))?$data["pro_att_name"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'pro_att_type' class = 'col-sm-2 col-form-label'>Pro att type</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["pro_att_type"]))?$data["pro_att_type"]:""  ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_attributes/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
</div>

</div>