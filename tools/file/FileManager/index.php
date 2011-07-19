<?php     
   require_once("../../../Gc.php");
?> 
    <script language="Javascript" type="text/javascript">
    <!--
        function go(arg) {
                location.href='FileManager.php?frame=3&dir_atual='+arg+'/';
        }
        go('<?php echo str_replace("\\","\/",Gc::$nav_root_path);?>');
    //-->
    </script>
