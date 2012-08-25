<?php
/**
 * This file contains the PMO_MyMapObject class which implements
 * the generic PMO_Object that represents a tuple in database.
 *
 * This file is part of the PhpMyObject project.
 * 
 * For questions, help, comments, discussion, etc., please join our
 * forum at {@link http://www.developpez.net/forums/forumdisplay.php?f=770} 
 * or our mailing list at {@link http://groups.google.com/group/pmo-dev}.
 *
 * PhpMyObject is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package		PhpMyObject
 * @subpackage PMO_Core
 * @author		Nicolas Boiteux <nicolas_boiteux@yahoo.fr>
 * @link			http://pmo.developpez.com/
 * @since		PhpMyObject v0.1x
 * @version		$Revision: $
 * @copyright	Copyright (C) 2007-2008 Nicolas Boiteux 
 * @license		GPLv3 {@link http://www.gnu.org/licenses/gpl}
 * @filesource
 */ 

/** requires the interface */
require_once(dirname(__FILE__).'/PMO_Object.php');


/**
 * This class describe a generic PMO_Object that represents a tuple
 * from the database. It's a model class (the M part of the MVC design pattern)
 * that enables you to create, read, update and delete data (CRUD).
 * 
 * This class can be extended by adding methods or fields
 * and load dynamically via the PMO_Table mecanism
 * 
 * @package 	PhpMyObject
 * @subpackage PMO_Core
 * @see 			class_film_actor.php
 * @tutorial 	PhpMyObject/PhpMyObject.Quickstart.pkg#using.pmo_myObject a simple introduction to PMO_MyObject
 * @tutorial 	PhpMyObject/Manual.pkg#PMO_MyObject the manual chapter on PMO_MyObject
 */ 
class PMO_MyObject implements PMO_Object{
	
	/**
	 * holds the related PMO_Table object
	 * @var object
	 */
	protected $object_table;

	/**
	 * holds the object attributes
	 * @var PMO_MyArray
	 */
	protected $object_attribute ;

	/**
	 * boolean flag indicating if this object is a new one or
	 * has already been loaded through the {@link load()} method
	 * @var bool
	 */
	protected $object_new = TRUE;

	/**
	 * the constructor takes a PMO_Table argument and sets ifself up
	 * up so {@link factory()} will be able to create and return
	 * a PMO_Object 
	 *
	 * @param object $table			a PMO_Table object
	 */
	protected function __construct(PMO_Table $table){			
		$this->object_table = $table;
		$this->object_attribute = new PMO_MyArray();
	}
	
	/**
	 * this internal factory will instanciate either PMO_Object based
	 * on an extended class in your class_loader directory or a generic
	 * PMO_MyObject object.
	 * 
	 * @param object $table		a PMO_Table object
	 * @return PMO_Object
	 */
	public static function internalfactory(PMO_Table $table){
		$classname = $table->getClassname(); 
		if ($classname){
			require_once(PMO_MyConfig::factory()->get('PMO_MyObject.CLASSPATH').PMO_MyConfig::factory()->get('PMO_MyObject.CLASS_FILENAME_PREFIX').$classname.'.php');
			$object = new $classname($table);
		}else{
			$object = new PMO_MyObject($table);	
		}
		return $object;
	}
	
	/**
	 * returns an object conforming to the database table schema
	 *
	 * This factory calls an internal factory that is used to load your own
	 * class if it exists in the class_loader directory and is referenced in
	 * the property $table_classname in the corresponding
	 * PMO_Table/PMO_Table_Classname.php file. 
	 *
	 * @param string $tablename	the database table name to use
	 *										for building the PMO_Object object
	 * @return PMO_Object
	 */
	public static function factory($tablename){
		$table = PMO_MyTable::factory($tablename);
		$object = PMO_MyObject::internalfactory($table);
		return $object;
	}	
		
	/**
	 * Returns a reference to the PMO_Table object on which this object is based
	 *
	 * PMO_Table describe the structure of the table that was used to build
	 * the object data structure (column names, primary keys, ..)
	 * 
	 * @return PMO_Table
	 * @throws Exception				if the PMO_Table is undefined
	 */
	public function getTable(){
			if(isset($this->object_table))
				return $this->object_table;
		
		throw new Exception("Error: Object is undefined");
	}

	/**
	 * return the data structure of the PMO_Object
	 * array is attribute=>value
	 * 
	 * @return PMO_MyArray
	 * @throws Exception
	 */
	public function getObjectAttribute(){
		if($this->object_attribute->count() > 0)
			return $this->object_attribute;	
		throw new Exception("Error: No data found in object");
	}
	
	/**
	 * return an array wich contains
	 * all the names of attributes of PMO_Object
	 * 
	 * @return array
	 * @throws Exception
	 */	
	public function getListAttribute(){
		$array = new PMO_MyArray();
		$attributes = $this->getObjectAttribute();
		foreach($attributes as $attributename=>$attributevalue)
			$array->append($attributename);
				
		return $array;
	}

