<div class="container">
    <?php
    $form_attribute = array(
        "name" => "product_categories",
        "id" => "product_categories",
        "method" => "POST"
    );
    $form_action = "/products/product_categories/create";
    echo form_open($form_action, $form_attribute);
    ?>
    <div class = 'form-group row'>
        <label for = 'cat_name' class = 'col-sm-2 col-form-label'>Cat name</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "cat_name",
                "id" => "cat_name",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "text",
                "value" => (isset($data["cat_name"])) ? $data["cat_name"] : ""
            );
            echo form_error("cat_name");
            echo form_input($attribute);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'cat_position' class = 'col-sm-2 col-form-label'>Cat position</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "cat_position",
                "id" => "cat_position",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "number",
                "value" => (isset($data["cat_position"])) ? $data["cat_position"] : ""
            );
            echo form_error("cat_position");
            echo form_input($attribute);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'cat_parent_id' class = 'col-sm-2 col-form-label'>Cat parent id</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "cat_parent_id",
                "id" => "cat_parent_id",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "number",
                "value" => (isset($data["cat_parent_id"])) ? $data["cat_parent_id"] : ""
            );
            echo form_error("cat_parent_id");
            echo form_input($attribute);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'cat_title' class = 'col-sm-2 col-form-label'>Cat title</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "cat_title",
                "id" => "cat_title",
                "class" => "form-control",
                "title" => "",
                "required" => "",
            );
            $value = (isset($data["cat_title"])) ? $data["cat_title"] : "";
            echo form_error("cat_title");
            echo form_textarea($attribute, $value);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'cat_description' class = 'col-sm-2 col-form-label'>Cat description</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "cat_description",
                "id" => "cat_description",
                "class" => "form-control",
                "title" => "",
                "required" => "",
            );
            $value = (isset($data["cat_description"])) ? $data["cat_description"] : "";
            echo form_error("cat_description");
            echo form_textarea($attribute, $value);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'cat_created_date' class = 'col-sm-2 col-form-label'>Cat created date</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "cat_created_date",
                "id" => "cat_created_date",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "datetime ",
                "value" => (isset($data["cat_created_date"])) ? $data["cat_created_date"] : ""
            );
            echo form_error("cat_created_date");
            echo form_input($attribute);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'cat_created_by' class = 'col-sm-2 col-form-label'>Cat created by</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "cat_created_by",
                "id" => "cat_created_by",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "number",
                "value" => (isset($data["cat_created_by"])) ? $data["cat_created_by"] : ""
            );
            echo form_error("cat_created_by");
            echo form_input($attribute);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'cat_modified_date' class = 'col-sm-2 col-form-label'>Cat modified date</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "cat_modified_date",
                "id" => "cat_modified_date",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "datetime ",
                "value" => (isset($data["cat_modified_date"])) ? $data["cat_modified_date"] : ""
            );
            echo form_error("cat_modified_date");
            echo form_input($attribute);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'cat_modified_by' class = 'col-sm-2 col-form-label'>Cat modified by</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "cat_modified_by",
                "id" => "cat_modified_by",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "number",
                "value" => (isset($data["cat_modified_by"])) ? $data["cat_modified_by"] : ""
            );
            echo form_error("cat_modified_by");
            echo form_input($attribute);
            ?>
        </div>
    </div>

    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>products/product_categories/index">
                <span class="glyphicon glyphicon-th-list"></span> Cancel
            </a>
        </div>
        <div class = 'col-sm-1'>
            <input type="submit" id="submit" value="Save" class="btn btn-primary">
        </div>
    </div>
    <?= form_close() ?>
</div>