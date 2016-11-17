     // * Javascript函数。<br/>
     // * 将xml字符串转换成JSon
     // * @link http://blog.zenme.org/?action=show&id=271&page=1
        
    // creates an XMLHttpRequest instance
    function createXmlDom(response)
    {
        var responseXml;
        if (document.implementation.createDocument) { // mozilla  
            var parser = new DOMParser();  
            responseXml = parser.parseFromString(response, 'text/xml');  
        } else if (window.ActiveXObject) { // ie  
            responseXml = new ActiveXObject('Microsoft.XMLDOM');  
            responseXml.async = 'false';  
            responseXml.loadXML(response);  
        } 
        return responseXml;
    }
      
    function parseXML(xml /* req.responseXML */) {  
        var obj = { _text : "" };  
        var child = xml.firstChild;  
        while (child) {  
            if ((child.nodeName == "#text") || (child.nodeName =="#cdata-section")) {
                obj._text += child.nodeValue;  
            } else if (child.nodeType == 1) {  
                if (typeof obj[child.nodeName] == "undefined") {  
                    obj[child.nodeName] = [];  
                }  
                obj[child.nodeName][obj[child.nodeName].length] = parseXML(child);  
            }  
            child = child.nextSibling;  
        }  
        var att = xml.attributes;  
        if (att) { // there are attributes  
            for (var i = 0; i < att.length; i++) {  
                obj[att[i].name] = att[i].value;  
            }  
        }  
        if (obj._text.match(/^\s*$/)) {  
            delete obj._text;  
        }  
        return obj;  
    }  
    
    // Changes XML to JSON
    function xmltoJson(xml) {
        
        // Create the return object
        var obj = {};

        if (xml.nodeType == 1) { // element
            // do attributes
            if (xml.attributes.length > 0) {
            obj["@attributes"] = {};
                for (var j = 0; j < xml.attributes.length; j++) {
                    var attribute = xml.attributes.item(j);
                    obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
                }
            }
        } else if (xml.nodeType == 3) { // text
            obj = xml.nodeValue;
        }

        // do children
        if (xml.hasChildNodes()) {
            for(var i = 0; i < xml.childNodes.length; i++) {
                var item = xml.childNodes.item(i);
                var nodeName = item.nodeName;
                if (typeof(obj[nodeName]) == "undefined") {
                    obj[nodeName] = xmltoJson(item);
                } else {                    
                    if (typeof(obj[nodeName].length) == "undefined") {
                        var old = obj[nodeName];
                        obj[nodeName] = [];
                        obj[nodeName].push(old);
                    }

                    if (typeof(obj[nodeName]) == "object") {
                        obj[nodeName].push(xmltoJson(item));
                    }
                }
            }
        }
        return obj;
    }
    
   /*
    * 用来遍历指定对象所有的属性名称和值
    * obj 需要遍历的对象
    * author: Jet Mah
    * website: http://www.javatang.com/archives/2006/09/13/442864.html 
    */ 
    function allProps(obj) { 
        // 用来保存所有的属性名称和值
        var props = "";
        // 开始遍历
        for(var p in obj){ 
            // 方法
            if(typeof(obj[p])=="function"){ 
                obj[p]();
            }else{ 
                // p 为属性名称，obj[p]为对应属性的值
                props+= p + "=" + obj[p] + "\t";
            } 
        } 
        // 最后显示所有的属性
        alert(props);
    }  
    
    function dump(arr,level) {
        var dumped_text = "";
        if(!level) level = 0;
        
        //The padding given at the beginning of the line.
        var level_padding = "";
        for(var j=0;j<level+1;j++) level_padding += "    ";
        
        if(typeof(arr) == 'object') { //Array/Hashes/Objects 
            for(var item in arr) {
                var value = arr[item];
                
                if(typeof(value) == 'object') { //If it is an array,
                    dumped_text += level_padding + "'" + item + "' ...\n";
                    dumped_text += dump(value,level+1);
                } else {
                    dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
                }
            }
        } else { //Stings/Chars/Numbers etc.
            dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
        }
        return dumped_text;
    }