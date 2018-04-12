if ($.cookie('remember_dept') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#dept_id option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
    select_users();
}

function select_users() {

    $.cookie('remember_dept', $('#dept_id option:selected').val(), {expires: 90, path: '/'});
    var dept_id = $('#dept_id').val();
    if (dept_id != 0) {
        $('.faculty_details').hide();
        $('#export').hide();
        post_data = {'dept_id': dept_id}
        $.ajax({type: "POST",
            url: base_url + 'report/faculty_history/select_users',
            data: post_data,
            //dataType:'json',
            success: function (data) {
                $('#user_list').html(data);

                if ($.cookie('remember_user') !== null) {
                    // set the option to selected that corresponds to what the cookie is set to
                    $('#user_list option[value="' + $.cookie('remember_user') + '"]').prop('selected', true);
                    user_list();
                }
            }
        });
    } else {
        $('.faculty_details').hide();
        $('#export').hide();
    }

}
function generate_pdf()
{
    $('#pdf').val(" ");
    $('#add_brk').hide();
    $(".add_attr_style1").removeAttr("style"); // $(".add_attr_style").removeAttr("style");
    var cloned = $('#export_data').clone().html();
    $('#pdf').val('<b>Department : </b><span style="color:blue;">' + $("#dept_id option:selected").text() + '</span><br /><br/><br/>' + cloned);
    $('#form_id').submit();
    $('#add_brk').show();
    $('.add_attr_style1').attr('style', 'position: relative;-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 0px;border:1px solid #C4C0BE;background-color:#F7F7F7;-webkit-box-shadow: #C4C4C4 10px 10px 10px;-moz-box-shadow: #C4C4C4 10px 10px 10px; box-shadow: #C4C4C4 10px 10px 10px;width:auto;');
}

