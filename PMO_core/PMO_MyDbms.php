<?php
/**
 * This file contains the PMO_MyDbms abstract class which implements
 * a generic Dbms object.
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
require_once(dirname(__FILE__).'/PMO_Dbms.php');

/**
 * This class describe a generic Dbms object. It's a factory 
 * abstraction that permits to instanciate driver like mysql,
 * postgresql, pdo etc ...
 * 
 * This class also implements generic methods used by all drivers
 *
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */ 
abstract class PMO_MyDbms implements PMO_Dbms {

	/**
	 * current PMO_Dbbms instance
	 *
	 * @var object
	 */
	protected static $INSTANCE;

	/**
	 * a PMO_Dbms object or a database resource link
	 *
	 * @var object|resource
	 */
	protected $db;	

	/**
	 * holds the queries log
	 *
	 * @var array
	 */
	protected $log = array();
	
	/**
	 * returns a reference to the PMO_Dbms object
	 * If it does not already exists a new object is created and retained for future use
	 *
	 * if an PDO object is provided as parameter, it will be used and will replace any 
	 * existing instance.
	 *
	 * This uses the singleton design pattern. To get the object, use code such as this:
	 *
	 * <code>
	 * // either returns an existing instance or creates a new one and returns it
	 * $db = PMO_MyDbms::factory();
	 *
	 * // use an already defined PDO instance
	 * $pdo = new PDO("sqlite:/hoem/user/db/mydatabase.db");
	 * $db = PMO_MyBbms::factory($pdo);
	 * 
	 * </code>
	 *
	 * @param object $object			a PDO object
	 * @return PMO_Dbms
	 */
	public static function factory(PDO $object = NULL){
			
		if(isset($object)){
			$driver = $object->getAttribute(PDO::ATTR_DRIVER_NAME);

			switch($driver){
				case 'sqlite':
					require_once("PMO_Dbms_Sqlite.php");
					self::$INSTANCE = new Pmo_Dbms_Sqlite($object);
					break;

				default:
					require_once("PMO_Dbms_Pdo.php");
					self::$INSTANCE = new PMO_Dbms_Pdo($object);
					break;
			}
			return self::$INSTANCE;
		}	
		
		if(!isset(self::$INSTANCE)){	
			$config = PMO_MyConfig::factory();
			$authdb = array("driver"=>$config->get('PMO_MyDbms.DRIVER'), 
							"host"=>$config->get('PMO_MyDbms.HOST'), 
							"base"=>$config->get('PMO_MyDbms.BASE'), 
							"user"=>$config->get('PMO_MyDbms.USER'), 
							"pass"=>$config->get('PMO_MyDbms.PASS'), 
							"dsn"=>$config->get('PMO_MyDbms.DSN'),
							"pdodriver"=>$config->get('PMO_MyDbms.PDODRIVER'));
			
			switch($authdb['driver']){
				case 'mysql':
						require_once("PMO_Dbms_Mysql.php");
						self::$INSTANCE = new Pmo_Dbms_Mysql();
						break;
						
				case 'mysqli':
						require_once("PMO_Dbms_Mysqli.php");
						self::$INSTANCE = new Pmo_Dbms_Mysqli();
						break;						
						
				case 'pgsql':
						require_once("PMO_Dbms_Pgsql.php");
						self::$INSTANCE = new Pmo_Dbms_Pgsql();
						break;
						
				case 'pdo':
						switch($authdb['pdodriver']){
							case 'sqlite':
							require_once("PMO_Dbms_Sqlite.php");
							self::$INSTANCE = new Pmo_Dbms_Sqlite();
							break;							
							
							default:
							require_once("PMO_Dbms_Pdo.php");
							self::$INSTANCE = new Pmo_Dbms_Pdo();
							break;
						}
						break;
						
				default:
						throw new Exception("Error: ".$authdb['driver']." is not a PMO supported driver");
						break;
			}
			self::$INSTANCE->connect($authdb);
		}		
		return self::$INSTANCE;
	}

	/**
	 * kills the current PMO_Dbms object
	 */
	public static function killInstance(){
		self::$INSTANCE = NULL;
	}
	
	/**
	 * Returns the DB link or DB object
	 *
	 * @return PMO_Dbms|resource				the PMO_MyDbms or database link resouRCE
	 */	
	public function getDb(){
		return $this->db;
	}

	/**
	 * Set the DB link or DB object
	 *
	 * @param object|resource $object		the PMO_MyDbms or database link resouRCE
	 */	
	public function setDb($object){
		$this->db = $object;
	}

	/**
	 * sets the query verbose log
	 *
	 * @param string $log		text to log
	 * @return void
	 */
	public function setLog($log){
		if(PMO_MyConfig::factory()->get('PMO_MyDbms.LOG'))
			$this->log[] = date(PMO_MyConfig::factory()->get('PMO_MyDbms.LOG_FORMAT'))." ".$_SERVER['REMOTE_ADDR']." ".$log." ".$_SERVER['REQUEST_URI'];	
	}
	
	/**
	 * retrieves thee query verbose log
	 *
	 * @return array
	 */
	public function getLog(){
		if(isset($this->log))
			return $this->log;
		else
			return FALSE;
	}
	
