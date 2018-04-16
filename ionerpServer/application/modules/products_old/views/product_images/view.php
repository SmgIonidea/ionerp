<div class="container">
<h2>View product images</h2>
<div class = 'form-group row'>
                                <label for = 'ima_path' class = 'col-sm-2 col-form-label'>Ima path</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["ima_path"]))?$data["ima_path"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'ima_priority' class = 'col-sm-2 col-form-label'>Ima priority</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["ima_priority"]))?$data["ima_priority"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'ima_status' class = 'col-sm-2 col-form-label'>Ima status</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["ima_status"]))?$data["ima_status"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'ima_product_id' class = 'col-sm-2 col-form-label'>Ima product id</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["ima_product_id"]))?$data["ima_product_id"]:""  ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_images/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
</div>

</div>