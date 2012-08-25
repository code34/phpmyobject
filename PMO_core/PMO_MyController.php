<?php
/**
 * This file contains the PMO_MyController class which you can use
 * to send queries to your database.
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
require_once(dirname(__FILE__).'/PMO_Controller.php');

/** requires all the needed classes */
require_once(dirname(__FILE__).'/PMO_MyObject.php');
require_once(dirname(__FILE__).'/PMO_MyMap.php');
require_once(dirname(__FILE__).'/PMO_MyParser.php');
require_once(dirname(__FILE__).'/PMO_MyTable.php');
require_once(dirname(__FILE__).'/PMO_MyDbms.php');
require_once(dirname(__FILE__).'/PMO_MyConfig.php');
require_once(dirname(__FILE__).'/PMO_MyRequest.php');
require_once(dirname(__FILE__).'/PMO_MyArray.php');
require_once(dirname(__FILE__).'/PMO_MyMemCache.php');



/**
 * PMO_MyController enable you to send queries to you database
 * and returns the data.
 *
 * This class also enables you to execute raw sql query and transform
 * each tuples result into distinct {@link PMO_Object}.
 * 
 * Iterations schema is<br />
 * For each tuple > For each Table > Build an object > For each column > Set the value of object 
 * 
 * Before instanciation, a new object controller checks if it already exists
 * in a hash map. If it does exist, the controller will retrieve its reference. 
 *
 * Objects are always referenced in this hash map after their instanciation.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */ 
class PMO_MyController implements PMO_Controller {

	/**
	 * the Dbms instance
	 * @var object
	 * @access protected
	 */
	protected $dbms_instance;

	/**
	 * the map of PMO_MyObject objects this controller holds
	 *
	 * @var object				a {@link PMO_MyMap} object that holds the retrieved objects
	 * @access protected
	 */
	protected $map_objects;

	/**
	 * a map of PMO_MyTable objects
	 *
	 * @var object			a {@link PMO_MyMapTable} objects
	 * @access protected
	 */
	protected $map_tables;

	/**
	 * ArrayIterator SPL Object.
	 *
	 * @var object			a {@link ArrayIterator} object
	 * @access protected
	 */
	protected $array_iterator;

	/**
	 * the SQL parser object
	 *
	 * @var object			a {@link PMO_MyParser} object
	 * @access protected
	 */
	protected $parsersql;

	/**
	 * the constructor
	 * the constrortor instanciates the Ddms engine and initialize itself
	 *
	 * @param object $object		a PDO object to use fr the database requests.
	 *										If NULL, the Ddms driver specified by the
	 *										configuration will be used
	 * @return object					returns the PMO_MyController object
	 */
	public function __construct(pdo $object = NULL) {
		$this->dbms_instance = PMO_MyDbms::factory($object);
		$this->init();		
	}

	/**
	 * Short description
	 * @todo
	 */
	private function populateCollector(){
		while($sqlfunction = $this->parsersql->fetchFunction()){
			if(!isset($collector)){
				$collector = new PMO_MyTable();
				$collector->setTableName(PMO_MyConfig::factory()->get('PMO_MyController.OBJECT_COLLECTOR_NAME'));
				$this->map_tables->append($collector);
			}
						
			$tmparray = array("Field"=>$sqlfunction, 
				"Type" => "", 
				"Null" => "", 
				"Key"=> "PRI", 
				"Default"=> "", 
				"Extra"=> "",
				"Perm"=>"rw");

			$collector->set($tmparray);				
		}			
	}
	
	/**
	 * Retrieve the name of tables from an sql query,
	 * instanciate the tables objects PMO_Table corresponding and
	 * put them in a PMO_MapTable
	 * 
	 * @param string $query			the SQL query to get the table names from
	 * @return void
	 */
	private function populateMapTables($query){

		$this->parsersql->parseRequest($query);
		$numfields = $this->parsersql->countFields();

		while($table = $this->parsersql->fetchTable()){
			$objecttable = PMO_MyTable::factory($table);
			$this->map_tables->append($objecttable);
			
			if($numfields > 0){
				$objecttable->setPermForAll("r");
				while($field = $this->parsersql->fetchField()){
						if($objecttable->issetAttribute($field))
							$objecttable->setPerm($field, "rw");
				}
			}
		}						
	}

