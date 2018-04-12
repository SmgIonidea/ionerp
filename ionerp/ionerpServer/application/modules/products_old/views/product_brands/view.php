<div class="container">
    <div class = 'form-group row'>
        <label for = 'bra_name' class = 'col-sm-2 col-form-label'>Bra name</label>
        <div class = 'col-sm-3'>
            <?= (isset($data["bra_name"])) ? ucfirst($data["bra_name"]) : "" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'bra_created_date' class = 'col-sm-2 col-form-label'>Bra created date</label>
        <div class = 'col-sm-3'>
            <?= (isset($data["bra_created_date"])) ? date('m-d-Y H:m:s',  strtotime($data["bra_created_date"])) : "" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'bra_created_by' class = 'col-sm-2 col-form-label'>Bra created by</label>
        <div class = 'col-sm-3'>
            <?= (isset($data["created_by"])) ? $data["created_by"] : "" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'bra_modified_date' class = 'col-sm-2 col-form-label'>Bra modified date</label>
        <div class = 'col-sm-3'>
            <?= (isset($data["bra_modified_date"])) ? date('m-d-Y H:m:s',  strtotime($data["bra_modified_date"])) : "" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'bra_modified_by' class = 'col-sm-2 col-form-label'>Bra modified by</label>
        <div class = 'col-sm-3'>
            <?= (isset($data["modified_by"])) ? $data["modified_by"] : "" ?>
        </div>
    </div>

    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>products/product_brands/index">
                <span class="glyphicon glyphicon-th-list"></span> Cancel
            </a>
        </div>
    </div>

</div>