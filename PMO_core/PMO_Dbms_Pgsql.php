<?php
/**
 * This file contains the PMO_Dbms_Pgsql driver class.
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
 * This class implements a PostgreSql driver.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */
class PMO_Dbms_Pgsql extends PMO_MyDbms {

	public function __construct($pgsqllink = NULL) {
		if(isset($pgsqllink))
			$this->setDb($pgsqllink);
	}
	
	public function connect(array $authdb){
			$this->setDb(pg_connect("host={$authdb[host]} dbname={$authdb[base]} user={$authdb[user]} password={$authdb[pass]}"))
			or die(pg_last_error());
	}
	
	public function __destruct() {
		@pg_close($this->getDb());
	}

	public function query($query){
		$this->result = pg_query($query);
		if($this->result)
			return TRUE;
		else
			throw new Exception($query);
	}

	public function fetchArray() {
			return pg_fetch_assoc($this->result);
	}

	public function getTableDesc($table) {
		$this->result = pg_query_params('
			SELECT f.column_name AS "Field",
				CASE 
					WHEN c.contype=\'p\' THEN \'PRI\'
					ELSE \'NULL\'
				END AS "Key"
			FROM
				information_schema.columns AS f
				LEFT JOIN (
					information_schema.constraint_column_usage cu
						JOIN pg_catalog.pg_constraint AS c ON (cu.constraint_name = c.conname))
				ON (f.column_name=cu.column_name AND f.table_name=cu.table_name)
			WHERE
				f.table_name = $1', array(addslashes($table)));
			
		if(!$this->result)
			throw new Exception(pg_last_error());
		
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
		
	}

	public function beginTransaction(){
		
	}
	
	public function rollback(){
		
	}
	
	public function commit(){
		
	}	
}
?>
