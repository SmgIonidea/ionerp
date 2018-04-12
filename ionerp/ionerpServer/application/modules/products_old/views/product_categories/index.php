<?php if ($this->rbac->has_permission('PRODUCT_BRAND', 'create')) { ?>
<div class="pull-right" id="create_button_div">
    <a class="btn btn-primary btn-sm" href="<?= APP_BASE ?>products/product_categories/create">Create</a>
</div>
<?php }?>
<div class="row-fluid">
    <div class="col-sm-12 table-responsive no-pad">
        <?= generate_gird($grid_config, "product_categories_list") ?>
    </div>
</div><script type="text/javascript">
    $(function ($) {


        $(document).on('click', '.delete-record', function (e) {
            e.preventDefault();
            var data = {'category_id': $(this).data('category_id')}
            var row = $(this).closest('tr');
            BootstrapDialog.show({
                title: 'Alert',
                message: 'Do you want to delete the record?',
                buttons: [{
                        label: 'Cancel',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }, {
                        label: 'Delete',
                        action: function (dialog) {
                            $.ajax({
                                url: '<?= APP_BASE ?>products/product_categories/delete',
                                method: 'POST',
                                data: data,
                                success: function (result) {
                                    if (result == 'success') {
                                        dialog.close();
                                        row.hide();
                                        BootstrapDialog.alert('Record successfully deleted!');
                                    } else {
                                        dialog.close();
                                        BootstrapDialog.alert('Data deletion error,please contact site admin!');
                                    }
                                },
                                error: function (error) {
                                    dialog.close();
                                    BootstrapDialog.alert('Error:' + error);
                                }
                            });
                        }
                    }]
            });

        });
        $('div.DTTT_container').prepend($('#create_button_div'));

    });
</script>