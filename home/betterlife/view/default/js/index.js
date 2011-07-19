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