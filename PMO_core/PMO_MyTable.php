<?php
/**
 * This file contains the PMO_MyTable class which is used to hold the
 * data structure of your database tables
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
 *
 */ 

/** requires the interface */
require_once(dirname(__FILE__).'/PMO_Table.php');

/**
 * PMO_MyTable describes the data structure of your database tables.
 *
 * it is used to build the data structure of PMO_Object,
 * retrieve the fields that are primary keys, use alias instead
 * of the real name of columns.
 * 
 * @todo			provide some examples and link to tutorials
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */
class PMO_MyTable implements PMO_Table{

	/**
	* this PMO_Table object name
	*
	* @var string
	* @access protected
	*/
	protected $table_name;

	/**
	* holds the table attributes represented by this object
	* and their values
	*
	* This array holds the table columns name and their properties and values.
	*
	* Columns attributes
	*
	* - Type				  the columns type: string, int, float, etc.
	* - Null				  can the colun have null value: YES/NO
	* - Key				  if value is PRI, the column is a primary key or part of it
	* - Default			  value to default the column value with if none is provided
	* - Extra			  is this column an auto_increment column?  NULL/auto_increment
	* - Perm				  the column permission: rw/r
	*
	* @var array
	* @access protected
	*/
	protected $table_attribute = array();

	/**
	 * an array of PMO_Table objects
	 *
	 * @var array
	 * @static
	 * @access protected
	 */
	protected static $MAP = array();
	
	/**
	 * return a PMO_MyTable object from th PMO_MyTable directory or
	 * instanciates it if it does not exist yet.
	 *
	 * factory() first checks to see if the wanted PMO_Table has already
	 * been registered. If so it returns it immediately.
	 *
	 * Otherwise factory() will try to load the corresponding class from the
	 * PMO_MyTable/ directory. 
	 *
	 * If it does not exist, factory() will finally
	 * instanciate a generic PMO_Mytable object, get the table structure
	 * and persist it on disk and register it for the next instanciation.
	 * 
	 * @param string $tablename	the table name
	 * @return PMO_Table
	 */
	public static function factory($tablename){
		if(isset(self::$MAP[$tablename]))
			return self::$MAP[$tablename];
		
		$classname = PMO_MyConfig::factory()->get('PMO_MyTable.CLASS_FILENAME_PREFIX').$tablename; 
		$filename = PMO_MyConfig::factory()->get('PMO_MyTable.CLASSPATH').$classname.".php";

		if(file_exists($filename))
			require_once($filename);
			
		if(class_exists($classname)){
			$table = new $classname;
			self::$MAP[$tablename] = $table;
			return $table;
		}else{
			$table = new PMO_MyTable();
			$table->populate($tablename);
			if(PMO_MyConfig::factory()->get('PMO_MyTable.CLASS_WRITE_ON_DISK')){
				$table->persist();
				return(PMO_MyTable::factory($tablename));
			}else{
				self::$MAP[$tablename] = $table;
				return $table;
			}
		}
	}
	
	/**
	 * populates table with the Dbms information
	 *
	 * @param string $tablename	table name
	 */	
	private function populate($tablename){		
			$this->setTableName($tablename);
			$tmparray = PMO_MyDbms::factory()->getTableDesc($tablename);
			
			foreach($tmparray as $attributevalue)
				$this->set($attributevalue);	
	}
	
	/**
	 * retrieve the tablename of the object
	 * 
	 * @return string
	 * @throws Exception			if the table name is not set
	 */
	public function getTableName(){
		if(isset($this->table_name))
			return $this->table_name;
		
		throw new Exception("Error: no tablename defined");
	}

	/**
	 * retrieves the autoincrement field if it exists
	 * and returns it
	* 
	* @return string|NULL 
	*/
	public function getAutoincrement(){
		foreach($this->table_attribute as $attributename=>$attributevalue){
			if(! strcmp ($attributevalue['Extra'], 'auto_increment')){
				return $attributename;
			}
		}
		return NULL;	
	}			
	
