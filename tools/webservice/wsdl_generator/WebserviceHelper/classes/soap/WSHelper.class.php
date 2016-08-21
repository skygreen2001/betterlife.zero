<?php

class WSHelper extends SoapServer{
    public $uri;
    public $class         = null;
    private $name;
    private $cacheDir     = null;
    private $persistenceMode = SOAP_PERSISTENCE_REQUEST;
    
    /** Constructor
     * @param string The Uri name
     * @return void
     */
    public function __construct($uri, $cacheDir, $class = null){
        $this->uri         = $uri;
        $this->cacheDir = $cacheDir;
        if (!is_dir($cacheDir)) throw new Exception("The wsdl cache directory is not valid");
        if($class) 
            $this->setClass($class);
    }

    /**
     * Adds the given class name to the list of classes
     * to be included in the documentation/WSDL/Request handlers
     * @param string $name
     * @return void
     */
    public function setClass($name){
        $this->name = $name;
        $this->class = new phpdocClass($name);
        $this->wsdlfile = $this->cacheDir.$this->name.".wsdl";
    }

    /**
     * Handles everything. Makes sure the webservice is handled,
     * documentations is generated, or the wsdl is generated,
     * according to the page request
     * @return void
     */
    public function handle(){
        if(substr($_SERVER['QUERY_STRING'], -4) == 'wsdl'){
            $this->showWSDL();
        }elseif(isset($GLOBALS['HTTP_RAW_POST_DATA']) && strlen($GLOBALS['HTTP_RAW_POST_DATA']) > 0){
            //debug($GLOBALS['HTTP_RAW_POST_DATA']);
            $this->handleRequest();
        }else{
            $this->createDocumentation();
        }
    }
    /**
     * Checks if the current WSDL is up-to-date, regenerates if necessary and outputs the WSDL
     * @return void
     */
    public function showWSDL(){
        //@TODO: nog een mooie oplossing voor het cachen zoeken
        header("Content-type: text/xml");
        if(file_exists($this->wsdlfile)){
            readfile($this->wsdlfile);
        }else{
            //make sure to refresh PHP WSDL cache system
            ini_set("soap.wsdl_cache_enabled",0);
            echo $this->createWSDL();
        }
    }

    private function createWSDL(){
        $wsdl = new WSDLStruct($this->uri, "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?class=".$this->name);
        $wsdl->setService($this->class);

        try {
            $gendoc = $wsdl->generateDocument();
        } catch (WSDLException $exception) {
               $exception->Display();
               exit();
        }

        $fh = fopen($this->wsdlfile, "w+");
        fwrite($fh, $gendoc);
        fclose($fh);

        return $gendoc;
    }

    /**
     * Let the native PHP5 soap implementation handle the request
     * after registrating the class
     * @return void
     */
    private function handleRequest(){
        //check if it's a legal webservice class
        if(!in_array($this->name, $GLOBALS['WSClasses'])){
            throw new Exception('No valid webservice class');
        }

        //check cache
        if(!file_exists($this->wsdlfile))
            $this->createWSDL();

        $options = Array('actor' => WSURI);
        if(is_array($GLOBALS['WSStructures']))
            $options['classmap'] = $GLOBALS['WSStructures'];

        header("Content-type: text/xml");
        $server = new SoapServer($this->wsdlfile, $options);
        $server->setClass($this->name);
        $server->setPersistence($this->persistenceMode);

        try{
            $server->handle();
        }catch(Exception $e){
            $wse = new WSException($e->getMessage());
            echo $wse->toString();
        }
        
    }

    /** Generates the documentations for the
     * webservice usage.
     * @param string Template filename
     * @return void
     */
    public function createDocumentation($template="templates/docclass.tpl")    {
        if(!is_file($template))
            throw new Exception("Could not find the template file: '$template'");
        $xtpl = new XTemplate($template);

        //loop menu items
        if(!is_array($GLOBALS['WSClasses'])) die("No classes?");
        sort($GLOBALS['WSClasses']);//ff sorteren
        
        foreach($GLOBALS['WSClasses'] as $className) {
            $menuClass = new phpdocClass($className);
            $xtpl->assign("class", (array)$menuClass);
            $xtpl->parse("main.menuitem");
        }

        if($this->class){
            //loop properties
            $properties = $this->class->getProperties(false,false);
            foreach((array)$properties as $property) {
                $xtpl->assign("property", (array)$property);

                if($property->type) {
                    $xtpl->assign("typeExt", "<i>type ".$this->createTypeURI($property->type)."</i>");
                } else {
                    $xtpl->assign("typeExt", "<div class='warning'><img src='images/doc/warning.gif'/> missing type declaration</div>");
                    $xtpl->assign("warning", "warning");
                }

                $xtpl->assign("fullNameExt", "<b>{$property->name}</b>");
                if($property->fullDescription)
                    $xtpl->assign("fullDescriptionExt", $property->fullDescription."<br/>");
                else
                    $xtpl->assign("fullDescriptionExt", "");

                $xtpl->parse("main.properties.property");
                $xtpl->assign("warning", "");
            }
            if(count($properties))
                $xtpl->parse("main.properties");

            //loop methods
            $methods = $this->class->getMethods(false, false);
            $methodCount=0;
            foreach((array)$methods as $method) {
                if(substr($method->name, 0, 2)=="__") continue;

                $methodCount++;

                $xtpl->assign("method", (array)$method);

                $params = $method->getParameters();
                $paramExtArr = array();
                foreach($params as $param)
                    $paramExtArr[] = $this->createTypeURI($param->type)." {$param->name}";

                if($method->return) {
                    $xtpl->assign("returnExt", "<i>returns ".$this->createTypeURI($method->return)."</i>");
                } else {
                    $xtpl->assign("returnExt", "<div class='warning'><img src='images/doc/warning.gif'/> missing return type</div>");
                    $xtpl->assign("warning", "warning");
                }

                if($method->throws)
                    $xtpl->assign("throwsExt", "<i>throws ".$method->throws."<br/></i>");
                else
                    $xtpl->assign("throwsExt", "");

                if($method->fullDescription)
                    $xtpl->assign("fullDescriptionExt", $method->fullDescription);
                else {
                    $xtpl->assign("fullDescriptionExt", "<div class='warning'><img src='images/doc/warning.gif'/> missing full description</div>");
                    $xtpl->assign("warning", "warning");
                }

                $xtpl->assign("fullNameExt", "<b>{$method->name}</b> (".implode(", ", $paramExtArr).")");
                $xtpl->parse("main.methods.method");
                $xtpl->assign("warning", "");
            }
            if($methodCount>0) {
                $xtpl->assign("wsdlurl", "<a href='{$_SERVER['REQUEST_URI']}&wsdl'>[WSDL]</a>");
                $xtpl->parse("main.methods");
            }

            $xtpl->assign("class", (array)$this->class);
        }

        $xtpl->parse("main.htmlheader");
        $xtpl->parse("main.htmlfooter");
        $xtpl->parse("main");
        $xtpl->out("main");
    }

