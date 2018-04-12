<div class="container">
<h2>View product offer types</h2>
<div class = 'form-group row'>
                                <label for = 'off_typ_name' class = 'col-sm-2 col-form-label'>Off typ name</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["off_typ_name"]))?$data["off_typ_name"]:""  ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_offer_types/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
</div>

</div>