	/**
	 * Transform results of an sql query to objects
	 * and put them in a map of objects
	 * 
	 * @param string $query		the SQL query 
	 * @return PMO_Map
	 * @throws Exception
	 */
	private function populateMapObjects($query){	
		$numtables = $this->map_tables->count();

		/**
		* SQL request with more than 1 table
		*/
		if($numtables > 1){
			$this->array_iterator = new ArrayIterator();
			$this->dbms_instance->query($query);
			/**
			* Foreach row of raw results, we build a row of objects 
			*/
			while($db_result = $this->dbms_instance->fetchArray()) {
				$row = array();
				while($table = $this->map_tables->fetch()){
					$tablename = $table->getTableName();
					$fingerprint = "";
					$arrayofpk = $table->getPk();
					
					/**
					 *  Build unique fingerprint for object
					 *  from primary keys 
					 */
					foreach($arrayofpk as $pk)
						$fingerprint = $fingerprint.$pk.$db_result[$pk];

					/**
					 * We retrieve the object reference in arrayIterator
					 * if object is not present, we create it
					 * add it in the arrayIterator, and reference it
					 * into a row and put the row into the PMO_Map
					 */	
					if($this->array_iterator->offsetExists($tablename.$fingerprint)){
						$currentobject = $this->array_iterator->offsetGet($tablename.$fingerprint);
					}else{
						$currentobject = PMO_MyObject::internalfactory($table);
						$tablefields = $table->getColumns(); 
						
						foreach( $tablefields as $key=>$field){		
							if($table->getPerm($field) == "rw")
								$currentobject->set($field, $db_result[$field]);
						}
						$this->array_iterator->offsetSet($tablename.$fingerprint, $currentobject);
					}
					
					$row[$tablename] =  $currentobject;
				}
				$this->map_objects->add($row);	
			}
		}else{
			$table = $this->map_tables->fetch();
			$tablename = $table->getTableName();
			$tablefields = $table->getColumns();
			
			$this->dbms_instance->query($query);
			while($db_result = $this->dbms_instance->fetchArray()) {
				$line = array();
				$currentobject = PMO_MyObject::internalfactory($table); 
				foreach( $tablefields as $key=>$field){
					if($table->getPerm($field) == "rw")
						$currentobject->set($field, $db_result[$field]);
				}
				$line[$tablename] = $currentobject;
				$this->map_objects->add($line);
			}	
		}
		
		if ($this->map_objects->count() > 0)
			return $this->map_objects;
		else
			throw new Exception("Error: PMO_Map is empty");
	}

	/**
	 * Return map of objects allready
	 * loaded by query()
	 * 
	 * @return PMO_Map
	 * @throws Exception
	 */
	public function getMapObjects(){
		if(isset($this->map_objects))
			return $this->map_objects;
			
		throw new Exception("Error: Map is empty");
	}

	/**
	 * Execute a PMO_MyRequest query
	 *
	 * @param object request		a {@link PMO_Request} object
	 *										that has been used to build the query
	 * @return PMO_Map				a {@link PMO_Map} object
	 */
	public function objectquery(PMO_Request $request){
		return $this->query($request->toString());
	}
	
	/**
	 * Execute an sql query and
	 * return the corresponding PMO_Map fill with PMO_Object
	 * 
	 * @param string $query			the SQL query to execute
	 * @return PMO_Map
	 * @throws Exception
	 */
	public function query($query){
		$this->init();			
		$this->populateMapTables($query);
		$this->populateCollector();
		$this->populateMapObjects($query);
		return $this->getMapObjects();
	}
	
	/**
	 * Execute an sql query without traitment
	 *
	 * @param string $query		the SQL query
	 * @return object|resource		the method will return either a PDO object
	 *										or a DBMS resource link, depending on the
	 *										selected driver
	 */
	public function rawquery($query){
		$this->dbms_instance->query($query);
		return $this->dbms_instance;
	}
	
	/**
	 * Should not be use
	 * return all tuples of one table
	 * equivalent : SELECT	* table;
	 * 
	 * @param object $table			a {@link PMO_Table} object
	 * @return PMO_Map
	 * @throws Exception
	 */
	public function queryAll(PMO_Table $table){
		$this->init();
		$tablename = $table->getTableName();
		
		$this->dbms_instance->query("SELECT * FROM $tablename;");
		while($db_result = $this->dbms_instance->fetchArray()) {
			$currentobject = PMO_MyObject::internalfactory($table);
			$currentobject->initObjectMap($this->map_objects);
			$tablefields = $table->getColumns(); 
			foreach( $tablefields as $key=>$field)
				$currentobject->set($field, $db_result[$field]);
			
			$this->map_objects->addLine($currentobject);
		}
		return $this->map_objects;
	}

	/**
	 * initializes the controller
	 */
	public function init(){
		$this->map_objects = new PMO_MyMap();
		$this->map_tables = new PMO_MyArray();
		$this->parsersql = new PMO_MyParser();		
	}

}
?>
