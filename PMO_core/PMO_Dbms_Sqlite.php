<?php
/**
 * This file contains the PMO_Dbms_Sqlite driver class.
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

/** requires the PDO driver */
require_once(dirname(__FILE__).'/PMO_Dbms_Pdo.php');

/**
 * This class implements a Sqlite driver.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */
class PMO_Dbms_Sqlite extends PMO_Dbms_Pdo {
	
	public function connect(array $authdb){
		$this->setDb(new PDO($authdb['dsn']));
		$this->getDb()->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	
	public function getTableDesc($table) {
		$sql = sprintf('PRAGMA table_info(%s) ;', addslashes($table));
		$this->query($sql);

		foreach($this->result as $row){
			if(isset($row[1])){
				$Field = $row[1];
			}else{
				throw New Exception("Fatal Error: column doesn't exist");
			}
			
			if(isset($row[2]))
				$Type = $row[2];	

			if ($Field == $this->getAutoincrementColumn($table))
				$Extra = "auto_increment";
			else
				$Extra = "";	
			
			if(!empty($row[3]))
				$Null = "YES";
			else
				$Null = "NO";				

			if(isset($row[4]))
				$Default = $row[4];
			else
				$Default = "";	

			if(!empty($row[5]))
				$Key = "PRI";
			else
				$Key = "";
				
			$tmparray[] = array("Field" => $Field, 
								"Type" => $this->translateType($Type), 
								"Null" => $Null, 
								"Key"=>$Key, 
								"Default"=>$Default, 
								"Extra"=>$Extra,
								"Perm"=>"rw");
		}
		
		if(isset($tmparray))
			return($tmparray);
		else
			throw new Exception("Error: table $table doesn't exist");
	}

	/**
	 * returns the autoincrement column name
	 *
	 * this takes the SQL used to create the table as provided
	 * by the sqlite_master table
	 *
	 * @param string $sql
	 * @return string|FALSE		the autoincrement column name or
	 * 								FALSE if there is no increment column
	 */
 	private function getAutoincrementColumn($table) {
 		$sql = sprintf('SELECT sql FROM sqlite_master WHERE tbl_name = "%s" ;', addslashes($table));
		$this->query($sql);
		$ExtraSql = $this->fetchArray();
		
		$sql = $ExtraSql['sql'];
 		$tmp = explode('(', $sql);
 		$tmp = explode(',', $tmp[1]);

		foreach ($tmp as $row) {
			if (FALSE !== stripos($row, 'AUTOINCREMENT')) {
				$arr = explode(' ', $row);
				return str_replace('`', '', $arr[0]);
			}
		}
		
		return FALSE;
	}
}
?>
