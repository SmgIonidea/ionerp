<div class="pull-right" id="create_button_div">
    <a class="btn btn-primary btn-sm" href="<?= APP_BASE ?>rbac/rbac_permissions/create">Create/Update</a>
</div>
<div class="row-fluid table-responsive">
    <?php
    generate_gird($grid_config, "rbac_permissions_list");
    ?>
</div><script type="text/javascript">
    $(function ($) {
        $(document).on('click', '.delete-record', function (e) {
            e.preventDefault();
            var data = {'permission_id': $(this).data('permission_id')}
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
                                url: '<?= APP_BASE ?>rbac/rbac_permissions/delete',
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