	/**
	 * converts the attribute types from database to PHP primary types:
	 * string, float, or int
	 *
	 * @param string $type			the database type to convert
	 * @return string					a PHP data type
	 */
	protected function translateType($type){
		if (eregi('int', $type)) {
			return "int";
		}
		if (eregi('float', $type)) {
			return "float";
		}
		if (eregi('blob', $type)) {
			return "string";
		}
		if (eregi('text', $type)) {
			return "string";
		}
		if (eregi('char', $type)) {
			return "string";
		}			
		if (eregi('date', $type)) {
			return "string";
		}
		if (eregi('time', $type)) {
			return "string";
		}
		if (eregi('double', $type)) {
			return "float";
		}
		return "string";
	}

	/**
	 * Load a data row from the database and fills the PMO_Object with it
	 * 
	 * The data come from the first row of the fetchArray() method 
	 * 
	 * @throws Exception
	 * @return void
	 */
	public function load(PMO_Object $object){
		$whereclause = "";
		$objectAttributes = $object->getObjectAttribute();
		$objectTable = $object->getTable();
		
		foreach ($objectAttributes as $column=>$value)
			$whereclause .= " AND {$column}='{$value}'";

		$query = "SELECT * FROM ".$objectTable->getTableName()." WHERE ".substr($whereclause, 5, strlen($whereclause)).";";
		$this->setLog($query);
		$this->query($query);
		$result = $this->fetchArray();
		
		if(!$result)	
			throw new Exception("Error: Object ".$objectTable->getTableName()." can not be found in database");
			
		$tablefields = $objectTable->getColumns(); 
		foreach( $tablefields as $key=>$field){
			$object->set($field, $result[$field]);
		}
	}
	
	/**
	 * deletes the data row corresponding to the PMO_Object from the database table
	 * All primary key must be fill.
	 * 
	 * @param object $object		a PMO_Object corresponding to the target table
	 * @return bool					TRUE if the insert went PL					  
	 * @throws Exception				if something went wrong
	 */
	public function delete(PMO_Object $object){
		$querypk = "";
		$objectattributes = $object->getObjectAttribute();
		$objectTable = $object->getTable();
		$tablepk = $objectTable->getPk();
			
		foreach( $tablepk as $pk){
			if($objectTable->getPerm($pk) != "rw")
				throw new Exception("Error: primary key ".$pk." is not writable");

			if($objectattributes->offsetExists($pk))	
				$querypk .= " AND {$pk}='{$objectattributes->offsetGet($pk)}'";
			else
				throw new Exception("Error: primary key ".$pk." is undefined");
		}
			
			
		$query = "DELETE FROM {$objectTable->getTableName()} WHERE ".substr($querypk, 5, strlen($querypk)).";";
		$this->setLog($query);
		$this->query($query);
		return TRUE;
	}
	
	/**
	 * updates the database table corresponding to the PMO_Object
	 * All primary keys must be fill.
	 *
	 * @param object $object		a PMO_Object corresponding to the target table
	 * @return bool					TRUE if the insert went PL					  
	 * @throws Exception				if something went wrong
	 */	
	public function update(PMO_Object $object){
		$queryfield = "";
		$querypk = "";
		$objectAttributes = $object->getObjectAttribute();
		$objectTable = $object->getTable();
		$tablepk = $objectTable->getPk();			
		
		foreach ($objectAttributes as $columns=>$value){					
			if($objectTable->isPk($columns)){
				if($objectTable->getPerm($columns) != "rw")
					throw new Exception("Error: primary key ".$columns." is not writable");
				
				$querypk .= " AND {$columns}='{$value}'";
			}else{
				if($objectTable->getPerm($columns) == "rw")
					$queryfield .= ",{$columns}='{$value}'";
			}
		}
		
		$query = "UPDATE {$objectTable->getTableName()} SET ".substr($queryfield, 1, strlen($queryfield))." WHERE ".substr($querypk, 5, strlen($querypk)).";";
		$this->setLog($query);
		$this->query($query);
		return TRUE;		
	}
	
	/**
	 * inserts new data into the database table corresponding to the PMO_Object
	 * all primary keys must be fill.
	 * 
	 * @param object $object		a PMO_Object corresponding to the target table
	 * @return bool					TRUE if the insert went PL					  
	 * @throws Exception				if something went wrong
	 */	
	public function insert(PMO_Object $object){
		$queryfield = "";
		$value = "";
		$objectAttributes = $object->getObjectAttribute();
		$objectTable = $object->getTable();
		$tablecolumns = $objectTable->getColumns();
		
		foreach($tablecolumns as $field){
				if($objectAttributes->offsetExists($field)){
					if($objectTable->getPerm($field) == "rw"){
						$queryfield .= ",{$field}";
						$value .= ",\"{$objectAttributes->offsetGet($field)}\"";
					}
				}
		}
		
		$value = substr($value, 1, strlen($value));
		$queryfield = substr($queryfield, 1, strlen($queryfield));
		$query = "INSERT INTO {$objectTable->getTableName()}($queryfield) VALUES($value);";
		$this->setLog($query);
		$this->query($query);
		
		$autoincrement = $objectTable->getAutoincrement();
		if(isset($autoincrement))
			$object->set($autoincrement, $this->getLastId());
	
		return TRUE;
	}	

	
}
?>
