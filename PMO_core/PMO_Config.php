<?php
/**
 * This file contains the PMO_Config interface.
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
 * @since			PhpMyObject v0.14
 * @version			$Revision: $
 * @copyright		Copyright (C) 2007-2008 Nicolas Boiteux 
 * @license			GPLv3 {@link http://www.gnu.org/licenses/gpl}
 * @filesource
 *
 */ 

/**
 * This interface defines the methods a class must implement
 * to provide a working configuration class to the PMO objects.
 * 
 * @package		PhpMyObject
 * @subpackage PMO_Core
 * @see			PMO_MyConfig
 */ 
interface PMO_Config {

	/**
	 * the implementation must return an instance. It it does not exists
	 * it must be created.
	 *
	 * @return object  an object derived from a class implementing this interface.
	 */
	static function factory();

	/**
	 * the implementation must set the passed variable name
	 * with the passed value.
	 *
	 * @param string $varname	the variable name to set
	 * @param mixed  $value		the value to set the variable with
	 * @return void
	 */
	public function set($varname, $value=null);

	/**
	 * the implementation must return the value of the passed variable name
	 * or throw an exception if the variable does not exist.
	 *
	 * @param string $varname	the variable name
	 * @return mixed				the variable value
	 * @throws Exception			if $varname does not exist
	 */
	public function get($varname);

	/**
	 * the implementation must return true if the passed variable exists, false otherwise.
	 *
	 * @param string $varname		name of the variable to check for
	 * @return true|false			true if the variable exists, false otherwise
	 */
	//public function exists($varname);

}
?>
