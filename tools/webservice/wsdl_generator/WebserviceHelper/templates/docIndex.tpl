<!-- BEGIN: main -->
<html>
<head><title>Documentation</title></head>
<body>
<script>
oldC=null;
function displayClass(className){
    var c=document.getElementById('class_'+className);
    if(oldC)
        oldC.style.display='none';
    if(c){
        c.style.display='';
        oldC=c;
    }
}
</script>
<a href="{wsdlurl}">WSDL file</a><br>
<b>Classes</b>:
<table width="100%">
<tr>
    <td valign="top">
    <!-- BEGIN: class -->
        <table>
        <tr><td><a href="javascript:void(0)" onclick="displayClass('{class.name}')">{class.name}</a></td></tr>
        <tr><td>
            <table id="class_{class.name}" style="display:none">
            <!-- BEGIN: method -->
            <tr><td></td><td><a href="?class={class.name}#method_{method.name}">{method.fullName}</a></td>
            <!-- END: method -->
            <!-- BEGIN: property -->
            <tr><td></td><td><a href="?class={class.name}#property_{property.name}">{property.name}</a></td>
            <!-- END: property -->
            <!-- BEGIN: constant -->
            <tr><td></td><td>{constant.name}</td>
            <!-- END: constant -->
            </table>
        </td></tr></table>
    <!-- END: class -->
    </td>
    <td valign="top">{main}</td>
</tr>
</table>
</body>
</html>
<!-- END: main -->