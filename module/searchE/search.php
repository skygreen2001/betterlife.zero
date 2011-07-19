<TITLE>红蜘蛛正在搜索关键词为[<?php echo $KeyWords?>]的网页</TITLE>
<link rel="stylesheet" href="../All.css" type="text/css">
<body bgcolor="#eeffee">
    <table width="96%" border="0" cellspacing="2" cellpadding="2" align="center" bgcolor="#99CC00">
        <tr>
            <td>
                <div align="center"><font color="#FF0000"><b><span class="pt16">红蜘蛛搜索引擎</span>
                            <span class="pt12">V0.1</span></b></font></div>
            </td>
        </tr>
    </table>
    <form name="form1" method="post" action="Search.php">
        <table width="96%" border="1" cellspacing="1" cellpadding="1" align="center" bordercolor="#99CC00">
            <tr>
                <td valign="top">
                    <font color="#FF0000"><b><span class="pt13">关键字</span></b><span class="pt13">：</span></font>
                    <input type="text" name="KeyWords" value="<?php echo $KeyWords?>" size="30" maxlength="30">
                    <input type="submit" name="Submit" value="重新搜索">
                </td>
                <td valign="top">
                    <font color="#FF0000"><b><span class="pt13">查找范围</span></b><span class="pt13">：</span></font>
                    <select name="SearchIn">
                        <option value="Content" <?php if ($SearchIn=="Content") echo "selected";?>>网页正文</option>
                        <option value="Title" <?php if ($SearchIn=="Title") echo "selected";?>>网页标题</option>
                    </select>
                </td>
                <td valign="top">
                    <div align="left"><font color="#FF0000"><b><span class="pt13">查找方式</span></b><span class="pt13">：</span></font>
                        <select name="Speed">
                            <option value="Fast" <?php if ($Speed=="Fast") echo "selected";?>>快速查找</option>
                            <option value="Slow" <?php if ($Speed=="Slow") echo "selected";?>>更深查找</option>
                        </select>
                    </div>
                </td>
            </tr>
        </table>
    </form>
    <?php
    if($SearchIn=="Title") $SQL="SELECT Id,Url,Title,Content FROM WebPageFindFast WHERE ";
    else $SQL="SELECT Id,Url,Title,Content FROM WebPageFind$Speed WHERE ";
    $KeyWords=str_replace("　", " ", $KeyWords);
    if($KeyWords=="") {
        echo "关键字不能为空";
        exit();
    }
    $tok = strtok($KeyWords," ");
    $i=0;
    $j=0;
    while($tok) {
        $i++;
        $tok = strtok(" ");
    }
    $key = strtok($KeyWords," ");
    while($key) {
        $j++;
        if(substr($key,0,1)!="-") {
            $SQL=$SQL.$SearchIn." LIKE '%".$key."%' ";
            $Words[]=$key;
        }
        else {
            $SQL=$SQL.$SearchIn." NOT LIKE '%".substr($key,1)."%' ";
        }
        if($j<$i) $SQL.=" AND ";
        $key = strtok(" ");
    }
    if(@$CurPos!="") $SQL.=" AND Id>=$CurPos ";
    $SQL.=" LIMIT 100";
//echo "$SQL=".$SQL."<br>";
    mysql_connect("localhost","root","");
    $result=mysql_db_query("Spider",$SQL);
//    echo $SQL;
    $RowCount=mysql_num_rows($result);
    $FindCount=0;
    ?>
    <table border=0 align=center width="96%">
        <tr>
            <th nowrap width="41%">
                <div align="left" class="pt12">共找到关键字为 <font color=red>
                        <?php echo $KeyWords?>
                    </font> 的网页共 <font color=red>
                        <?php echo $RowCount;?>
                    </font> 个</div>
            </th>
            <td nowrap></td>
        </tr>
        <tr bgcolor="#FF0000">
            <th nowrap colspan="2" height="3"></th>
        </tr>
        <?php while($row= mysql_fetch_array($result)) {
            $Pos=$row[0];
            $FindCount++;
            if($FindCount>20) break;?>
        <tr>
            <td nowrap colspan="2">
                    <?php echo $FindCount;?>
                <a href="<?php echo $row[1]?>" target=_black>
                        <?php if($row[2]!="") echo $row[2];
                        else echo substr($row[3],0,64);
                        ?>
                </a></td>
        </tr>
        <tr>
            <td colspan="2" ><span class="pt13">摘要：</span>
                    <?php
                    if($SearchIn=="Title") {
                        $ZhaiYao=substr($row[3],0,1024);
                    }
                    else {
                        if($Speed=="Fast") $ZhaiYao=$row[3];
                        else {
                            $RowLen=strlen($row[3]);
                            if ($RowLen<1024) {
                                $ZhaiYao=$row[3];
                            }
                            else {
                                $CutPos=0;
                                $PosWord1=strpos($row[3],$Words[0]);
                                if($PosWord1-512<0) $ZhaiYao=substr($row[3],0,1024);
                                else {
                                    for($i=24;$i<500;$i++) { //避免将中文字符从半个字处截断，选择从英文处截断
                                        if(ord(substr($row[3],$PosWord1-$i,1))<128) {
                                            $CutPos=$i;
                                            break;
                                        }
                                    }
                                    $ZhaiYao=substr($row[3],$PosWord1-$CutPos,1024);
                                }
                            }
                        }
                        for($i=0;$i<count($Words);$i++) {
                            $ZhaiYao=str_replace($Words[$i],"<font color=red>".$Words[$i]."</font>", $ZhaiYao);
                        }
    }
    echo $ZhaiYao;
    ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right"><a href="One.php?num=<?php echo $row[0]?>" target=_black>
                    <font color="#0033FF" class="pt12">本地镜像</font></a></td>
        </tr>
        <tr bgcolor="#999933">
            <td nowrap colspan="2" height="1"></td>
        </tr>
    <?php } ?>
<?php if($RowCount>20) { ?>
        <tr>
            <td align="right" colspan="2" height="10">
                <form name="form2" method="post" action="Search.php">
                    <input type="hidden" name="KeyWords" value="<?php echo $KeyWords;?>">
                    <input type="hidden" name="SearchIn" value="<?php echo $SearchIn;?>">
                    <input type="hidden" name="Speed" value="<?php echo $Speed;?>">
                    <input type="hidden" name="CurPos" value="<?php echo $Pos;?>">
                    <input type="submit" name="Submit" value="下20个网页">
                </form>
            </td>
        </tr>
    <?php } ?>
    </table>