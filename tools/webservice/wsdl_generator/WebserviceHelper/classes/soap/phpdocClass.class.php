<?php
/**
 * An extended reflection/documentation class for classes
 *
 * This class extends the reflectionClass class by also parsing the
 * comment for javadoc compatible @tags and by providing help
 * functions to generate a WSDL file. THe class might also
 * be used to generate a phpdoc on the fly
 *
 * @version 0.1
 * @author David Kingma
 * @extends reflectionClass
 */
class phpdocClass extends reflectionClass {
	/** @var string class name */
	public $classname = null;

	/** @var string */
	public $fullDescription = "";

	/** @var string */
	public $smallDescription = "";

	/** @var phpdocMethod[] */
	public $methods = Array();

	/** @var phpdocProperty[] */
	public $properties = Array();

	/** @var string */
	public $extends;


	/**
	 * Constructor
	 *
	 * sets the class name and calls the constructor of the reflectionClass
	 *
	 * @param string The class name
	 * @return void
	 */
	public function __construct($classname){
		$this->classname = $classname;
		parent::__construct($classname);
		
		$this->parseComment();
	}

	/**
	 *Levert een array met alle methoden van deze class op
	 *
	 * @param boolean If the method should also return protected functions
	 * @param boolean If the method should also return private functions
	 * @return phpdocMethod[]
	 */
	public function getMethods($alsoProtected = true, $alsoPrivate = true){
		$ar=parent::getMethods();
		foreach($ar as $method){
			$m = new phpdocMethod($this->classname, $method->name);
			if((!$m->isPrivate() || $alsoPrivate) && (!$m->isProtected() || $alsoProtected) && ($m->getDeclaringClass()->name==$this->name))
				$this->methods[$method->name] = $m;
		}
		ksort($this->methods);
		return $this->methods;
	}

	/**
	 * Levert een array met variabelen van deze class op
	 *
	 * @param boolean If the method should also return protected properties
	 * @param boolean If the method should also return private properties
	 * @return phpdocProperty[]
	 */
	public function getProperties($alsoProtected=true,$alsoPrivate=true){
		$ar=parent::getProperties();
		$this->properties=Array();
		foreach($ar as $property){
			if((!$property->isPrivate() || $alsoPrivate) && (!$property->isProtected() || $alsoProtected)){
				try{
					$p = new phpdocProperty($this->classname, $property->getName());
					$this->properties[$property->name]=$p;
				}catch(ReflectionException $exception){
					echo "Fout bij property: ".$property->name."<br>\n";
				}
			}
		}
		ksort($this->properties);
		return $this->properties;
	}

	/**
	 * Gets all the usefull information from the comments
	 * @return void
	 */
	private function parseComment(){
		$comment=$this->getDocComment();
		new commentParser($comment,$this);
	}

}
?>