	/**
	 * retrieves all the primary key of the object
	 * and returns them in an array
	 * 
	 * Permission must be "rw" for the key to be returned.
	 *
	 * @return array
	 * @throws Exception			if the primary key is not defined
	 */	
	public function getPk(){
		foreach($this->table_attribute as $attributename=>$attributevalue){
			if($attributevalue['Key'] == 'PRI')
				if($attributevalue['Perm'] == 'rw')
					$array[] = $attributename;
		}

		if(isset($array))
			return $array;
		
		throw new Exception("Error: primary key of table ".$this->getTableName()." is undefined");		
	}	

	/**
	 * sets the primary keys of an object with an array
	 * 
	 * @param string $attributename	a column name that is the table
	 *											primary key or part of it
	 * return void
	 */	
	public function setPk($attributename){
		$this->table_attribute[$attributename]['Key'] = 'PRI';
	}
	
	/**
	 * checks if the column is a primary key or not
	 * 
	 * @param string $attributenane	returns true if the attribute name
	 *											is part of the table primary key
	 * @return boolean
	 */
	public function isPk($attributename){
		if($this->table_attribute[$attributename]['Key'] == 'PRI')
			return TRUE;
			
		return FALSE;
	}	
	
	/**
	 * sets the table name of the object
	 * 
	 * @param string $tablename
	 * @return void
	 */
	public function setTableName($tablename){
		$this->table_name = $tablename;
	}
	
	/**
	 * retrieves an array that contains all the
	 * table columns names, e.g. [0]=>nameofcolumn
	 * 
	 * @return array
	 * @throws Exception			if there is no attribute defined
	 */
	public function getColumns(){
		if(count($this->table_attribute) < 1)
			throw new Exception("Error: table attributes are undefined");
		
		foreach($this->table_attribute as $name=>$value)
			$array[] = $name;
			
			return $array;
	}

	/**
	 * returns the permissions of an attribute: r=read, w=write
	 * 
	 * @param string $attributename		name of the attribute for which
	 *												we want the permissions
	 * @return string
	 * @throws Exception						if the attribute is not defined
	 */
	public function getPerm($attributename){
		if(isset($this->table_attribute[$attributename]['Perm']))
			return $this->table_attribute[$attributename]['Perm'];
			
		throw new Exception("Error: Perm of ".$attributename." is undefined.");
	}
	
	/**
	 * returns the type of an attribute: string, int, float
	 * 
	 * @param string $attributename		name of the attribute for which
	 *												we want the type
	 * @return string
	 */
	public function getType($attributename){
		return $this->table_attribute[$attributename]['Type'];
	}
	
	/**
	 * sets permission for an attribute (r=read, w=write)
	 *
	 * @param string $attributename		attribute on which to set the permission
	 * @param string $value					the permission to set, e.g. "r" or "rw"
	 * @throws Exception						if the attribute is not set
	 */
	public function setPerm($attributename, $value){
		if(isset($this->table_attribute[$attributename]))
			$this->table_attribute[$attributename]['Perm'] = $value;
		else
			throw new Exception("Error: attribute ".$attributename." doesn't exist");
	}

	/**
	 * sets permissions for all attributes (r=read, w=write)
	 *
	 * @param string $value			permission to set, e.g. "r" or "rw"
	 */
	public function setPermForAll($value){
		foreach($this->table_attribute as $attributename=>$array)
			$this->table_attribute[$attributename]['Perm'] = $value;
	}
		
	/**
	* returns an attribute in the datastructure of object
	* array is attribute => value
	* 
	* @param string $attributename  attribute name for which we want
	*										  the properties
	* @return array					  an array of attribute => value 
	* @throws Exception				  if $attributename does not exist
	*/	
	public function get($attribute){
		if(isset($this->table_attribute[$attribute]))
			return $this->table_attribute[$attribute];
		
		throw new Exception("Error: Attribute ".$attribute." of Table ".$this->getTableName()." is undefined");
	}
	
