<?php
/**
 * This file contains the PMO_Controller interface.
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
 * @package			PhpMyObject
 * @subpackage 	PMO_Core
 * @author			Nicolas Boiteux <nicolas_boiteux@yahoo.fr>
 * @link				http://pmo.developpez.com/
 * @since			PhpMyObject v0.1
 * @version			$Revision: $
 * @copyright		Copyright (C) 2007-2008 Nicolas Boiteux 
 * @license			GPLv3 {@link http://www.gnu.org/licenses/gpl}
 * @filesource
 *
 */ 


/**
 * This interface defines the methods a class must implement
 * to provide a working controller class that will be able
 * to query the data and return a {@link PMO_MyMap} map
 * of objects.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 * @see			PMO_MyController
 */ 
interface PMO_Controller {

	/**
	 * Return map of objects already loaded through {@link query()}
	 * 
	 * @return PMO_Map
	 * @throws Exception
	 */
	public function getMapObjects();
	
	/**
	 * Execute an sql query without treatment
	 *
	 * @param string $query			the query to execute
	 * @return object|resource		the method must return either a PDO object
	 *										or a DBMS resource link, depending on the
	 *										selected driver
	 */
	public function rawquery($query);
	
	/**
	 * Execute a PMO_Request query
	 *
	 * @param object $request		a {@link PMO_Request} object
	 */
	public function objectquery(PMO_Request $request);

	/**
	 * Execute an sql query and
	 * return the corresponding PMO_Map fill with PMO_Object
	 *
	 * @param string $query		the query to execute
	 * @return PMO_Map
	 * @throws Exception
	 */
	public function query($query);
	
	/**
	 * Should not be use
	 * return all tuples of one table
	 * equivalent : SELECT	* table;
	 * 
	 * @param object $table			a {@link PMO_Table} object
	 * @return PMO_Map
	 * @throws Exception
	 */
	public function queryAll(PMO_Table $table);
	
}
?>
