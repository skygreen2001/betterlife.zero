/**
 * 解决IE6 Css样式input[type=submit]兼容性的问题 
 */
$(function() {                                  
	$("input").hover(function(){
		$(this).css("color","#000");          
		$(this).css("background-color","#FFF");   
		$(this).css("border","1px solid green"); 
	},function(){
		$(this).css("color","#FFF");          
		$(this).css("background-color","gray");  
		$(this).css("border","1px solid green");
	})
	$(".btnSubmit").hover(function(){
		$(this).css("color","#000");          
		$(this).css("background-color","#FFF");   
		$(this).css("border","1px solid green"); 
	},function(){
		$(this).css("color","#FFF");          
		$(this).css("background-color","#000");  
		$(this).css("border","1px solid green");
	})           	
});

/**
 * 添加在线编辑器KindEditor 
 */
function showHtmlEditor(form_name,elementID){     
	KE.show( {
		id : elementID,
		afterCreate : function(id) {
			KE.event.ctrl(document, 13, function() {
				KE.util.setData(id);
				document.forms[form_name].submit();
			});
			KE.event.ctrl(KE.g[id].iframeDoc, 13, function() {
				KE.util.setData(id);
				document.forms[form_name].submit();
			});
		}	
	});      
}    