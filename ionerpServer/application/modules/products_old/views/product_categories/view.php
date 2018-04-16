<div class="container">
<h2>View product categories</h2>
<div class = 'form-group row'>
                                <label for = 'cat_name' class = 'col-sm-2 col-form-label'>Cat name</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["cat_name"]))?$data["cat_name"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'cat_position' class = 'col-sm-2 col-form-label'>Cat position</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["cat_position"]))?$data["cat_position"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'cat_parent_id' class = 'col-sm-2 col-form-label'>Cat parent id</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["cat_parent_id"]))?$data["cat_parent_id"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'cat_title' class = 'col-sm-2 col-form-label'>Cat title</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["cat_title"]))?$data["cat_title"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'cat_description' class = 'col-sm-2 col-form-label'>Cat description</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["cat_description"]))?$data["cat_description"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'cat_created_date' class = 'col-sm-2 col-form-label'>Cat created date</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["cat_created_date"]))?$data["cat_created_date"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'cat_created_by' class = 'col-sm-2 col-form-label'>Cat created by</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["cat_created_by"]))?$data["cat_created_by"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'cat_modified_date' class = 'col-sm-2 col-form-label'>Cat modified date</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["cat_modified_date"]))?$data["cat_modified_date"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'cat_modified_by' class = 'col-sm-2 col-form-label'>Cat modified by</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["cat_modified_by"]))?$data["cat_modified_by"]:""  ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_categories/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
</div>

</div>