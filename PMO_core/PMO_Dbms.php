<?php
/**
 * This file contains the PMO_Dbms interface.
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
 * @since		PhpMyObject v0.1x
 * @version		$Revision: $
 * @copyright	Copyright (C) 2007-2008 Nicolas Boiteux 
 * @license		GPLv3 {@link http://www.gnu.org/licenses/gpl}
 * @filesource
 *
 */ 

/**
 * This interface defines the methods a class must implement
 * to provide a working Dbms driver class.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 * @see			PMO_MyDbms
 */ 
interface PMO_Dbms {
	
	/**
	 * The implementatio must create a PMO_MyDbms object or if it already
	 * exists, return a reference of this object instance
	 *
	 * @param object $object	a optional PDO object to use.
	 * @return PMO_Dbms
	 * @static
	 */
	static function factory(PDO $object = null);
	
	/**
	 * The implementation must create a data link with the database
	 *
	 * @param array $authdb		an array containing the needed connection info
	 * @see PMO_MyConfig
	 */
	public function connect(array $authdb);
	
	/**
	 * The implementation must return the DB link or DB object
	 *
	 * @return object|resource		either a Dbms object or a resource link
	 */
	public function getDB();

	/**
	 * The implementation must set a DB link or DD object
	 *
	 * @param object|resource $object
	 */
	public function setDB($object);
	
	/**
	 * The implementation must implement a method that will 
	 * execute an SQL query and return true if everything is ok
	 * or thow an eception if no result is found
	 * 
	 * @param string $query			the SQL query to execute
	 * @return bool					true if results are found
	 * @throws Exception				if no result is found
	 */
	public function query($query);

	/**
	 * the implemetation must returns the query results
	 *
	 * @return array
	 */
	public function fetchArray();

	/**
	 * the implementation must an array containing the table properties
	 *
	 * query database for a description of the $table schems 
	 *
	 * like a :
	 * DESC $table;
	 * DESCRIBE $table;
	 * SHOW $table;
	 *
	 * @param sint $table		the table name to work on
	 * @return array
	 */
	public function getTableDesc($table);
	
	/** 
	 * the implementation must return the last inserted primary key value
	 *
	 * @return int
	 */
	public function getLastId();

	/**
	 * the implementation must load data from the database
	 * and fill the passed in PMO_Object with it
	 *
	 * @return void
	 * @throws Exception
	 */
	public function load(PMO_Object $object);	

	/**
	 * the implementation must update the data corresponding to the
	 * PMO_Object in database All primary keys must be fill.
	 *
	 * @return TRUE 
	 * @throws Exception
	 */	
	public function update(PMO_Object $object);
	
	/**
	 * the implementation must delete data corresponding to the PMO_Object
	 * in database All primary key must be fill.
	 * 
	 * @return TRUE
	 * @throws Exception
	 */
	public function delete(PMO_Object $object);

	/**
	 * the implementation must insert new data in database
	 * corresponding to the PMO_Object
	 * all primary keys must be fill.
	 * 
	 * @return TRUE
	 * @throws Exception
	 */		
	public function insert(PMO_Object $object);	
	
	/**
	 * the implementation must start a transaction
	 */
	public function beginTransaction();

	/**
	 * the implementation must rollback an already stated transaction
	 */
	public function rollback();

	/**
	 * the implemetation ust commit the started transaction
	 */
	public function commit();
}
?>
