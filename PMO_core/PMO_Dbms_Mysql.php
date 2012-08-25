<?php
/**
 * This file contains the PMO_Dbms_Mysql driver class.
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
 * This class implements the MySql driver.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */
class PMO_Dbms_Mysql extends PMO_MyDbms {

	/**
	 * a result resource returned by a MySql query
	 * @var resource
	 */
	protected $result;

	/**
	 * the constructor
	 *
	 * if a mysql link is provided, it will be used.
	 *
	 * @param resource $mysqllink			a standrd MySql link returned by mysql_query()
	 */
	public function __construct($mysqllink = NULL) {
		if(isset($mysqllink))
			$this->setDB($mysqllink);
	}

	/**
	 * establishes a connection with the database server and the database
	 *
	 * @param array $authdb			the database connection information, e.g. host,
	 *										user name and password, database name
	 * @throws Exception				if we cannot connect to the database server or
	 *										the actual database
	 * @see PMO_MyConfig
	 */
	public function connect(array $authdb){
		$this->setDB(mysql_connect($authdb['host'], $authdb['user'], $authdb['pass']));
		if(!$this->getDB())
			throw new Exception(mysql_error());
		
	if(!mysql_select_db($authdb['base'], $this->getDB()))
			throw new Exception(mysql_error());
	}

	/**
	 * closes the database connetion
	 */
	public function __destruct() {
		@mysql_close($this->getDB());
	}

	/**
	 * execute a SQL query against the database
	 *
	 * @param string $query			the SQL query to execute against the database 
	 * @return bool					TRUE is the query returned some results
	 * @throws Exception				if no result were returned by the query
	 */
	public function query($query){
		$this->result = mysql_query($query);
		if($this->result)
			return TRUE;
		else
			throw new Exception($query." ".mysql_error());
	}

	/**
	 * returns the next row as an associative array
	 *
	 * @return array
	 */
	public function fetchArray() {
			return mysql_fetch_assoc($this->result);
	}

	/**
	 * returns an array containing the table properties
	 *
	 * @param string $table			the table name to look for
	 * @return array
	 */
	public function getTableDesc($table) {
		$sql = sprintf('DESC %s', addslashes($table));
		$this->query($sql);
		
		while($dbresult = $this->fetchArray()){
			$tmparray[] = array("Field"=>$dbresult['Field'], 
								"Type" => $this->translateType($dbresult['Type']), 
								"Null" => $dbresult['Null'], 
								"Key"=>$dbresult['Key'], 
								"Default"=>$dbresult['Default'], 
								"Extra"=>$dbresult['Extra'],
								"Perm"=>"rw");
		}
		return $tmparray;
	}

	/**
	 * returns the last inserted id
	 *
	 * @return int
	 */
	public function getLastId() {
		return mysql_insert_id();
	}

	/**
	 * starts a MySql transaction
	 *
	 * @todo need to check if we already are in an open transaction
	 *			and throw an exception if so
	 */
	public function beginTransaction(){
		mysql_query("BEGIN", $this->getDb()); 
	}

	/**
	 * rolls back a transaction
	 *
	 * @todo need to check if we are in a transaction and throw 
	 *			an exception if we are not
	 */
	public function rollback(){
		mysql_query("ROLLBACK", $this->getDb()); 
	}
	
	/**
	 * commits a transaction
	 *
	 * @todo need to check if we are in a transaction and throw 
	 *			an exception if we are not
	 */
	public function commit(){
		mysql_query("COMMIT", $this->getDb()); 
	}	
	
}
?>
