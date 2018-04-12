
	//language script
	
	$(function() {
		var KeywordsData ={};
		
		//$.cookie("locale_lang", "us");
		
		$(".lg_dropdown a").click(function() {
			$.cookie("locale_lang", $(this).data("value"));
			
			var base_url = $('#get_base_url').val();
			var url = base_url+"locale/"+$.cookie("locale_lang")+"-keywords.json";

			$.getJSON(url,function(data) {
				$("*[data-key]").each(function() {
					$(this).text(data[$(this).data('key')]);
				});
			});
		});
	
		if($.cookie("locale_lang") == "en" || !$.cookie("locale_lang")) {
			return false;
		}
		
		var base_url = $('#get_base_url').val();
		var url = base_url+"locale/"+$.cookie("locale_lang")+"-keywords.json";

		$.getJSON(url,function(data) {
			$("*[data-key]").each(function() {
				$(this).text(data[$(this).data('key')]);
			});
		});
	});