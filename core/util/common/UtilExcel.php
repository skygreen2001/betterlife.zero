<?php
/**
 +---------------------------------<br/>
 * 功能:处理文件目录相关的事宜方法。<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilExcel extends Util
{
    /**
    * 将数组转换成Excel文件
    * 示例:
    *     1.直接下载:UtilExcel::arraytoExcel($arr_output_header,$regions,"regions.xlsx",true);
    *     2.保存到本地指定路径:
    * @param array $arr_output_header 头信息数组
    * @param array $excelarr 需要导出的数据的数组
    * @param string $outputFileName 输出文件路径
    * @param bool $isDirectDownload 是否直接下载。默认是否，保存到本地文件路径
    */
    public static function arraytoExcel($arr_output_header,$excelarr,$outputFileName=null,$isDirectDownload=false,$isExcel2007=false)
    {
        UtilFileSystem::createDir(dirname($outputFileName));
        $objActSheet=array ();
        $objExcel=new PHPExcel();
        if ($isExcel2007){
            if(!function_exists("zip_open")){LogMe::log("后台下载功能需要Zip模块支持,名称:php_zip<br/>",EnumLogLevel::ALERT);die();}
            $objWriter=new PHPExcel_Writer_Excel2007($objExcel);
        }else{
            $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        }
        $objExcel->setActiveSheetIndex(0);
        $objActsheet=$objExcel->getActiveSheet();

        //获取表内容
        $i=0;
        if (!empty($excelarr)){
            foreach($excelarr as $record)
            {
                $column='A';
                foreach($arr_output_header as $key=>$value)
                {
                    if($i==0)
                    {
                        if ($column>'A')$value=str_replace(array('标识','编号','主键'),"",$value);
                        $objActsheet->setCellValue($column."1",$value);
                        $objActsheet->setCellValue($column."2",$record->$key);
                        $j=2;
                    }
                    else
                    {
                        $objActsheet->setCellValue($column.$i,$record->$key);
                    }
                    $column++;
                }
                if($j==2)
                {
                    $i=2;
                    $j=0;
                }
                $i++;
            }
        }else{
            $column='A';
            foreach($arr_output_header as $key=>$value)
            {
                if($i==0)
                {
                    if ($column>'A') $value=str_replace(array('标识','编号','主键'),"",$value);
                    $objActsheet->setCellValue($column."1",$value);
                    $objActsheet->setCellValue($column."2",$record->$key);
                    $j=2;
                }
                else
                {
                    $objActsheet->setCellValue($column.$i,$record->$key);
                }
                $column++;
            }
        }
        if (empty($outputFileName)){
            if ($isExcel2007)$outputFileName=date("YmdHis").".xlsx";else$outputFileName=date("YmdHis").".xls";
        }else{
            if ($isExcel2007){
                if (endWith($outputFileName,".xls"))$outputFileName=str_replace(".xls", ".xlsx", $outputFileName);
            }else{
                if (endWith($outputFileName,".xlsx"))$outputFileName=str_replace(".xlsx", ".xls", $outputFileName);
            }
        }

        if ($isDirectDownload){
            $outputFileName=basename($outputFileName);
            ob_end_clean();
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            Header("Content-Disposition:attachment;filename= ".$outputFileName);
            //header('Content-Disposition:inline;filename="'.$outputFileName.'"');
            header("Content-Transfer-Encoding: binary");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: no-cache");
            $objWriter->save('php://output');
        } else{
            //导出到服务器
            //$outputFileName=UtilString::utf82gbk($outputFileName);
            $objWriter->save($outputFileName);
        }
    }

    /**
     * Excel 日期时间转换Php认知的日期时间格式
     * @link http://hi.baidu.com/greenxm/item/80f8f0ce0004bbd297445243
     * @param mixed $days
     * @param mixed $time
     */
    public static function exceltimtetophp($days,$time=false)
    {
        if(is_numeric($days))
        {
            $jd = GregorianToJD(1, 1, 1970);
            $gregorian = JDToGregorian($jd+intval($days)-25569);
            $myDate = explode('/',$gregorian);
            $myDateStr= str_pad($myDate[2],4,'0', STR_PAD_LEFT)."-".str_pad($myDate[0],2,'0',STR_PAD_LEFT)."-".str_pad($myDate[1],2,'0', STR_PAD_LEFT).($time?" 00:00:00":'');
            return $myDateStr;
        }
        return $days;
    }


    /**
     * 从Excel文件获取行数据转换成数组
     * @param ByteArray $byte
     * @return array
     */
    public static function exceltoArray($importFileName,$arr_import_header)
    {
        $result=null;
        $filetype=end(explode('.', $importFileName));

        if(empty($importFileName))
        {
            LogMe::log('路径或文件名有错！');
            return null;
        }
        if($filetype=='xls'||$filetype=='xlsx')
        {
            $PHPExcel=new PHPExcel();
            $PHPReader=new PHPExcel_Reader_Excel2007();
            if(!$PHPReader->canRead($importFileName))
            {
                $PHPReader=new PHPExcel_Reader_Excel5();
                if(!$PHPReader->canRead($importFileName))
                {
                    LogMe::log('请确保Excel格式正确！');
                    return null;
                }
            }
            try
            {
                $PHPExcel=$PHPReader->load($importFileName);
                $currentSheet=$PHPExcel->getSheet(0);
                //取得excel的sheet
                $allColumn=$currentSheet->getHighestColumn(); //表中列数
                $allRow=$currentSheet->getHighestRow(); //表中行数
            }
            catch(Exception $e)
            {
                LogMe::log($e);
                return null;
            }

            $num_tempcol=alphatonumber($allColumn);
            $currentColumn='A';
            $num_currentColumn=alphatonumber($currentColumn);
            //从Excel文档中获取头信息
            for($num_currentColumn;$num_currentColumn<=$num_tempcol;$num_currentColumn++){
                $address=$currentColumn."1";
                $header[]=trim($currentSheet->getCell($address)->getValue());
                $currentColumn++;
            }
            $arr_import_header=array_flip($arr_import_header);
            foreach ($header as $value) {
                if (!array_key_exists($value,$arr_import_header)){
                    $key_words=array('标识','编号','主键');
                    foreach ($key_words as $key_word) {
                        if (array_key_exists($value.$key_word,$arr_import_header)){
                            $value=$value.$key_word;
                            break;
                        }
                    }
                   if (!array_key_exists($value,$arr_import_header))$arr_head[]=$value;
                }
                if (!in_array($value,$arr_head)&&array_key_exists($value,$arr_import_header))$arr_head[]=$arr_import_header[$value];
            }

            //从Excel文档中获取所有内容
            for($currentRow=2,$i=1;$currentRow<=$allRow;$currentRow++,$i++)
            {
                $num_tempcol=alphatonumber($allColumn);
                $currentColumn='A';
                $num_currentColumn=alphatonumber($currentColumn);
                for($num_currentColumn;$num_currentColumn<=$num_tempcol;$num_currentColumn++)
                {
                    $address=$currentColumn.$currentRow;
                    $result[$i][]=trim($currentSheet->getCell($address)->getValue());
                    ++$currentColumn;
                }
            }

            //将头信息数组作为键，内容数组作为Value；获取可转化为数据对象的数组
            if ($result){
                $result_tmp=array();
                foreach ($result as $value) {
                    $result_tmp[]=array_combine($arr_head,$value);
                }
                $result=$result_tmp;
            }
        }
        return $result;
    }

}
?>
