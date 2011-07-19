<?php
/**
 * 获取中国地理数据
 *
 * @author green
 */
class Place_China {

    public function province() {
    }

    public static function place() {

//        $content=Data_Take_Normal::getHtmlContent("http://www.dianping.com/citylist");
//         echo "<pre><br/>";
//        echo $content;
//         echo "</pre><br/>";

        ini_set('user_agent',Data_Take_Normal::$user_agent);
        $html = file_get_html('http://www.dianping.com/citylist');

// Find all images
        foreach($html->find('img') as $element)
            echo $element->src . '<br>';
// Find all links
        foreach($html->find('a') as $element)
            echo $element->plaintext . '<br>';
//        return $content;
    }



}



?>