    /**
     * creates a possibly linked html representation of a type for the documentation
     *
     * @param string type The type name
     */
    private function createTypeURI($type) {
        if(substr($type, -2)=="[]")
            $name = substr($type, 0, -2);
        else
            $name = $type;
        if(in_array(strtolower($name), array("int", "boolean", "double", "float", "string", "void")))
            return $type;
        else
            return "<a href='?class={$name}'>$type</a></i>";
    }

    /**
     * Equal to the setPersistence of the soapServer
     * @param int SOAP_PERSISTENCE_REQUEST | SOAP_PERSISTENCE_SESSION
     */
    public function setPersistence($mode){
        $this->persistenceMode = $mode;
    }
    /**
     * Creates the code for a class with the given name
     * that extends the given class, but catches exceptions &
     * throws SoapFaults and handles typehints. It can also be used
     * to abstract a database transaction system.
     *
     * @param string name for the new class
     * @param string name of the class that needs to be extended.
     * @deprec
     */
    /*
    private function createExtended($newname,$class){
        $ref=new reflectionClass($class);
        $return = "class $newname extends $class{\n";
        $funcs = $ref->getMethods();


        foreach($funcs as $func){
            if($func->isPrivate() || $func->isFinal()) throw new Exception("Het is niet toegestaan om private / final methods te hebben, maak ze protected ");
            $return .= "\n  public function " . $func->name . "(";
                $args                 = $func->getParameters();
                $params_original     = "";
                $params_new         = "";
                $newclasses         = "";
                foreach($args as $arg){
                    //check if it has a typehint
                    $arg_name = $arg->getName();
                    // Disabled for now, not needed after PHP 5.0.3 
                    if(!$GLOBALS["503"] && $typehint = $arg->getClass()){
                        $arg_name_new= $arg_name."_new";
                        $typehint=$typehint->getName();
                        $newclasses.="    \$$arg_name_new=new $typehint();\n";
                        $newclasses.="    copyClass(\$$arg_name,\$$arg_name_new);\n";
                    }else
                        $arg_name_new=$arg_name;

                    if($params_original!=""){
                        $params_original.=", ";
                        $params_new.=", ";
                    }
                    $params_original.="$".$arg_name."";
                    $params_new.="$".$arg_name_new."";


                }
            $return .= $params_original."){\n";
            $return .= $newclasses;
            $return .= "     //debugObject('original:',\$opdracht);\n";
            $return .= "    try{\n";
            //$return .= "      \$GLOBALS[\"db\"]->BeginTrans();";
            $return .= "      \$r= parent::".$func->getName()."($params_new);\n";
            $return .= "       }catch(SoapFault \$sf){\n";
            //$return .= "      \$GLOBALS[\"db\"]->RollbackTrans();";
            //$return .= "      logging::addMessage(\$sf->getMessage(),\$GLOBALS[\"logid\"]);\n";
            $return .= "         throw \$sf;\n";
            $return .= "        //do nothing\n";
            $return .= "    }catch(Exception \$e){\n";
            //$return .= "      \$GLOBALS[\"db\"]->RollbackTrans();";
            //$return .= "      logging::addMessage(\$e->getMessage(),\$GLOBALS[\"logid\"]);\n";
            $return .= "         throw new SoapFault(\"Server\",\$e->getMessage());\n";
            $return .= "    }\n";
            //$return .= "    \$GLOBALS[\"db\"]->CommitTrans();";
            $return .= "    return \$r;";
            $return .= "  }\n";
        }

        $return.="}\n";
        return $return;
    }
    */
}
?>