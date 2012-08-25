<?php
/**
 * This file contains PMO_MyConfig class which is used to store
 * the PhpMyObject configuration.
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
 * @since		PhpMyObject v0.14
 * @version		$Revision: $
 * @copyright	Copyright (C) 2007-2008 Nicolas Boiteux 
 * @license		GPLv3 {@link http://www.gnu.org/licenses/gpl}
 * @filesource
 *
 */ 

/** requires the interface */
require_once(dirname(__FILE__).'/PMO_Config.php');

/**
 * PMO_MyConfig manages your system configuration.
 *
 * Use this class to takes care of database config and paths to the
 * class_loader/ and PMO_MyTable/ directories 
 *
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */
class PMO_MyConfig implements PMO_Config {

	/**
	 * the static instance of PMO_MyConfig that will be returned
	 * @var object $INSTANCE
	 * @static
	 * @access protected
	 */
	protected static $INSTANCE;

	/**
	 * PMO_Myarray where the PMO configuration variables are stored
	 *
	 * On instanciation, this array is filled with default values for
	 * the collector name, classpaths and classname prefixes. The database
	 * configuration properties are set to empty strings for wich you will
	 * need to provide values.
	 *
	 * PhpMyObject Configuration Properties:
	 *
	 * - PMO_MyController.OBJECT_COLLECTOR_NAME = "collector"
	 * - PMO_MyDbms.DRIVER = ""
	 * - PMO_MyDbms.PDODRIVER = ""
	 * - PMO_MyDbms.HOST = ""
	 * - PMO_MyDbms.BASE = ""
	 * - PMO_MyDbms.USER = ""
	 * - PMO_MyDbms.PASS = ""
	 * - PMO_MyDbms.DSN = ""
	 * - PMO_MyDbms.LOG = FALSE
	 * - PMO_MyDbms.LOG_FORMAT = "Y-m-d H:i:s"
	 * - PMO_MyObject.CLASSPATH = dirname(__FILE__).'/../class_loader/'; 
	 *										located at the same level as PMO_Core
	 * - PMO_MyObject.CLASS_FILENAME_PREFIX = "class_";
	 *										prefix to use before table names in the
	 *										class_loader directory
	 * - PMO_MyTable.CLASSPATH  = dirname(__FILE__).'/PMO_MyTable/';
	 *										located under PMO_Core
	 * - PMO_MyTable.CLASS_FILENAME_PREFIX =	"PMO_MyTable_";
	 *										prefix to use in naming the table file names
	 *										that are persisted by PMO
	 * - PMO_MyMemCache.ACTIVE = FALSE
	 * - PMO_MyMemCache.HOST = ""
	 * - PMO_MyMemCache.PORT = ""
	 * - PMO_MyMemCache.TIMEOUT = 10
	 * 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $structure;

	/**
	 * the constuctor
	 *
	 * the constructor initializes variables that are needed by PMO, e.g.
	 *
	 * - the PMO_MyController.OBJECT_COLLECTOR_NAME
	 * - the PMO_MyDbms.DRIVER
	 * - the PMO_MyDbms.PDODRIVER
	 * - the PMO_MyDbms.HOST
	 * - the PMO_MyDbms.BASE
	 * - the PMO_MyDbms.USER
	 * - the PMO_MyDbms.PASS
	 * - the PMO_MyDbms.DSN
	 * - the PMO_MyDbms.LOG
	 * - the PMO_MyDbms.LOG_FORMAT
	 * - the PMO_MyObject.CLASSPATH
	 * - the PMO_MyObject.CLASS_FILENAME_PREFIX
	 * - PMO_MyMemCache.ACTIVE
	 * - PMO_MyMemCache.HOST
	 * - PMO_MyMemCache.PORT
	 * - PMO_MyMemCache.TIMEOUT
	 * - the PMO_MyTable.CLASSPATH
	 * - the PMO_MyTable.CLASS_FILENAME_PREFIX
	 * - the PMO_MyTable.CLASS_WRITE_ON_DISK
	 *
	 * @see your_config.php
	 *
	 * @return PMO_MyConfig		the PMO_MyConfig instance
	 */
	private function __construct(){
		$this->structure = new PMO_MyArray();
		$this->structure->offsetSet('PMO_MyController.OBJECT_COLLECTOR_NAME', "collector");
		$this->structure->offsetSet('PMO_MyDbms.DRIVER', "");
		$this->structure->offsetSet('PMO_MyDbms.PDODRIVER', "");
		$this->structure->offsetSet('PMO_MyDbms.HOST', "");
		$this->structure->offsetSet('PMO_MyDbms.BASE', "");
		$this->structure->offsetSet('PMO_MyDbms.USER', "");
		$this->structure->offsetSet('PMO_MyDbms.PASS', "");
		$this->structure->offsetSet('PMO_MyDbms.DSN', "");
		$this->structure->offsetSet('PMO_MyDbms.LOG', FALSE);
		$this->structure->offsetSet('PMO_MyDbms.LOG_FORMAT', "Y-m-d H:i:s");
		$this->structure->offsetSet('PMO_MyObject.CLASSPATH', dirname(__FILE__).'/../class_loader/');
		$this->structure->offsetSet('PMO_MyObject.CLASS_FILENAME_PREFIX', "class_");
		$this->structure->offsetSet('PMO_MyMemCache.ACTIVE', FALSE);
		$this->structure->offsetSet('PMO_MyMemCache.HOST', "");
		$this->structure->offsetSet('PMO_MyMemCache.PORT', "");
		$this->structure->offsetSet('PMO_MyMemCache.TIMEOUT', 10);
		$this->structure->offsetSet('PMO_MyTable.CLASSPATH', dirname(__FILE__).'/PMO_MyTable/');
		$this->structure->offsetSet('PMO_MyTable.CLASS_FILENAME_PREFIX', "PMO_MyTable_");
		$this->structure->offsetSet('PMO_MyTable.CLASS_WRITE_ON_DISK', FALSE);
	}
	
