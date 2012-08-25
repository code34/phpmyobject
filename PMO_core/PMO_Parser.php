<?php
/**
 * This file contains the PMO_Parser interface.
 *
 * This file is part of the PhpMyObject project,
 * an Object-Relational Mapping (ORM) system.
 * 
 * For questions, help, comments, discussion, etc., please join our
 * forum at {@link http://www.developpez.net/forums/forumdisplay.php?f=770} 
 * or our mailing list at {@link http://groups.google.com/group/pmo-dev}.
 *
 * Copyright (c) 2007-2008 Nicolas Boiteux
 *
 * LICENSE
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
 * to provide a working parser class capable of parsing
 * a SQL query.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 * @see			PMO_MyParser
 */ 
interface PMO_Parser{

	/**
	 * Add a field viewed by parser
	 * 
	 * @throws Exception
	 * @return void
	 */
	public function addField($field);
	
	/**
	 * Add a table viewed by parser
	 * 
	 * @throws Exception
	 * @return void
	 */
	public function addTable($table);

	/**
	 * add an sql function 
	 * into function array
	 * 
	 * @return void
	 */	
	public function addFunction($fonction);
	
	/**
	 * count the number of fields seen by parser
	 * 
	 * @return int
	 */
	public function countFields();
	
	/**
	 * extract the name of the tables and fields
	 * from an SQL query and put them
	 * into fields & tables variables 
	 * 
	 * @throws Exception
	 * @return TRUE
	 */
	public function parseRequest($string);

	/**
	 * fetch the fieldname 
	 * stocked in fields PMO_MyArray.
	 * 
	 * @return string
	 */
	public function fetchField();

	/**
	 * fetch tablename stocked
	 * in the fields PMO_MyArray
	 * 
	 * @return string
	 */	
	public function fetchTable();

	/**
	 * fetch sql function stocked
	 * in the functions PMO_MyArray
	 * 
	 * @return string
	 */	
	public function fetchFunction();
}
?>
