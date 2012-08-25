<?php
/**
 * This file contains the PMO_Dbms_Pdo driver class.
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
 * This class implements a PDO driver.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */
class PMO_Dbms_Pdo extends PMO_MyDbms {

	public function __construct(PDO $pdo = NULL) {
		if(isset($pdo))
			$this->setDB($pdo);
	}
	
	public function connect(array $authdb){
		$this->setDB(new PDO("$authdb[pdodriver]:host=$authdb[host];dbname=$authdb[base]", $authdb['user'], $authdb['pass']));
		$this->getDB()->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	
	public function __destruct() {
		$this->setDB(NULL);
	}

	public function query($query){
		$this->result = $this->getDB()->prepare($query);
		if(!$this->result->execute()){
			$errorinfo = $this->result->errorInfo();
			throw new Exception("Error: SQL ".$errorinfo[0]." ".$errorinfo[2]);	
		} else {
			return TRUE;
		}
	}

	public function fetchArray() {
		return $this->result->fetch(PDO::FETCH_ASSOC);
	}

	public function getTableDesc($table) {
		$sql = sprintf('DESC %s ;', addslashes($table));
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
		return $this->getDB()->lastInsertId();
	}

	/**
	 * begin a transaction with Dbms
	 * only with pdo driver
	 */
	public function beginTransaction(){
		return $this->getDb()->beginTransaction();
	}

	/**
	 * commit the transaction with Dbms
	 * only with pdo driver
	 */	
	public function commit(){
		return $this->getDb()->commit();
	}	
	
	/**
	 * rollback the transaction with Dbms
	 * only with pdo driver
	 */	
	public function rollback(){
		return $this->getDb()->rollback();
	}
	
}
?>
