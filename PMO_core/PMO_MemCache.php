<?php
/**
 * This file contains the PMO_MemCache interface.
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
 * This interface defines the methods a class must implement
 * to provide a working MemCache class.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 * @see			PMO_MyMemCache
 */ 
interface PMO_MemCache{

	/**
	 * connects to the memcache server
	 *
	 * connect() uses the configuration information from
	 * your_config.php
	 */
	public function connect();

	/**
	 * closes the connection to the memcache server
	 */
	public function close();

	/**
	 * retrieve a PMO_Object into the memcache
	 * 
	 * @return PMO_Object
	 */	
	public function get(PMO_Object $object);	
	
	/**
	 * replaces an existing object in the memcache
	 */
	public function replace(PMO_Object $object);

	/**
	 * inserts a new PMO_Object into the memcache
	 */
	public function set($key, PMO_Object $object);

	/**
	 * deletes an existing PMO_Object from the memcache server
	 */
	public function delete(PMO_Object $object);
	
	public function flush();

}
?>
