<?php
/**
 * This file contains the PMO_Dbms_Mysqli driver class.
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
 * @since		PhpMyObject v0.14
 * @version		$Revision: $
 * @copyright	Copyright (C) 2007-2008 Nicolas Boiteux 
 * @license		GPLv3 {@link http://www.gnu.org/licenses/gpl}
 * @filesource
 *
 */ 

/**
 * This class implements a MySqli driver.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */
class PMO_Dbms_Mysqli extends PMO_MyDbms {

	public function __construct($mysqllink = NULL) {
		if(isset($mysqllink))
			$this->setDB($mysqllink);
	}

	public function connect(array $authdb){
		$this->setDB(new mysqli($authdb['host'], $authdb['user'], $authdb['pass'], $authdb['base']));
		
		if(!$this->getDB())
			throw new Exception(mysqli_connect_errno());
		
	}

	public function __destruct() {
		$this->getDB()->close();
	}

	public function query($query){
		$this->result = $this->getDB()->query($query);
		if($this->result)
			return TRUE;
		else
			throw new Exception($query." ".$this->getDB()->error());
	}

	public function fetchArray() {
			return $this->result->fetch_assoc();
	}

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
	
	public function getLastId() {
		return $this->getDb()->mysqli_insert_id();
	}

	public function beginTransaction(){
		$this->getDB()->autocommit(FALSE);
	}
	
	public function rollback(){
		return $this->getDB()->rollback();
	}
	
	public function commit(){
		return $this->getDB()->commit();
	}
}
?>