	/**
	* retrieve the value of an attribute  
	* this function also cleans the escape chars
	* with a simple stripslashes
	* 
	* @param sting $attribute
	* @throws Exception
	*/	
	public function get($attribute){
		if($this->object_attribute->offsetExists($attribute))
			return stripslashes($this->object_attribute->offsetGet($attribute));
				
		throw new Exception("Error: Attribute ".$attribute." doesn't exist");		
	}

	public function fetch(){
		return $this->object_attribute->fetch();
	}
	
	/**
	* Set the value of an attribute of the data structure
	* this function already escape strange char with 
	* a  simple addslashes
	* 
	* data structure is an array 
	* attribute => value
	*
	* @param string $alias		  column alias
	* @param mixed $value		  value to set the attribute to
	* @throws Exception
	* @return bool					  always TRUE
	*/	
	public function set($attribute, $value){
		if($this->object_table->issetAttribute($attribute))
			$this->object_attribute->offsetSet($attribute, $value);
		else
			throw new Exception("Error: Attribute ".$attribute." value is undefined");
		
		return TRUE;
	}
	
	/**
	 * export the data structure of an object in a xml format stream
	 *
	 * <code>
	 * <attributes>
	 *		<attributename>attributevalue</attributename>
	 * </attributes>
	 * </code>
	 */ 
	public function toXml($encoding){
		$out = "<?xml version=\"1.0\" encoding=\"$encoding\"?>\r";
		$out .= "<attributes>\r";

		foreach($this->object_attribute as $key=>$value)
			$out .= "<{$key}>$value</{$key}>\r";

		$out .= "</attributes>\r";
		return $out;
	}

	/**
	 * alias of set function
	 *
	 * @param string $attributename
	 * @param mixed $attributevalue
	 * @see set()
	 */
	public function __set($attributename, $attributevalue) {
		$this->set($attributename, $attributevalue);
	}
	
	/**
	 * alias of get function
	 *
	 * @param string $attributename
	 * @return mixed
	 * @see get()
	 */
	public function __get($attributename) {
		return $this->get($attributename);
	}
			
	/**
	 * define the object as new, not already present in the database
	 *
	 * This flag is used to define when we do an insert or an update
	 * 
	 * @param bool $flag
	 * @return void
	 */
	public function setNew($flag){
		if(!is_bool($flag))
			throw new Exception("Error: New flag should be a boolean");
			
		$this->object_new = $flag;	
	}
	
	/**
	 * return the flag used to define if the objet is
	 * already present in database table
	 *
	 * @return bool
	 */
	public function getNew(){
		return $this->object_new;
	}
	
	/**
	 * Loads a data row from the database table using its primary key
	 * and fills the object attributes
	 *
	 * Primary key needs to be set beforehand
	 * 
	 * @throws Exception
	 * @return void
	 */
	public function load(){
		if(PMO_MyConfig::factory()->get('PMO_MyMemCache.ACTIVE')){
			PMO_MyMemCache::factory()->get($this);
		}
		$dbms = PMO_MyDbms::factory();
		$dbms->load($this);
		$this->setNew(FALSE);	
	}
	
	/**
	 * Deletes the corespondgng data row from the database table
	 *
	 * primary key needs to be set beforehand to delete the right tuple
	 * 
	 * @throws Exception
	 * @return void
	 */
	public function delete(){
		if(PMO_MyConfig::factory()->get('PMO_MyMemCache.ACTIVE')){
				PMO_MyMemCache::factory()->delete($this);
		}	
		
		$dbms = PMO_MyDbms::factory();
		$dbms->delete($this);
	}
	
	/**
	 * Save this object into the corresponding database table
	 *
	 * If this is a new object, it will be inserted. Otherwise
	 * an update will be performed. 
	 * 
	 * @throws Exception
	 */
	public function save(){
		if($this->getNew()){
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	/**
	 * updates the corresponding data row into the database table
	 * 
	 * @throws Exception
	 */
	public function update(){
		$dbms = PMO_MyDbms::factory();
		$dbms->update($this);
		if(PMO_MyConfig::factory()->get('PMO_MyMemCache.ACTIVE')){
				PMO_MyMemCache::factory()->set($this);
		}		
		$this->setNew(FALSE);
	}

	/**
	 * inserts a new data row into the database table
	 *
	 * Fields must be filled beforehand.
	 * 
	 * @throws Exception
	 */	
	public function insert(){
		$dbms = PMO_MyDbms::factory();
		$dbms->insert($this);
		if(PMO_MyConfig::factory()->get('PMO_MyMemCache.ACTIVE')){
				PMO_MyMemCache::factory()->set($this);
		}			
		$this->setNew(FALSE);
	}
	
}
?>
