<?php
/**
 * This file contains the PMO_Table interface.
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
 * to provide a working table class capable of describing
 * and working with a database table.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 * @see			PMO_MyTable
 */ 
interface PMO_Table{

	/**
	 * retrieve the tablename of the object
	 * 
	 * @return string
	 * @throws Exception
	 */	
	public function getTableName();

	/**
	 * check if the column is a primary key or not
	 * 
	 * @return boolean
	 */
	public function isPk($pk);	

	/**
	 * retrieves the autoincrement field if it exists
	 * and returns it
	* 
	* @return string|NULL 
	*/
	public function getAutoincrement();	
	
	/**
	 * retrieve all the primary key of the object
	 * and return them in an array
	 * 
	 * @return array
	 * @throws Exception
	 */	
	public function getPk();

	/**
	 * set the primary keys of object with
	 * an array
	 * 
	 * return void
	 */
	public function setPk($attributename);	

	/**
	 * set the forein keys of object with
	 * an array
	 * 
	 * array($tablename=>$keyname)
	 * 
	 * return void
	 */	
	public function setFk($attributename, array $array);

	/**
	 * retrieve all the foreign key of the object
	 * and return them in an array
	 * 
	 * @return array|FALSE
	 */		
	public function getFk();
	
	/**
	 * set the tablename of the object
	 * 
	 * @return void
	 */
	public function setTableName($tablename);	
	
	/**
	 * retrieve an array that contains all the
	 * name of the columns of the table
	 * [0]=>nameofcolumn
	 * 
	 * @return array
	 * @throws Exception
	 */
	public function getColumns();	
	
	/**
	 * return an array that contains
	 * nameofcolumn=>aliasofcolumn
	 * 
	 * @return array
	 * @throws Exception
	 */
	//public function getAlias();
	
	/**
	 * return Perm of an attribute r=read, w=write
	 * 
	 * @return string
	 * @throws Exception
	 */
	public function getPerm($attributename);

	/**
	 * set Perm of an attribute r=read, w=write
	 * @throws Exception
	 */
	public function setPerm($attributename, $value);	

	/**
	 * set Perm for all attributes r=read, w=write
	 */
	public function setPermForAll($value);	
	
	/**
	 * retrieve the real name of column from its alias
	 * 
	 * @return array
	 * @throws Exception
	 */
	//public function getReverseAlias($alias);

	/**
	 * return an attribute in the datastructure of object
	 * array is
	 * attribute => value
	 * 
	 * @throws Exception
	 */
	public function get($attributename);	

	/**
	 * check if an alias exist or not in the table_alias
	 * 
	 * @return boolean
	 */
	//public function issetAlias($aliasname);

	/**
	 * check if an attribute exist or not in the table_alias
	 * 
	 * @return boolean
	 */
	public function issetAttribute($attributename);	
	
	/**
	 * return the class name used to instanciate a PMO_Object
	 * corresponding to this PMO_Table
	 * 
	 * @return FALSE
	 */
	public function getClassname();	
	
	/**
	 * set the attribute "Field" with a value
	 * 
	 * @return void
	 */
	public function set(array $attributevalue);

	/**
	 * create a PMO_MyTable_xxx class at the first execution time, and
	 * flush it to the disk.
	 *
	 * This class describe the data structure of tables, their primary keys,
	 * columns aliases and the class name to used to instanciate 
	 * the PMO_Object.
	 * 
	 * @return TRUE
	 */
	public function persist();	
	
}
?>
