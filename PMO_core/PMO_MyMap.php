<?php
/**
 * This file contains the PMO_MyMap class which implements
 * datastructure to stock, retrieve and filter a PMO_Object.
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
require_once(dirname(__FILE__).'/PMO_Map.php');

/**
 * This class is a datastructure to stock, retrieve and filter 
 * the reference of PMO_Object.
 *
 * Several methods are build to 
 * search for an object with criterians. This map is specific
 * to PMO_Object as it use the interface PMO_Object
 *
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */ 
class PMO_MyMap implements PMO_Map{

	/**
	* holds an array of PMO_Object in the from of
	* tablename => PMO_Object
	*
	* @var ArrayIterator from SPL
	*/
	protected $map;
	
	public function __construct(){
		$this->map = new PMO_MyArray();
	}

	/**
	* Adds an array of PMO_Object to the current map in the form of
	* tablename=>PMO_Object
	* 
	* @param array $row		  an array of PMO_Object
	* @return void
	*/
	public function add(array $row){
		$this->map->append($row);	
	}

	/**
	* returns a new map from the current map using a PMO_Object as filter
	*
	* this new map only contains rows that are relative to the passed in object
	* 
	* @param object $object		  a PMO_Object
	* @return PMO_Map
	* @throws Exception			  if there is no PMO_Object for the same table name
	*/
	public function getMapByObject(PMO_Object $object){
		$oldmap = $this->get();
		$newmap = new PMO_MyMap;
		$tablename = $object->getTable()->getTableName();
		foreach($oldmap as $line)
			if($line[$tablename] === $object)
				$newmap->add($line);
				
		if($newmap->count()>0)
			return $newmap;
		else
			throw new Exception("Error: Map doesn't contain ".$tablename);
	}
	
	/**
	* returns a new map from the current map using a PMO_Object
	* primary keys as filter
	*
	* this new map will only contain rows that match the filter
	* 
	* Other values can be NULL as they are not used.
	* 
	* @param object		  a PMO_Object to use as filter
	* @return PMO_Map
	* @throws Exception	  if no object is found
	*/
	public function getMapByObjectByValue(PMO_Object $object){
		$oldmap = $this->get();		
		$newmap = new PMO_MyMap;
		$table = $object->getTable();
		$tablename = $table->getTableName();
		$arrayofpk = $table->getPk();
		
		foreach($oldmap as $line){
			$flag = TRUE;
			foreach($arrayofpk as $pk){
				if($line[$tablename]->$pk != $object->$pk){ 
					$flag = FALSE;
					break;
				}
			}

			if($flag)
				$newmap->add($line);
		}
		
		if($newmap->count()>0)
			return $newmap;
		else
			throw new Exception("Error: Map doesn't contain ".$tablename);
	}
	
	/**
	* Alias of {@see getMapByObject()}
	*
	* @param object $object		  a PMO_Object to use as filter
	* @return PMO_Map
	*/
	public function getMapLinked(PMO_Object $object){
		return $this->getMapByObject($object);
	}
	
	/**
	* build a new map that only contains objects of type tablename
	* relative to our object.
	* 
	* @param object $object		  the PMO_Object to search for
	* @param string $tablename   the table nae for which we want a map
	* @return PMO_Map
	* @throws Exception
	*/
	public function getMapRelated(PMO_Object $object, $tablename){
		$map = $this->getMapByObject($object);
		return $map->getMapByTable($tablename);
	}
	
	/**
	* returns a new map that only contains objects of type tablename with attribute=value.
	*
	* Search is done only on one fields. Faster than getMapByObjectByValue but less powerfull.
	* 
	* @param string $tablename		  table name to search for
	* @param string $attribute		  attribute for which we want to check the value
	* @param string $value			  value to search for 
	* @return PMO_Map
	* @throws Exception				  if no match has been found
	*/
	public function getMapByValue($tablename, $attribute, $value){
		$oldmap = $this->get();		
		foreach($oldmap as $line)
			if($line[$tablename]->get($attribute) == $value)
				return $this->getMapByObject($line[$tablename]);		
							
		throw new Exception("Error: No object ".$tablename." found");
	}
	
