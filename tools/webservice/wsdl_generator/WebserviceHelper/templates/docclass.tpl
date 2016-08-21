<!-- BEGIN: main -->
<!-- BEGIN: htmlheader -->
<html>
<head>
    <title>WSDL Helper</title>
    <link rel="stylesheet" href="css/doc.css" type="text/css">
</head>
<body>
<!-- END: htmlheader -->
<div id="main">
<div id="mainheader">
<div id="mainheaderpadded">
<h1>{class.name} {wsdlurl}</h1>
</div>
</div>
<div id="mainpadded">
<table cellpadding="0" cellspacing="0">
<tr>
<td id="menu">
    <h2>Classes</h2>
    <!-- BEGIN: menuitem -->
    <a href="?class={class.name}">{class.name}</a><br>
    <!-- END: menuitem -->
</td>
<td id="content">
    <h2>Full description</h2>
    <p>{class.fullDescription}</p>

    <!-- BEGIN: properties -->
    <h2>Properties</h2>
    <!-- BEGIN: property -->
    <a name="property_{property.name}"></a>
    <div class="property{warning}">
    {fullNameExt}<br>
    {typeExt}<br>
    {fullDescriptionExt}
    </div>
    <!-- END: property -->
    <!-- END: properties -->

    <!-- BEGIN: methods -->
    <h2>Methods</h2>
    <!-- BEGIN: method -->
    <a name="method_{method.name}"></a>
    <div class="method{warning}">
    {fullNameExt}<br>
    {paramExt}
    {returnExt}<br>
    {throwsExt}
    {fullDescriptionExt}<br>
    </div>
    <!-- END: method -->
    <!-- END: methods -->
</td>
</tr>
</table>
</div>
<div id="mainfooter"><img src="images/doc/backbottom.jpg"></div>
</div>
<!-- BEGIN: htmlfooter -->
</body>
</html>
<!-- END: htmlfooter -->
<!-- END: main -->