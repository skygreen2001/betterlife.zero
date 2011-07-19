<?php
/**
 * An extended reflection/documentation class for class properties
 *
 * This class extends the reflectionProperty class by also parsing the
 * comment for javadoc compatible @tags and by providing help
 * functions to generate a WSDL file. The class might also
 * be used to generate a phpdoc on the fly
 *
 * @version 0.2
 * @author David Kingma
 * @extends reflectionProperty
 */
class phpdocProperty extends reflectionProperty{
	/** @var string Classname to whom this property belongs */
	public $classname;

	/** @var string Type description of the property */
	public $type = "";

	/** @var boolean Determens if the property is a private property */
	public $isPrivate = false;

	/** @var string */
	public $description;

	/** @var boolean */
	public $optional = false;
	
	/** @var string */
	public $fullDescription = "";

	/** @var string */
	public $smallDescription = "";
	
	/**
	 * constructor. will initiate the commentParser
	 *
	 * @param string Class name
	 * @param string Property name
	 * @return void
	 */
	public function __construct($class,$property){
		if(trim($property) == '') echo "No property!";
		$this->classname = $class;
		parent::__construct($class,$property);
		$this->parseComment();
	}

	/**
	 * Gets the comment for this property
	 *
	 * Since the PHP reflection API doesn't support comments for
	 * class properties, we have to find them our selves :(
	 *
	 * @return string The comment
	 */
	public function getdocComment(){
		$filename = $this->getDeclaringClass()->getFileName();
		//check if the file exists
		if(is_file($filename) && $f = @fopen($filename,"r")){
			//read the file
			$content = fread($f, filesize($filename));
			@fclose($f);

			$varname=$this->name;
			$out=Array();
			preg_match_all("/\/\*\*((?:(?!\*).)*(?:\n(?!\s*\*\/)\s*\*(?:(?!\*\/).)*)*)\*\/[\n|\r|\t|\s]*(var|public|protected)\s[\$]$varname\s?[;|=]/ims",$content, $out);
			if(isset($out[0][0]))
				$comment  = $out[0][0];
			else $comment = null;
			return $comment;
		}else {
			throw new Exception("Cannot find or read file: '$filename' for ".$this->getDeclaringClass()->name."->".$this->name."\n",0);

		}
	}

	private function parseComment(){
		// No getDocComment available for properties in php 5.0.1 :(

		$comment = $this->getDocComment();
		new commentParser($comment, $this);
	}
}

?>