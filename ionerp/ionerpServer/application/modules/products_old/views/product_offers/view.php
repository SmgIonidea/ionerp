<div class="container">
<h2>View product offers</h2>
<div class = 'form-group row'>
                                <label for = 'off_name' class = 'col-sm-2 col-form-label'>Off name</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_name"]))?$data["off_name"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_percent' class = 'col-sm-2 col-form-label'>Off percent</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_percent"]))?$data["off_percent"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_flat_amount' class = 'col-sm-2 col-form-label'>Off flat amount</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_flat_amount"]))?$data["off_flat_amount"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_valid_from' class = 'col-sm-2 col-form-label'>Off valid from</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_valid_from"]))?$data["off_valid_from"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_valid_to' class = 'col-sm-2 col-form-label'>Off valid to</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_valid_to"]))?$data["off_valid_to"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_extended_hours' class = 'col-sm-2 col-form-label'>Off extended hours</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_extended_hours"]))?$data["off_extended_hours"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_offer_type_id' class = 'col-sm-2 col-form-label'>Off offer type id</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_offer_type_id"]))?$data["off_offer_type_id"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_status' class = 'col-sm-2 col-form-label'>Off status</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_status"]))?$data["off_status"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_created' class = 'col-sm-2 col-form-label'>Off created</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_created"]))?$data["off_created"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_created_by' class = 'col-sm-2 col-form-label'>Off created by</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_created_by"]))?$data["off_created_by"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_modified' class = 'col-sm-2 col-form-label'>Off modified</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_modified"]))?$data["off_modified"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'off_modified_by' class = 'col-sm-2 col-form-label'>Off modified by</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_modified_by"]))?$data["off_modified_by"]:""  ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_offers/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
</div>

</div>