	/**
	 * var_dump all configuration
	 */
	public function show(){	
		print'<pre>';var_dump($this->structure);print'</pre>';
	}
	
	/**
	 * Returns the PMO_MyConfig instance.
	 *
	 * If the instance does not exist yet, it is created.
	 *
	 * @return PMO_MyConfig		the PMO_MyConfig instance.
	 * @static
	 */
	public static function factory(){
			if (!isset(self::$INSTANCE))
				self::$INSTANCE = new PMO_MyConfig;
			
		return self::$INSTANCE; 
	}

	/**
	 * Sets a PMO_MyConfig variable.
	 *
	 * Example:
	 *
	 * <code>
	 * $conf = PMO_MyConfig::factory();
	 * $conf->set('PMO_MyDbms.HOST', 'localhost');
	 * </code>
	 *
	 * @param string $varname	the variable name
	 * @param mixed  $value		the value to set the variable with
	 * @return void
	 */
	public function set($varname, $value = null){
		if($this->structure->offsetExists($varname))	
			$this->structure->offsetSet($varname, $value);
		else
			throw new Exception("Error: Parameter $varname doesn't exist");
	}	 

	/**
	 * alias of the {@link set()} function
	 *
	 * @param string $varname	the variable name
	 * @param mixed  $value		the value to set the variable with
	 */
	public function __set($varname, $value) {
			$this->set($varname, $value);
	}	 

	/**
	 * alias of the {@link get()} function
	 *
	 * @param string $varname		Variable to read. Must exist.
	 * @throws Exception
	 * @return mixed
	 */
	public function __get($varname) {
			return $this->get($varname);
	}		
	
	/**
	 * Returns the value of a PMO_MyConfig variable.
	 *
	 * Will throw an exception if the variable is undefined.
	 *
	 * @param string $varname		variable to read. Must exist.
	 * @throws Exception				thrown if the variable does not exist.
	 * @return mixed
	 */
	public function get($varname){
		if($this->structure->offsetExists($varname))
			return $this->structure->offsetGet($varname);
	
		throw new Exception("Error: Parameter $varname doesn't exist");
	}
	
	/**
	 * returns true if the provided variable name exists, false otherwise.
	 *
	 * @param string $varname		variable name to test for
	 * @return true|false			true if the variable exists, false otherwise
	 * 
	 */
	//public function exists($varname){
	//	if (empty($varname))
	//		throw new Exception("Error: Empty parameter");
	//
	//	return isset($this->structure->offsetGet($varname));
	//}
	
}

?>