	/**
	 * checks if an attribute exists or not for the provided
	 * attribute
	 * 
	 * @param string $attributename
	 * @return boolean
	 */
	public function issetAttribute($attributename){		
		if(isset($this->table_attribute[$attributename]))
			return TRUE;
		
		return FALSE;
	}	
	
	/**
	 * return the class name used to instanciate a PMO_Object
	 * corresponding to this PMO_Table
	 * 
	 * @return string|false		the class name of false if not set
	 */
	public function getClassname(){
		if(isset($this->table_classname))
			return $this->table_classname;
			
		return FALSE;
	}

	/**
	 * sets the attribute "Field" with a value
	 * 
	 * @param array $attributevalue
	 * @return void
	 */	
	public function set(array $attributevalue){
		$this->table_attribute[$attributevalue['Field']] = $attributevalue;
	}

	/**
	 * sets the foreign key for an attribute
	 *
	 * @param string $attributename
	 * @param array $value
	 * @throws Exception			if the attribute does not exist
	 */
	public function setFk($attributename, array $value){
		if(isset($this->table_attribute[$attributename]))
			$this->table_attribute[$attributename]['Fk'] = $value;
		else
			throw new Exception("Error: attribute ".$attributename." doesn't exist");
	}
	
	/**
	 * returns the table foreign keys
	 * 
	 * @todo this method returns the attribute value as a whole,
	 *			should only return the foreign keys
	 * @return array|false		array of attributes value or false if none exist
	 * @throws Exception
	 */
	public function getFk(){
		$array = new PMO_MyArray();
		foreach($this->table_attribute as $attributename=>$attributevalue){
				if(isset($attributevalue['Fk']))
					$array->offsetset($attributename, $attributevalue);
		}
		
		return $array;	
	}
	
	/**
	* creates a PMO_MyTable_xxx class at the first execution time, and
	* flushes it to the disk
	* this class extends the class PMO_MyTable, 
	*
	* describe the data structure of tables, describe the primary keys,
	* and the class name to used to instanciate 
	* the PMO_Object
	* 
	* @return bool
	*/
	public function persist(){

		$cache = "<?php \n";
		$cache .= "class PMO_MyTable_".$this->table_name." extends PMO_MyTable{\n\n";
		
		$cache .= "\tprotected \$table_name = '".$this->table_name."';\n";		
		$cache .= "\tprotected \$table_attribute = Array(\n\t\t\t\t";
			
		$tmp = "";
		foreach($this->table_attribute as $attribute=>$value){
			$tmp .= ',\''.$attribute.'\'=> Array('."\n\t\t\t\t";
			$tmp2 = "";
			foreach($value as $attribute2=>$value2){
				$tmp2 .= ',\''.$attribute2.'\'=> \''.$value2.'\''."\n\t\t\t\t";			
			}
			$tmp .= substr($tmp2, 1, strlen($tmp2));
			$tmp .= ")\n\t\t\t\t";
		}
		
		$cache .= substr($tmp, 1, strlen($tmp));
		$cache .=");\n\n";
				
		$cache .= "\tprotected \$table_classname = NULL;\n\n";
		
		$cache .="}\n";
		$cache .= "?>";
		
		$filename = PMO_MyConfig::factory()->get('PMO_MyTable.CLASSPATH').PMO_MyConfig::factory()->get('PMO_MyTable.CLASS_FILENAME_PREFIX').$this->table_name.'.php';
		$handle = fopen($filename, "w");
		if(!fwrite($handle, $cache, strlen($cache)))
			throw new Exception("Error: PMO_MyTable ".$this->getTableName()." can not be write in ".PMO_MyConfig::factory()->get('PMO_MyTable.CLASSPATH')." directory");
		fclose($handle);
		
		return TRUE;
	}
}

?>
