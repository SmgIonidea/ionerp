/* You may use scrollspy along with creating and removing elements form DOM. 
 * But if you do so, you have to call the refresh method . 
 * The following code shows how you may do that:
 */

$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});

//set cookie
if ($.cookie('remember_curriculum') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
    select_mapped_po_peo();
}

/* Function is used to fetch mapped POs & PEOs.
 * @param-
 * @retuns - the table grid view of po peo mapped report details.
 */
function select_mapped_po_peo()
{
    $.cookie('remember_curriculum', $('#crclm option:selected').val(), {expires: 90, path: '/'});
    $('#loading').show();
    var base_url = $('#get_base_url').val();
    var data_val1 = document.getElementById('crclm').value;
    if (!data_val1)
	$("a#export").attr("href", "#");
    else
    {
	$("a#export").attr("onclick", "generate_pdf(" + 0 + ");");
	$("a#export_doc").attr("onclick", "generate_pdf(" + 1 + ");");
    }

    var post_data = {
	'crclm_id': data_val1,
    }
    if (data_val1) {
	$.ajax({type: "POST",
	    url: base_url + 'report/po_peo_map_report/fetch_po_peo_mapping_details',
	    data: post_data,
	    success: function (msg) {
		document.getElementById('po_peo_mapped_report_table_id').innerHTML = msg;
		$('#loading').hide();
	    }
	});
	$.ajax({type: "POST",
	    url: base_url + 'report/po_peo_map_report/justification',
	    data: post_data,
	    success: function (msg) {
		if (msg != 0) {
		    $('#justification_view').attr("class", "bs-docs-example");
		    document.getElementById('justification_view').innerHTML = msg;
		} else {
		    $('#justification_view').attr("class", "1");
		    document.getElementById('justification_view').innerHTML = "";
		}
	    }
	});
	$.ajax({type: "POST",
	    url: base_url + 'report/po_peo_map_report/individual_justification',
	    data: post_data,
	    success: function (msg) {
		if (msg != 0) {
		    $('#individual_justification_view').attr("class", "bs-docs-example");
		    document.getElementById('individual_justification_view').innerHTML = msg;
		} else {
		    $('#individual_justification_view').attr("class", "1");
		    document.getElementById('individual_justification_view').innerHTML = "";
		}
	    }
	});

    } else {
	document.getElementById('po_peo_mapped_report_table_id').innerHTML = '';
	$('#loading').hide();
    }
}

function generate_pdf(type)
{
    if (type == '0') {
	$('#doc_type').val('pdf');
    } else {
	$('#doc_type').val('word');
    }
    var cloned = $('#po_peo_mapped_report_data').clone().html();
    cloned = cloned + '</span><br />' + $('#individual_justification_view').html() + $('#justification_view').html() ;
    $('#pdf').val('<b>Curriculum : </b><span style="color:blue;">' + $("#crclm option:selected").text() + '</span><br />' + cloned);
    $('#form_id').submit();
}


