<div class="container">
<h2>View product meta keywords</h2>
<div class = 'form-group row'>
                                <label for = 'met_char_set' class = 'col-sm-2 col-form-label'>Met char set</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["met_char_set"]))?$data["met_char_set"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'met_content' class = 'col-sm-2 col-form-label'>Met content</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["met_content"]))?$data["met_content"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'met_http_equiv' class = 'col-sm-2 col-form-label'>Met http equiv</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["met_http_equiv"]))?$data["met_http_equiv"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'met_name' class = 'col-sm-2 col-form-label'>Met name</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["met_name"]))?$data["met_name"]:""  ?>
                                </div>
                           </div>
<div class = 'form-group row'>
                                <label for = 'met_product_id' class = 'col-sm-2 col-form-label'>Met product id</label>
                                <div class = 'col-sm-3'>
                                    <?=(isset($data["met_product_id"]))?$data["met_product_id"]:""  ?>
                                </div>
                           </div>

<div class = 'form-group row'>
<div class = 'col-sm-1'>
<a class="text-right btn btn-default" href="<?=APP_BASE?>products/product_meta_keywords/index">
<span class="glyphicon glyphicon-th-list"></span> Cancel
</a>
</div>
</div>

</div>