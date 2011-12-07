(function($){
	function submit_story() {

        alert('submit story');
    }
	$('#poi_list_title_submit').click(function(){
		var title = $("#poi_list_title").val();
		var section_id = $("#section_id").val();
		if (title== null || section_id == null) {
			alert('标题不能为空');
			return ;
		}
		$.ajax({
			type	: 	"POST",
			url		: 	"/pos/save_poi_list",
			dataType: 	"json",
			data	:   {"title": title,"section_id": section_id },
			success	: 	function(json) {
				if(json.code == 200) {
					alert("添加成功");
					location.href ="/pos/add_poi/"  + json.poi_list_id;
				} else {
					alert(json.code);
				}
			}
		});
	});
	
	$("#story_title_input").focus(function(){
		var df = "输入store title";
		if ($("#story_title_input").val() == df ) {
			$(this).val("");
		}
	});
	
	$("#pb-poi-submit").click(function(){
		var title = $("input[name='poi_title']").val();
		var address = $("input[name='poi_address']").val();
		var brief = $("input[name='poi_brief']").val();
		var area = $("input[name='poi_area']").val();
		var posxy = $("input[name='poi_posxy']").val();
		var price = $("input[name='poi_price']").val();
		var phone = $("input[name='poi_phone']").val();
		var introduction = $("textarea[name='poi_introduction']").val();
		
		var img_list = new Array();
		$('#pb-photo-list li').each(function(i){
			var img  = new Object();
			var img_id = $(this).attr('img-id');
			var img_title = $(this).find('textarea').val();
			img['image_id'] = img_id;
			img['title'] = img_title;
			img_list.push(img);
		});
		
		$.ajax({
			type	:	"POST",
			url		:   "/pos/save_poi",
			dataType: 	"json",
			data	:   {"title": title, "address": address,"brief": brief, "introduction" :introduction, "area": area, "posxy": posxy, "price": price, "phone": phone, "images" : img_list },
			success	: 	function(json) {
				if(json.code == 200) {
					alert("添加成功");
				} else {
					alert('添加失败');
				}
			}
			
		});
		
		
	});
	
	$("#pb-submit").click(function(){
		var title = $("#story_title_input").val();
		var content = editor.getContent();
		var img_list = new Array();
		$('#pb-photo-list li').each(function(i){
			var img  = new Object();
			var img_id = $(this).attr('img-id');
			var img_title = $(this).find('textarea').val();
			img['image_id'] = img_id;
			img['title'] = img_title;
			img_list.push(img);
		});
		
		//var ops_id = $
		$.ajax({
			type	:	"POST",
			url		:   "/pos/save_story",
			dataType: 	"json",
			data	:   {"ops_id": ops_id, "title": title,"content": content,"images" : img_list },
			success	: 	function(json) {
				if(json.success == 1) {
					$(".editable").show();
					$("#title_editing").hide();
					$("#ops_title_h1").text(title);
					$("#ops_id_input").val(json.ops_id);
				}
			}
			
		});
		//var content = $("#story_");
	});
	
	
	
})(jQuery);