	/**
	* returns a new map that only contains objects of type tablename 
	* 
	* @param string $tablename		  the table name to search for
	* @return PMO_Map
	* @throws Exception				  if none has been found
	*/
	public function getMapByTable($tablename){
		$oldmap = $this->get();		
		$newmap = new PMO_MyMap();
		foreach($oldmap as $line){
			if(!isset($line[$tablename]))
				throw new Exception("Error: Object ".$tablename." not exists");
				
			$array[$tablename] = $line[$tablename];
			$newmap->add($array);
		}
		
		if($newmap->count() > 0)
			return $newmap;
		else
			throw new Exception("Error: No object ".$tablename." found");
	}

	/**
	 * returns the current map array of PMO_Object
	 *  
	 * @return array
	 * @throws Exception				if the current map is empty
	 */	
	private function get(){
		if(count($this->map) == NULL)
			throw new Exception('Error: Map is empty');
		
		return $this->map;		
	}
		
	/**
	* returns one row of the current map array structure
	*
	* Null is returned at the end and the iterator is reset.
	* 
	* The array is not poped, it's only a cursor that move
	* an index and return the results. 
	* 
	* @return array
	*/
	public function fetch(){	
		return $this->map->fetch();	
	}

	/**
	* returns one PMO_Object of row of the current map array structure
	*
	* Null is returned at the end and the iterator is reset.
	* 
	* The array is not poped, it's only a cursor that move
	* an index and return the results. 
	* 
	* @return PMO_Object
	*/	
	public function fetchTable($tablename){	
		$value = $this->fetch();
		if($value != NULL)
			return $value[$tablename];
		else
			return NULL;		
	}
	
	/**
	* returns the number of rows in the map
	*
	* @return int
	*/	
	public function count(){
		return $this->map->count();
	}
	
	/**
	* retrieves the first object in map matching with tablename, and attribute=>value
	*
	* If object is not found, returns an exception
	* 
	* @param string $tablename		  table name to search for
	* @param string $attribute		  attribute for which we want to check the value
	* @param string $value			  value to search for 
	* @return PMO_Map
	* @throws Exception				  if no object matches the criteria
	*/
	public function getObjectByValue($tablename, $attribute, $value){
		$oldmap = $this->get();
		foreach($oldmap as $line){
			if(!isset($line[$tablename]))
				throw new Exception("Error: Object ".$tablename." is undefined");
			
			if(! strcmp ($line[$tablename]->get($attribute), $value))
				return $line[$tablename];
		}
		throw new Exception("Error: No object ".$tablename." found");
	}
	
	/**
	* retrieves one object from map using the object primary key as filter
	*
	* All primary keys must be set, it's more powerfull than getObjectByValue
	* but slower too. If object is not found, return an exception
	* 
	* @param object $object		  a PMO_Object to serve as filter
	* @return PMO_Object
	* @throws Exception
	*/
	public function getObjectByObject(PMO_Object $object){
		$oldmap = $this->get();
		$table = $object->getTable();
		$tablename = $table->getTableName();
		$tablepk = $table->getPk();
		
		foreach($oldmap as $line){
			$flag = TRUE;
			foreach($tablepk as $pk){
				if($line[$tablename]->$pk != $object->$pk){ 
					$flag = FALSE;
					break;
				}
			}

			if($flag)
				return($line[$tablename]);
		}
		throw new Exception("Error: No object ".$tablename." found");
	}
	
	/**
	 * Only an exception, for not use PMO_Map as PMO_Object
	 * @throws Exception
	 */
	public function __get($value) {
		throw new Exception("Error: Try to get attribute ".$value." on a PMO_MyMap");
	}
	
}
?>
