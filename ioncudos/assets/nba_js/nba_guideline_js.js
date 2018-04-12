$(function(){
	tinymce.init({
		selector: "textarea",
		relative_urls: false,
		plugins: [
		"advlist autolink lists charmap preview ",
		"searchreplace visualblocks fullscreen",
		"table contextmenu paste moxiemanager"
		],
		width: 600,
		//paste_data_images: true,
		setup : function(ed) {
		},
		toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"		
	});
	$('.guideline_save').on('click',function(){
		var base_url = $('#get_base_url').val();
		tinymce.triggerSave();
		var post_data = {
			'guideline' : $('#guideline').val(),
			'is_edit' : $('#is_edit').val()
		};
		$.ajax({
			type: 'post',
			async: false,
			url: base_url+'nba_sar/nba_sar/guide_line_save',
			data: post_data,
			success : function(){
				window.location = base_url+'nba_sar/nba_sar/guideline/'+$('#is_edit').val();
			}
		});
	});
});