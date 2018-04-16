<div class="container">
    <?php
    $form_attribute = array(
        "name" => "product_brands",
        "id" => "product_brands",
        "method" => "POST"
    );
    $form_action = "/products/product_brands/edit";
    echo form_open($form_action, $form_attribute);
    ?>
    <?php
    $attribute = array(
        "name" => "brand_id",
        "id" => "brand_id",
        "class" => "form-control",
        "title" => "",
        "required" => "",
        "type" => "hidden",
        "value" => (isset($data["brand_id"])) ? $data["brand_id"] : ""
    );
    echo form_error("brand_id");
    echo form_input($attribute);
    ?>
    <div class = 'form-group row'>
        <label for = 'bra_name' class = 'col-sm-2 col-form-label'><?= $this->lang->line("brand_name") ?></label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "bra_name",
                "id" => "bra_name",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "text",
                "value" => (isset($data["bra_name"])) ? $data["bra_name"] : ""
            );
            echo form_input($attribute);
            echo form_error("bra_name");
            ?>
        </div>
    </div>    
    <div class = 'form-group row'>
        <label for = 'bra_title' class = 'col-sm-2 col-form-label'><?= $this->lang->line("brand_title") ?></label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "bra_title",
                "id" => "bra_title",
                "class" => "form-control",
                "title" => "",
                "type" => "text",
                "required" => '',
                "value" => (isset($data["bra_title"])) ? $data["bra_title"] : ""
            );
            echo form_input($attribute);
            echo form_error("bra_title");
            ?>
        </div>
    </div>
   <div class = 'form-group row'>
        <label for = 'bra_desc' class = 'col-sm-2 col-form-label'><?= $this->lang->line("brand_description") ?><span class="red">*</span></label>
        <div class = 'col-sm-6'>
            <?php
            $attribute = array(
                "name" => "bra_desc",
                "id" => "bra_desc",
                "class" => "form-control",
                "title" => "",                
                "type" => "text",
                "required"=>'',
                "value" => (isset($data["bra_desc"])) ? $data["bra_desc"] : ""
            );            
            echo form_textarea($attribute);
            echo form_error("bra_desc");
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>products/product_brands/index">
                <span class="glyphicon glyphicon-th-list"></span> Cancel
            </a>
        </div>
        <div class = 'col-sm-1'>
            <input type="submit" id="submit" value="Update" class="btn btn-primary">
        </div>
    </div>
    <?= form_close() ?>
</div>