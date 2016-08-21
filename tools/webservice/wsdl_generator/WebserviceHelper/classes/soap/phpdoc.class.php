<?PHP

/**
 * de basic phpdoc class. Zoekt uit welke classes er allemaal aanwezig zijn
 * 
 * @version 0.1
 * @author David Kingma
 */
class phpdoc{
    /** @var phpdocClass[] Array with available classes */
    public $classes=array();
    /** @var phpdocClass The current class */
    public $class="";

    /**
    * Constructor, initiates the getClasses() method
    * 
    * @return void
    */
    function __construct(){
        $this->getClasses();
    }
    
    /** Sets the current class
     * @param string The class name
     * @return void
     */
    public function setClass($class){
        $this->class=new phpdocClass($class);    
    }
    /** 
     * Haalt alle geladen classes op die 'custom zijn
     *
     * @author David Kingma
     * @version 0.9
     * @return phpdocClass[]
     */
    function getClasses(){
        $ar = get_declared_classes();
        foreach($ar as $class){
            $c=new reflectionClass($class);
            if($c->isUserDefined()){//add only when class is user-defined
                $this->classes[$class]=new phpdocClass($class);
            }
        }
        ksort($this->classes);
        return $this->classes;
    }
    /**
     * Generates the documentation page with all classes, methods etc.
     *
     * @param string Template file (optional)
     * @return string
     */
    function getDocumentation($template="templates/docindex.tpl"){
        $xtpl=new XTemplate($template);
        //loop classes
        foreach((array)$this->classes as $class){
            $xtpl->assign("class",(array)$class);
            //loop methods
            $methods=$class->getMethods(false,false);
            foreach((array)$methods as $method){
                $method->fullName=$method->getFullName();
                $xtpl->assign("method",(array)$method);
                $xtpl->parse("main.class.method");
            }
            
            //loop properties
            $properties=$class->getProperties();
            foreach((array) $properties as $property){
                $xtpl->assign("property",(array)$property);
                $xtpl->parse("main.class.property");
            }
            
            //loop constants
            $constants=$class->getConstants();
            foreach((array) $constants as $constant){
                $xtpl->assign("constant",(array)$constant);
                $xtpl->parse("main.class.constant");
            }    
            $xtpl->parse("main.class");
        }
        
        if($_GET["class"]){
            $cls=$this->classes[$_GET["class"]];
            $xtpl->assign("main",$cls->getDocumentation());
        }
        
        $xtpl->parse("main.htmlheader");
        $xtpl->parse("main.htmlfooter");
        $xtpl->parse("main");
        return $xtpl->give("main");
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
}

?>