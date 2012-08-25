<?php
/**
 * This file contains the PMO_Object interface.
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
 * to provide a working PMO_Object table class capable of managing
 * database entities.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 * @see			PMO_MyObject
 */ 
interface PMO_Object{
	
	/**
	 * Return the reference of the object PMO_Table
	 * linked to the object PMO_Table describe the
	 * structure of the table that was used to build
	 * the object data structure (name of columns,
	 * primary keys, ..)
	 * 
	 * @return PMO_Table
	 * @throws Exception
	 */
	public function getTable();	

	/**
	 * return the data structure of the PMO_Object
	 * array is
	 * attribute=>value
	 * 
	 * @throws Exception
	 */
	public function getObjectAttribute();	
	
	/**
	 * return an array wich contains
	 * all the names of attributes of PMO_Object
	 * 
	 * @return array
	 * @throws Exception
	 */	
	public function getListAttribute();

	/**
	 * return the value of an attribute  
	 * this function also cleans the escape chars
	 * with a simple stripslashes
	 * 
	 * @return string
	 * @throws Exception
	 */
	public function get($attribute);

	/**
	 * export the data structure of an object in a xml format stream
	 * <attributes>
	 * <attributename>attributevalue</attributename>
	 * </attributes>
	 */ 
	public function toXml($encoding);	
	
	/**
	 * Set the value of an attribute of the data structure
	 * this function already escape strange char with 
	 * a	simple addslashes
	 * 
	 * data structure is an array 
	 * attribute => attributevalue
	 * 
	 * @throws Exception
	 * @return TRUE
	 */
	public function set($attribute, $value);
		
	/**
	 * define the object as new, not already
	 * present in the database This flag
	 * is used to define when we do an insert
	 * or an update
	 * 
	 * 
	 * @return void
	 */
	public function setNew($flag);	
	
	/**
	 * return the flag used to define if 
	 * objet is present or not in database
	 */	
	public function getNew();
	
	/**
	 * Load data of object from database
	 * using the value of primary key
	 * All primary key should be 
	 * set to retrieve the data
	 * 
	 * @throws Exception
	 * @return void
	 */
	public function load();	
	
	/**
	 * Delete data of object into database
	 * using the value of primary keys, all
	 * primary keys should be set to delete
	 * the right tuple
	 * 
	 * @throws Exception
	 * @return void
	 */
	public function delete();
		
	/**
	 * Save data of object into database
	 * insert or update dependings the value of 
	 * the object_new flags
	 * 
	 * @throws Exception
	 */
	public function save();
	
}
?>
