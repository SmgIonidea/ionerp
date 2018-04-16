<div class="container">
    <h2>Edit rbac actions</h2>
    <?php
    $form_attribute = array(
        "name" => "rbac_actions",
        "id" => "rbac_actions",
        "method" => "POST"
    );
    $form_action = "/rbac/rbac_actions/edit";
    echo form_open($form_action, $form_attribute);
    ?>
    <?php
    $attribute = array(
        "name" => "action_id",
        "id" => "action_id",
        "class" => "",
        "title" => "",
        "required" => "",
        "type" => "hidden",
        "value" => (isset($data["action_id"])) ? $data["action_id"] : ""
    );
    echo form_error("action_id");
    echo form_input($attribute);
    ?><div class = 'form-group row'>
        <label for = 'name' class = 'col-sm-2 col-form-label'>Name</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "name",
                "id" => "name",
                "class" => "",
                "title" => "",
                "required" => "",
                "type" => "text",
                "value" => (isset($data["name"])) ? $data["name"] : ""
            );
            echo form_error("name");
            echo form_input($attribute);
            ?>
        </div>
    </div>

    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>rbac/rbac_actions/index">
                <span class="glyphicon glyphicon-th-list"></span> Cancel
            </a>
        </div>
        <div class = 'col-sm-1'>
            <input type="submit" id="submit" value="Update" class="btn btn-primary">
        </div>
    </div>
    <?= form_close() ?>
</div>