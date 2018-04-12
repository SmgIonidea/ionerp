<div class="container">
<h2>View category products</h2>
<div class = 'form-group row'>
                                <label for = 'product_id' class = 'col-sm-2 col-form-label'>Product id</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["product_id"]))?$data["product_id"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'category_id' class = 'col-sm-2 col-form-label'>Category id</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["category_id"]))?$data["category_id"]:""  ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/category_products/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
</div>

</div>