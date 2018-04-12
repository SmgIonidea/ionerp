
//Bloom's Level List page

var base_url = $('#get_base_url').val();
$.cookie('1', $('#bloom_domain option:selected').val(), {expires: 90, path: '/'});
//tool tip - to display message when mouse is placed on the icon
$("#hint a").tooltip();

//script to delete bloom's level
$(document).ready(function () {
    var table_row;
    var blooms_id;
	fetch_bloom_level_data();

    $('.get_id').live('click', function (e)
    {
        blooms_id = $(this).attr('id');
        //fetching all the details of the row to be deleted
        table_row = $(this).closest("tr").get(0);
    });

    $('.delete_bloom').click(function (e) {
        e.preventDefault();
        var post_data = {
            'bloom_id': blooms_id,
        }

        $.ajax({type: "POST",
            url: base_url + 'configuration/bloom_level/bloom_delete',
            data: post_data,
            datatype: "JSON",
            success: function (msg)
            {
                var oTable = $('#example').dataTable();
                oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
            }
        });
    });
});

//Display Bloom's level details on select of bloom's domain 
if ($.cookie('remember_bloom_domain') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#bloom_domain option[value="' + $.cookie('remember_bloom_domain') + '"]').prop('selected', true);
    $('#add_bloom_domain option[value="' + $.cookie('remember_bloom_domain') + '"]').prop('selected', true);
    fetch_bloom_level_data();
}

//Display Bloom's level details on change of bloom's domain 
$('#bloom_domain').on('change', function () {
    fetch_bloom_level_data();
});

//function to fetch Bloom's level details
function fetch_bloom_level_data() {
    $.cookie('1', $('#bloom_domain option:selected').val(), {expires: 90, path: '/'});
    var bloom_domain_id = $('#bloom_domain').val();

    var post_data = {
        'bloom_domain_id': bloom_domain_id,
    }
    $.ajax({type: "POST",
        url: base_url + 'configuration/bloom_level/bloom_level_list',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            populate_table(data);
        }
    });
}

//function to populate table with Bloom's level details.
function populate_table(data) {
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
            {"aoColumns": [
                    {"sTitle": "Bloom's Levels", "mData": "level"},
                    {"sTitle": "Level Of Learning ", "mData": "description"},
                    {"sTitle": "Characteristics Of Learning", "mData": "learning"},
                    {"sTitle": "Bloom's Action Verbs", "mData": "bloom_actionverbs"},
                    {"sTitle": "Edit", "mData": "edit"},
                    {"sTitle": "Delete", "mData": "delete"}
                ], "aaData": data,
                "sPaginationType": "bootstrap"
            });
}
//Bloom's Level List Page Script Ends Here