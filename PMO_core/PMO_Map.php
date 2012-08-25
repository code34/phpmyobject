<?php
/**
 * This file contains the PMO_Map interface.
 *
 * This file is part of the PhpMyObject project,
 * an Object-Relational Mapping (ORM) system.
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
 * along with this program.  If not, see {@link http://www.gnu.org/licenses/}.
 *
 * @package		PhpMyObject
 * @subpackage PMO_Core
 * @author		Nicolas Boiteux <nicolas_boiteux@yahoo.fr>
 * @link			http://pmo.developpez.com/
 * @since		PhpMyObject v0.1
 * @version		$Revision: $
 * @copyright	Copyright (C) 2007-2008 Nicolas Boiteux 
 * @license		GPLv3 {@link http://www.gnu.org/licenses/gpl}
 * @filesource
 *
 */ 

/**
 * This interface defines the methods a class must implement
 * to provide a working PMO map class.
 * 
 * PMO_Map is a array of PMO_Objects. 
 * Each row of array contains as many PMO_Objects
 * as there is table concerns by the SQL Request
 *
 * @package		PhpMyObject
 * @subpackage PMO_Core
 * @see			PMO_MyMap
 */ 
interface PMO_Map{
	
	/**
	 * Add an array of PMO_Object to map
	 * tablename=>PMO_Object
	 * 
	 * @return void
	 */
	public function add(array $row);
		
	/**
	 * build a new map from an other map
	 * this new map only contains row that are relative to the object
	 * 
	 * @return PMO_Map
	 * @throws Exception
	 */
	public function getMapByObject(PMO_Object $object);
	
	/**
	 * build a new map from a map that contains
	 * row relative to the object. This function
	 * use the values of the primary keys to retrieve
	 * the lines concerned. 
	 * 
	 * Others value can stay NULL
	 * 
	 * @return PMO_Map
	 * @throws Exception
	 */
	public function getMapByObjectByValue(PMO_Object $object);
	
	/**
	 * Alias of getMapByObject
	 */
	public function getMapLinked(PMO_Object $object);
	
	/**
	 * build a new map that only contains objects of type tablename
	 * relative to our object.
	 * 
	 * @return PMO_Map
	 * @throws Exception
	 */
	public function getMapRelated(PMO_Object $object, $tablename);	
	
	/**
	 * build a new map that only contains objects of type tablename with attribute=value.
	 * Search is done only on one fields. Faster than getMapByObjectByValue but less powerfull.
	 * 
	 * @return PMO_Map
	 * @throws Exception
	 */
	public function getMapByValue($tablename, $attribute, $value);	
	
	/**
	 * build a new map that only contains objects of type tablename 
	 * 
	 * @return PMO_Map
	 * @throws Exception
	 */
	public function getMapByTable($tablename);
	
	/**
	 * return one row of the array structure
	 * Null is returned at the end
	 * The array is not pop, it's only a cursor
	 * that move on index and return the results. 
	 * 
	 * @return array
	 */
	public function fetch();

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
	public function fetchTable($tablename);	
	
	/** 
	* Return number of rows for this PMO_Map
	*/
	public function count();

	/**
	 * retrieve first object in map matching with tablename, and attribute=>value
	 * If object is not find return an exception
	 * 
	 * @throws Exception
	 * @return PMO_Map
	 */
	public function getObjectByValue($tablename, $attribute, $value);

	/**
	 * retrieve one object in map comparing the value of primary key of object in param
	 * All primary keys must be set, it's more powerfull than getObjectByValue
	 * but slower too. If object is not find, return an exception
	 * 
	 * @throws Exception
	 * @return PMO_Object
	 */
	public function getObjectByObject(PMO_Object $object);
	
}


?>
