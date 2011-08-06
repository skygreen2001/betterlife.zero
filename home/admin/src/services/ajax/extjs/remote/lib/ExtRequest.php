<?php

/**
 * @class Request
 */
class ExtRequest 
{
    public $restful, $method, $controller, $action, $id, $params;
    public $start,$limit;

    public function __construct($params) 
    {
        $this->restful = (isset($params["restful"])) ? $params["restful"] : false;
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->parseRequest();
    }
    
    public function isRestful() 
    {
        return $this->restful;
    }
    
    protected function parseRequest() 
    {
        if ($this->method == 'PUT') {   // <-- Have to jump through hoops to get PUT data
            $raw  = '';
            $httpContent = fopen('php://input', 'r');
            while ($kb = fread($httpContent, 1024)) {
                $raw .= $kb;
            }
            fclose($httpContent);
            $params = array();
            parse_str($raw, $params);

            if (isset($params['data'])) {
                $this->params =  json_decode(stripslashes($params['data']));
            } else {
                $params = json_decode(stripslashes($raw));
                $this->params = $params->data;
            }
        } else {
            // grab JSON data if there...
            $this->params = (isset($_REQUEST['data'])) ? json_decode(stripslashes($_REQUEST['data'])) : null;

            if (isset($_REQUEST['start'])){
                $this->start=$_REQUEST['start'];
            }
            if (isset($_REQUEST['limit'])){
                $this->limit=$_REQUEST['limit'];
            }
            
            if (isset($_REQUEST['data'])) {
                $this->params =  json_decode(stripslashes($_REQUEST['data']));
            } else {
                $raw  = '';
                $httpContent = fopen('php://input', 'r');
                while ($kb = fread($httpContent, 1024)) {
                    $raw .= $kb;
                }
                $params = json_decode(stripslashes($raw));
                
                if ($params!=null){
                    $this->params = $params->data;
                }
            }
            if (strtoupper($this->method)=="GET"){
               unset($_GET["_dc"]); 
               unset($_GET["start"]);                
               unset($_GET["limit"]);  
               $this->params =$_GET;
            }

        }
        // Quickndirty PATH_INFO parser
        if (isset($_SERVER["PATH_INFO"])){
            $cai = '/^\/([a-zA-Z]+\w)\/([a-z]+\w)\/([0-9]+)$/';  // /controller/action/id
            $ca =  '/^\/([a-zA-Z]+\w)\/([a-z]+)$/';              // /controller/action
            $ci = '/^\/([a-zA-Z]+\w)\/([0-9]+)$/';               // /controller/id
            $c =  '/^\/([a-zA-Z]+\w)$/';                         // /controller
            $i =  '/^\/([0-9]+)$/';                           // /id
            $matches = array();
            if (preg_match($cai, $_SERVER["PATH_INFO"], $matches)) {
                $this->controller = $matches[1];
                $this->action = $matches[2];
                $this->id = $matches[3];
            } else if (preg_match($ca, $_SERVER["PATH_INFO"], $matches)) {
                $this->controller = $matches[1];
                $this->action = $matches[2];
            } else if (preg_match($ci, $_SERVER["PATH_INFO"], $matches)) {
                $this->controller = $matches[1];
                $this->id = $matches[2];
            } else if (preg_match($c, $_SERVER["PATH_INFO"], $matches)) {
                $this->controller = $matches[1];
            } else if (preg_match($i, $_SERVER["PATH_INFO"], $matches)) {
                $this->id = $matches[1];
            }
        }
    }
}

?>