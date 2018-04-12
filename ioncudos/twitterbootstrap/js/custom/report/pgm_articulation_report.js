/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
     The following code shows how you may do that:*/
	  var base_url = $('#get_base_url').val();
    $('[data-spy="scroll"]').each(function() {
        var $spy = $(this).scrollspy('refresh')
    });
    
    //second dropdown - term
    function select_term()
    {
        var data_val = document.getElementById('crclm').value;
		var select_term_path = base_url+'report/pgm_articulation_report/select_term';
        var post_data = {
            'crclm_id': data_val
        }
        $.ajax({type: "POST",
            url: select_term_path, 
            data: post_data,
            success: function(msg) {
                document.getElementById('term').innerHTML = msg;
            }
        });
    }


    //display grid on select of term
    function func_grid()
    {
        var data_val = document.getElementById('term').value;
        var data_val1 = document.getElementById('crclm').value;
		var core = 0;
      /*   if (document.getElementById('course').checked == true)
        {
            var core = 1;
        }
        else
        {
            var core = 0;
        } */

        if (!data_val)
            $("a#export").attr("href", "#");
        else
            $("a#export").attr("onclick", "generate_pdf();");
		var clo_details_path = base_url+'report/pgm_articulation_report/clo_details' ;
		var po_details_path = base_url+'report/pgm_articulation_report/po_details';
        var post_data = {
            'crclm_term_id': data_val,
            'crclm_id': data_val1,
            'core': core,
        }

        $.ajax({type: "POST",
            url: clo_details_path, 
            data: post_data,
            success: function(msg) {
                document.getElementById('course_articulation_matrix_grid').innerHTML = msg;
            }
        });

        $.ajax({type: "POST",
            url: po_details_path, 
            data: post_data,
            success: function(msg1) {
                document.getElementById('text1').innerHTML = msg1;
            }
        });
		
		var termSelect = document.getElementById("term");
        var selectedterm = termSelect.options[termSelect.selectedIndex].text
        document.getElementById("term_name").value = selectedterm;
		
    }

    function generate_pdf() {
        var cloned = $('#course_articulation_matrix_grid').clone().html();
        var cloned1 = $('#po_stmt').clone().html();
		
        $('#pdf').val(cloned);
        $('#stmt').val(cloned1);
        $('#form1').submit();
    }

    function fetch_crclm() {
        var crclmSelect = document.getElementById("crclm");
        var selectedcrclm = crclmSelect.options[crclmSelect.selectedIndex].text
        document.getElementById("curr").value = selectedcrclm;
    }

    function clo_details(temp) {
		var clo_path = base_url+'report/pgm_articulation_report/fetch_clo';
        
		var post_data = {
            'crs_id': temp,
        }

        $.ajax({type: "POST",
            url: clo_path, 
            data: post_data,
            success: function(msg) {
                $('#myModal1').modal('show');
                document.getElementById('comments').innerHTML = msg;
            }
        });
    }
