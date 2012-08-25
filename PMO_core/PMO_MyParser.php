<?php
/**
 * This file contains the PMO_MyMapObject class which implements
 * the generic PMO_Object that represents a tuple in database.
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
 */ 

/** requires the interface */
require_once(dirname(__FILE__).'/PMO_Parser.php');

/**
 * This class	is used to parse SQL query and extract
 * the tablename and the fields used
 *
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */ 
class PMO_MyParser implements PMO_Parser{
	
	protected $array_functions ;
	protected $array_tables ;
	protected $array_fields ;
	
	public function __construct(){
		$this->array_fields = new PMO_MyArray();
		$this->array_tables = new PMO_MyArray();
		$this->array_functions = new PMO_MyArray();
	}
	
	/**
	 * extract the name of the tables and fields
	 * from an SQL query and put them
	 * into fields & tables variables 
	 * 
	 * @throws Exception
	 * @return TRUE
	 */
	public function parseRequest($string){
		$string = addslashes($string);
		$str = explode(' ', $string);
		$str = str_replace(array(';', '\r', '\n'), array('', ' ', ' '), $str);

		$mode = '';
		foreach( $str as $word){
			switch($mode){
				  case 'SELECT':
					$fields = explode(',', $word);
					foreach($fields as $field){
						if(preg_match('/[a-zA-Z_]+\([a-zA-Z_]+\)/', $field, $alias)){
							$this->addFunction($alias[0]);
						}else{	
							if(preg_match('/\.[a-zA-Z_]+/', $field, $alias)){
								$this->addField(substr($alias[0], 1, strlen($alias[0])));
							}else{
								$this->addField($field);
							}
						}
					}
					break;
				  
				case 'FROM':
					$tables = explode(',', $word);
					foreach($tables as $table)
						$this->addTable($table);
					return TRUE;
					
				case 'WHERE':
					return TRUE;
				
				default:
					break;
			}
	
			$mode = '';
			
			$word = strtoupper($word);
			switch($word){
				case 'SELECT':
					$mode = $word;
					break;
				case 'FROM':
					$mode = $word;
					break;
				case 'WHERE':
					return TRUE;
				default:
					break;
			}
		}
	}

	/**
	 * add a tablename
	 * stocked in tables array
	 * 
	 * @return void
	 * @throws Exception
	 */
	public function addTable($tablename){
		if(empty($tablename))
			throw new Exception("Fatal Error: Your SQL QUERY must only contains ',' between tables name. ");
		
		$this->array_tables->append($tablename);
	}

	/**
	 * add an sql function 
	 * into function array
	 * 
	 * @return void
	 */	
	public function addFunction($function){
		$this->array_functions->append($function);
	}
	
	/**
	 * add a field name to 
	 * field array
	 * 
	 * @return void
	 */
	public function addField($field){
		if($field != '*')
			$this->array_fields->append($field);
	}
	
	/**
	 * count the number of fields
	 * 
	 * @return int
	 */
	public function countFields(){
		return $this->array_fields->count();
	}

	/**
	 * fetch fieldname stocked
	 * in the fields PMO_MyArray
	 * 
	 * @return string
	 */
	public function fetchField(){		
		return $this->array_fields->fetch();	
	}

	/**
	 * fetch sql function stocked
	 * in the functions PMO_MyArray
	 * 
	 * @return string
	 */
	public function fetchFunction(){
		return $this->array_functions->fetch();
	}
	
	/**
	 * fetch tablename stocked
	 * in the fields PMO_MyArray
	 * 
	 * @return string
	 */	
	public function fetchTable(){
		return $this->array_tables->fetch();
	}
	
}
?>
