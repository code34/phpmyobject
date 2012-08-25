<?php
/**
 * This file contains the PMO_Request interface.
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
 * This interface defines the methods a class must implement
 * to provide a working SQL request to the {@link PMO_MyController}
 * class
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 * @see			PMO_MyRequest
 */
interface PMO_Request{

	/**
	 * the implementation must store the passed $field arguments 
	 * in the {@link PMO_MyRequest::$field} property and return
	 * the object itself.
	 *
	 * @param string $field,...
	 * @return object
	 */
	public function field($field);

	/**
	 * the implementation must store the passed $from arguments
	 * in the {@link PMO_MyRequest::$from} property and return
	 * the object itself.
	 *
	 * @param string $from,...
	 * @return object
	 */
	public function from($from);
	
	/**
	 * the implementation must store the passed $where arguments
	 * in the {@link PMO_MyRequest::$where} property and return
	 * the object itself
	 *
	 * @param string $where,...
	 * @return object
	 */
	public function where($where);
	
	/**
	 * the implementation must store the passed $order arguments
	 * in the {@link PMO_MyRequest::$order} property and return
	 * the object itself.
	 *
	 * @param string $order,...
	 * @return object
	 */
	public function order($order);
	
	/**
	 * the implementation must store the passed $having arguments
	 * in the {@link PMO_MyRequest::$having} array and return
	 * the object itself.
	 *
	 * @param string $having,...
	 * @return object
	 */
	public function having($having);
	
	/**
	 * the implementation must store the passed $limit argument
	 * in the {@link PMO_MyRequest::$limit} property.
	 *
	 * @param string|integer $limit
	 * @return object
	 *
	 */
	public function limit($limit);

	/**
	 * the implementation must create the SQL query using
	 * the stored values.
	 *
	 * @see PMO_MyRequest::toString()
	 * @return string
	 */
	public function toString();
	
	/**
	 * the implementation must reset the object
	 */
	public function reset();
}

?>
