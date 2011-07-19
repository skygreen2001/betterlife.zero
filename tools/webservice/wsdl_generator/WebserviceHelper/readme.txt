Webservice helper 1.3 - Readme
For more information see www.jool.nl/webservicehelper/
Author: David Kingma david<AT>jool<DOT>nl

1. What does the webservice helper?
2. Manual
3. FAQ
4. Use of external tools
5. Example
6. TODO


-- 1. What does the webservice helper?

The webservice helper does what the name says: helping you making a php class 
available as webservice. It generates the documentation, the webservice
description file (WSDL) and handles errorhandling. It consists of three parts: 
* extension of the PHP reflection classes to also parse the comments for
  information on parameter info and return values. The documentation and WSDL
  are generated from these classes.(see also documentation.php as an example)
* extension to the PHP SOAP implementation. It catches all normal exceptions
  and allows typehints in the webservice methods. (ie. saveContact(contact $contact))

-- 2. Manual
So how do you create your own webservice. As an example we create a webservice to
add and show contacts. First you create a class called contactManager in the 
/classes/data_objectswith the public functions getContacts(), saveContact(contact 
$contact) and newContact(). To let the Webservice helper know what the parameters 
and return values of each method are we put a comment in front of each method 
specifying the parameters and return types. For example:

/**
 * This method saves the given contact
 * @return contact[] Array with all the contacts
 */
 public function getContacts(){}

/**
 * This method saves the given contact
 * @param contact The contact to save
 * @return void
 */
 public function saveContact(contact $contact){}

/**
 * This method saves the given contact
 * @return contact A new contact template
 */
 public function newContact(){}

We used the contact type as a return value for newContact() and getContacts() so we 
need to define what a contact looks like. For that we create a class called contact:

class contact{
	/** @var string */
	public $name;
	/** @var string */
	public $address;
}

Since string is (just as boolean and int) a known datatype we don't need to specify it
any further.

The last thing we need to do to finish our webservice is to tell the webservice that de 
contactManager class is an allowed webservice and that contact is an allowed data-
structure (for documentation purpose only). In the config.php you add "contactmanager" to 
the WSClasses array and add "contact" to the WSStructures array. This is also the place 
where you might want to change the Webserviceuser / password and your schema URI.

You can now view the service documentation at /service.php and the wsdl at 
/service.php?class=contactmanager&wsdl

More documentation on the client will follow


-- 3. FAQ

* My function doesn't showup in the documentation nor the WSDL file?
Please check if it's a public function and it doesn't start with '__'

* The client doesn't work with webservices from another domain, why is that?
It's a java / browser security feature that you cannot connect to other domeins than your own.

* It doesn't work!
    - Do you see any warnings in the generated documentation? Fix them
    - Check case sensitivity of class names
    - Did you check the javaconsole to see if anything goes wrong?
    - Tried cleaning the wsdl cache in the WSDL cache directory?
    - Did you check the WSDL url in the client?


-- 4. Use of external tools
For this project we used some external software (see the licenses directory):

* xtemplate - The rather old template engine used for the documentation html generation,
    available at http://sourceforge.net/projects/xtpl under lgpl

-- 5. example
See /service.php?class=contactManager



-- 6. TODO
* =BUG= make it work with static / final functions as well
* Extend te documentation and example 
* Document the client applet
* Making a better cache mechanism for the WSDL files and documentation
* Extending the client applet to make it more compatible with existing xmlhttp objects
* Make debugging of errors easier
* XML signature / XML encryption
* Digest authentication