function user_list() {

    //$('#dept_id option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
    $.cookie('remember_user', $('#user_list option:selected').val(), {expires: 90, path: '/'});

    var dept_id = $('#dept_id').val();
    var user_list = $('#user_list').val();
    post_data = {'dept_id': dept_id, 'user_list': user_list}
    if (user_list != "" && dept_id != 0) {

        $('#export').show();
        $.ajax({type: "POST",
            url: base_url + 'report/faculty_history/fetch_faculty_contribution',
            data: post_data,
            dataType: 'json',
            success: function (data) {
                $('.faculty_details').show();
                fetch_faculty_contribution(data);
                //$.cookie('remember_user', $('#user_list option:selected').val(), {expires: 90, path: '/'});      
            }
        });

        $.ajax({type: "POST",
            url: base_url + 'report/faculty_history/user_basic',
            data: post_data,
            success: function (data) {
                $('#user_basic_data').html(data);
            }
        });



    } else {
        $('.faculty_details').hide();
        $('#export').hide();
    }
}
$(document).ready(function () {

    $("a#export ,#export_bottom").attr("onclick", "generate_pdf();");
    //$("a#export_bottom").attr("onclick", "generate_pdf();");

    $('#user_list').live('change', function () {

        $.cookie('remember_user', $('#user_list option:selected').val(), {expires: 90, path: '/'});

        var dept_id = $('#dept_id').val();
        var user_list = $('#user_list').val();
        post_data = {'dept_id': dept_id, 'user_list': user_list}
        if (user_list != "" && dept_id != 0) {

            $('#export').show();
            $.ajax({type: "POST",
                url: base_url + 'report/faculty_history/fetch_faculty_contribution',
                data: post_data,
                dataType: 'json',
                success: function (data) {
                    $('.faculty_details').show();
                    fetch_faculty_contribution(data);
                    //$.cookie('remember_user', $('#user_list option:selected').val(), {expires: 90, path: '/'});

                }
            });

            $.ajax({type: "POST",
                url: base_url + 'report/faculty_history/user_basic',
                data: post_data,
                success: function (data) {
                    $('#user_basic_data').html(data);
                }
            });



        } else {
            $('.faculty_details').hide();
            $('#export').hide();
        }
    });

    function fetch_faculty_contribution(msg) {
        //$.cookie('remember_user', $('#user_list option:selected').val(), {expires: 90, path: '/'});
        //$('#user_list option[value="' + $.cookie('remember_user') + '"]').prop('selected', true);

        //$('.add_attr_style').attr('style', 'position: relative;-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 0px;border:1px solid #C4C0BE;background-color:#F7F7F7;-webkit-box-shadow: #C4C4C4 10px 10px 10px;-moz-box-shadow: #C4C4C4 10px 10px 10px; box-shadow: #C4C4C4 10px 10px 10px;width:1060px;');
        if (msg.example13 != "none") {
            $('#example13_div').show();
            $('#example13').dataTable().fnDestroy();
            $('#example13').dataTable(
                    {"sSort": false,
                        "sPaginate": false,
                        "bPaginate": false,
                        "bFilter": false,
                        "bInfo": false,
                        "aoColumns": [
                            {"sTitle": "Program Title No", "mData": "program_title"},
                            {"sTitle": "Level", "mData": "level"},
                            {"sTitle": "Type", "mData": "training_type"},
                            {"sTitle": "Co-ordinator(s)", "mData": "coodinators"},
                            {"sTitle": "Duration", "mData": "hours"},
                            {"sTitle": "From date", "mData": "from_date"},
                            {"sTitle": "To date", "mData": "to_date"},
                            {"sTitle": "Sponsored by", "mData": "sponsored_by"},
                            {"sTitle": "Role", "mData": "role_fetched"},
                            {"sTitle": "View", "mData": "upload"},
                        ], "aaData": msg['example13'],
                        "sPaginationType": "bootstrap",
                    });

            $('#example13').dataTable().fnDestroy();
            $('#example13').dataTable({
                "sSort": false,
                "sPaginate": false,
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                "sPaginationType": "bootstrap",
                "fnDrawCallback": function () {
                    $('.group').parent().css({'background-color': '#C7C5C5'});
                }
            }).rowGrouping({iGroupingColumnIndex: 8,
                bHideGroupingColumn: true});
        } else {
            $('#example13_div').hide();
        }
        /** Function to display qyalification details**/

        if (msg.example1 != "none") {
            $('#my_qualification_tbl_div').show();

            $('#my_qualification_tbl').dataTable().fnDestroy();
            $('#my_qualification_tbl').dataTable(
                    {
                        "sSort": false,
                        "sPaginate": false,
                        "bPaginate": false,
                        "bFilter": false,
                        "bInfo": false,
                        "sEmptyTable": "Your custom message for empty table",
                        "aoColumns": [
                            {"sTitle": "Sl No.", "mData": "sl_no"},
                            {"sTitle": "Qualification", "mData": "qualification"},
                            {"sTitle": "University", "mData": "university"},
                            {"sTitle": "Year Of Graduation", "mData": "yog"},
                            {"sTitle": "View", "mData": "upload"},
                        ], "aaData": msg['example1'],
                        "sPaginationType": "bootstrap",
                    });
        } else {
            $('#my_qualification_tbl_div').hide();
        }

        /** Function to display workload details**/
        if (msg.example2 != "none") {
            $('#example2_div').show();
            $('#example2').dataTable().fnDestroy();
            $('#example2').dataTable(
                    {"sSort": false,
                        "sPaginate": false,
                        "bPaginate": false,
                        "bFilter": false,
                        "bInfo": false,
                        "aoColumns": [
                            {"sTitle": "Sl No.", "mData": "sl_no"},
                            {"sTitle": "Department", "mData": "dept"},
                            {"sTitle": "Program Type", "mData": "prgm_type"},
                            {"sTitle": "Program", "mData": "program"},
                            {"sTitle": "Program Category", "mData": "program_category"},
                            {"sTitle": "Workload Distribution(in years)", "mData": "year"},
                            {"sTitle": "Academic Year", "mData": "accademic_year", "sClass": "alignright"},
                            {"sTitle": "Workload(%)", "mData": "workload", "sClass": "alignright"},
                        ], "aaData": msg['example2'],
                        "sPaginationType": "bootstrap",
                    });
        } else {
            $('#example2_div').hide();
        }
        /**Function to display research details**/
        if (msg.example3 != "none") {
            $('#example3_div').show();
            $('#example3').dataTable().fnDestroy();
            $('#example3').dataTable({
                "sSort": false,
                "sPaginate": false,
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                "aoColumns": [
                    {"sTitle": "Sl No.", "mData": "sl_no", "sType": "numeric"},
                    {"sTitle": "Project Title", "mData": "title"},
                    {"sTitle": "Co-Author", "mData": "authors"},
                    {"sTitle": "Volume No", "mData": "vol_no", "sClass": "alignright"},
                    {"sTitle": "Pages", "mData": "pages", "sClass": "alignright"},
                    {"sTitle": "Citation count", "mData": "citation_count", "sClass": "alignright"},
                    {"sTitle": "h-index", "mData": "hindex", "sClass": "alignright"},
                    {"sTitle": "i10_index", "mData": "i10_index", "sClass": "alignright"},
                    {"sTitle": "ISSN", "mData": "issn"},
                    {"sTitle": "Sponsored by", "mData": "sponsored_by"},
                    {"sTitle": "Publisher", "mData": "publisher"},
                    {"sTitle": "View", "mData": "view"},
                ], "aaData": msg['example3'],
                "sPaginationType": "bootstrap",
            });
        } else {
            $('#example3_div').hide();
        }
        /**Function to display journal publication details**/
        if (msg.example1_1 != "none") {
            $('#example1_div').show();
            $('#example1').dataTable().fnDestroy();
            $('#example1').dataTable({
                "sSort": false,
                "sPaginate": false,
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                "aoColumns": [
                    {"sTitle": "Sl No.", "mData": "sl_no", "sType": "numeric"},
                    {"sTitle": "Project Title", "mData": "title"},
                    {"sTitle": "Co-Author", "mData": "authors"},
                    {"sTitle": "Volume No", "mData": "vol_no", "sClass": "alignright"},
                    {"sTitle": "Pages", "mData": "pages", "sClass": "alignright"},
                    {"sTitle": "Citation count", "mData": "citation_count", "sClass": "alignright"},
                    {"sTitle": "h-index", "mData": "hindex", "sClass": "alignright"},
                    {"sTitle": "i10_index", "mData": "i10_index", "sClass": "alignright"},
                    {"sTitle": "ISSN", "mData": "issn"},
                    {"sTitle": "Sponsored by", "mData": "sponsored_by"},
                    {"sTitle": "Publisher", "mData": "publisher"},
                    {"sTitle": "View", "mData": "view"},
                ], "aaData": msg['example1_1'],
                "sPaginationType": "bootstrap",
            });
        } else {
            $('#example1_div').hide();
        }
        /** Function to display consultancy projects **/

        if (msg.example6 != "none") {
            $('#example6_div').show();
            $('#example6').dataTable().fnDestroy();
            $('#example6').dataTable(
                    {"sSort": false,
                        "sPaginate": false,
                        "bPaginate": false,
                        "bFilter": false,
                        "bInfo": false,
                        "aoColumns": [
                            {"sTitle": "Sl No.", "mData": "sl_no", "sType": 'numeric'},
                            {"sTitle": "Project Code ", "mData": "project_code"},
                            {"sTitle": "Project Title ", "mData": "project_title"},
                            {"sTitle": "Client", "mData": "client"},
                            {"sTitle": "Consultant", "mData": "consultant"},
                            {"sTitle": "Co-consultant(s)", "mData": "co_consultant"},
                            {"sTitle": "Year", "mData": "year", "sClass": "alignright"},
                            //{"sTitle": "Abstract","mData":"abstract"},
                            {"sTitle": "Status", "mData": "status"},
                            {"sTitle": "View", "mData": "upload"}

                        ], "aaData": msg['example6'],
                        "sPaginationType": "bootstrap",
                    });
        } else {
            $('#example6_div').hide();
        }
        /** Function to display sponsored project details **/
        if (msg.example7 != "none") {
            $('#example7_div').show();
            $('#example7').dataTable().fnDestroy();
            $('#example7').dataTable(
                    {"sSort": false,
                        "sPaginate": false,
                        "bPaginate": false,
                        "bFilter": false,
                        "bInfo": false,
                        "aoColumns": [
                            {"sTitle": "Sl No.", "mData": "sl_no", "sType": 'numeric'},
                            {"sTitle": "Project Code ", "mData": "project_code"},
                            {"sTitle": "Project Title ", "mData": "project_title"},
                            {"sTitle": "Sponsored Organization", "mData": "spo_organization"},
                            {"sTitle": "Principal Investigator", "mData": "investigator"},
                            {"sTitle": "Co-Principal Investigator(s)", "mData": "co_investigator"},
                            {"sTitle": "Year", "mData": "year"},
                            {"sTitle": "Status", "mData": "status"},
                            {"sTitle": "View", "mData": "upload"}

                        ], "aaData": msg['example7'],
                        "sPaginationType": "bootstrap",
                    });
        } else {
            $('#example7_div').hide();
        }

        if (msg.example8 != "none") {
            $('#example8_div').show();
            $('#example8').dataTable().fnDestroy();
            $('#example8').dataTable(
                    {"sSort": false,
                        "sPaginate": false,
                        "bPaginate": false,
                        "bFilter": false,
                        "bInfo": false,
                        "aoColumns": [
                            {"sTitle": "Sl No.", "mData": "sl_no"},
                            {"sTitle": "Award Name", "mData": "award_name"},
                            {"sTitle": "Award for", "mData": "award_for"},
                            {"sTitle": "Sponsored Organization", "mData": "spo_oganization"},
                            {"sTitle": "Year", "mData": "year"},
                            {"sTitle": "Remarks", "mData": "remarks"},
                            {"sTitle": "View", "mData": "upload"}
                        ], "aaData": msg['example8'],
                        "sPaginationType": "bootstrap",
                    });
        } else {
            $('#example8_div').hide();
        }

        if (msg.example9 != "none") {
            $('#example9_div').show();
            $('#example9').dataTable().fnDestroy();
            $('#example9').dataTable(
                    {"sSort": false,
                        "sPaginate": false,
                        "bPaginate": false,
                        "bFilter": false,
                        "bInfo": false,
                        "aoColumns": [
                            {"sTitle": "Sl No.", "mData": "sl_no"},
                            {"sTitle": "Title", "mData": "patent_title"},
                            {"sTitle": "Inventor(s)", "mData": "inventors"},
                            {"sTitle": "Patent No", "mData": "patent_no"},
                            {"sTitle": "Year", "mData": "year"},
                            {"sTitle": "Status", "mData": "status"},
                            {"sTitle": "View", "mData": "upload"},
                        ], "aaData": msg['example9'],
                        "sPaginationType": "bootstrap",
                    });
        } else {
            $('#example9_div').hide();
        }

        if (msg.example10 != "none") {
            $('#example10_div').show();
            $('#example10').dataTable().fnDestroy();
            var table = $('#example10').dataTable(
                    {"sSort": false,
                        "sPaginate": false,
                        "bPaginate": false,
                        "bFilter": false,
                        "bInfo": false,
                        "aoColumns": [
                            {"sTitle": "Sl No.", "mData": "sl_no"},
                            {"sTitle": "Fellowship / Scholarship for", "mData": "fellow_scholar_for"},
                            {"sTitle": "Awarded by", "mData": "awarded_by"},
                            {"sTitle": "Date", "mData": "year"},
                            {"sTitle": "Type", "mData": "type"},
                            {"sTitle": "View", "mData": "upload"},
                        ], "aaData": msg['example10'],
                        "sPaginationType": "bootstrap",
                    });
        } else {
            $('#example10_div').hide();
        }

        if (msg.example11 != "none") {
            $('#example11_div').show();
            $('#example11').dataTable().fnDestroy();
            $('#example11').dataTable(
                    {"sSort": false,
                        "sPaginate": false,
                        "bPaginate": false,
                        "bFilter": false,
                        "bInfo": false,
                        "aoColumns": [
                            {"sTitle": "Title", "mData": "title"},
                            {"sTitle": "Venue", "mData": "venue"},
                            {"sTitle": "Date", "mData": "year"},
                            {"sTitle": "Presentation Type", "mData": "presentation_type"},
                            {"sTitle": "Presentation Role", "mData": "presentation_role"},
                            {"sTitle": "Presentation Level", "mData": "presentation_level"},
                            {"sTitle": "View", "mData": "upload"},
                        ], "aaData": msg['example11'],
                        "sPaginationType": "bootstrap",
                    });

            $('#example11').dataTable().fnDestroy();
            $('#example11').dataTable({
                "sSort": false,
                "sPaginate": false,
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                "sPaginationType": "bootstrap",
                "fnDrawCallback": function () {
                    $('.group').parent().css({'background-color': '#C7C5C5'});
                }
            }).rowGrouping({iGroupingColumnIndex: 4,
                bHideGroupingColumn: true});
        } else {
            $('#example11_div').hide();
        }

        if (msg.example12 != "none") {
            $('#example12_div').show();
            $('#example12').dataTable().fnDestroy();
            $('#example12').dataTable(
                    {"sSort": false,
                        "sPaginate": false,
                        "bPaginate": false,
                        "bFilter": false,
                        "bInfo": false,
                        "aoColumns": [
                            {"sTitle": "Sl No.", "mData": "book_type"},
                            {"sTitle": "Book Title", "mData": "book_title"},
                            {"sTitle": "Book No", "mData": "book_no"},
                            {"sTitle": "Co - Author", "mData": "co_author"},
                            {"sTitle": "ISBN No", "mData": "isbn_no"},
                            {"sTitle": "Copyright Year", "mData": "copyright_year"},
                            {"sTitle": "Year of publication", "mData": "year_of_publication"},
                            {"sTitle": "View", "mData": "upload"},
                        ], "aaData": msg['example12'],
                        "sPaginationType": "bootstrap",
                    });

            $('#example12').dataTable().fnDestroy();
            $('#example12').dataTable({
                "sSort": false,
                "sPaginate": false,
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                "sPaginationType": "bootstrap",
                "fnDrawCallback": function () {
                    $('.group').parent().css({'background-color': '#C7C5C5'});
                }
            }).rowGrouping({iGroupingColumnIndex: 0,
                bHideGroupingColumn: true});
        } else {
            $('#example12_div').hide();
        }


        if (msg.example14 != "none") {
            $('#example14_div').show();
            $('#example14').dataTable().fnDestroy();
            $('#example14').dataTable(
                    {"sSort": false,
                        "sPaginate": false,
                        "bPaginate": false,
                        "bFilter": false,
                        "bInfo": false,
                        "aoColumns": [
                            {"sTitle": "Sl No.", "mData": "sl_no"},
                            {"sTitle": "Department", "mData": "department"},
                            {"sTitle": "Designation", "mData": "designation"},
                            {"sTitle": "Year", "mData": "year"},
                        ], "aaData": msg['example14'],
                        "sPaginationType": "bootstrap",
                    });
        } else {
            $('#example14_div').hide();
        }
    }

//	$('#my_qualification_tbl').on('click',' .view_uploaded_file',function(){
    $('.view_uploaded_file').live('click', function (e) {
        var table_id = ($(this).attr('data-id'));
        var tab_ref_id = ($(this).attr('data-tab_ref_id'));
        var view_user_id = ($(this).attr('data-user_id_detl'));
        post_data = {'table_id': table_id, 'tab_ref_id': tab_ref_id, 'user_id': view_user_id}
        $.ajax({type: "POST",
            url: base_url + 'report/faculty_history/fetch_files',
            data: post_data,
            //dataType:'json',
            success: function (data) {
                $('#View_uploaded_data').html(data);
                $('#view_files').modal('show');
            }
        });

    });

}); 