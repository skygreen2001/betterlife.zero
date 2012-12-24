<?php     
   require_once("../../../Gc.php");
?> 
    <script language="Javascript" type="text/javascript">
    <!--
        function go(arg) {
                location.href='FileManager.php?senha=&dir_atual='+arg+'/';//frame=3&
        }
        go('<?php echo str_replace("\\","/",Gc::$nav_root_path);?>');
    //-->
    </script>
