<?php
/**
 * This file contains the PMO_MyMemCache class
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    PhpMyObject
 * @subpackage PMO_Core
 * @author     Nicolas Boiteux <nicolas_boiteux@yahoo.fr>
 * @link       http://pmo.developpez.com/
 * @since      PhpMyObject v0.1x
 * @version    $Revision: $
 * @copyright  Copyright (C) 2007-2008 Nicolas Boiteux 
 * @license    GPLv3 {@link http://www.gnu.org/licenses/gpl}
 * @filesource
 *
 */ 

/** requires the interface */
require_once(dirname(__FILE__).'/PMO_MemCache.php');

/**
 * this class provides for memcaching PMO_Objects
 *
 * @package 	PhpMyObject
 * @subpackage PMO_Core
 * @todo 		make a tutorial to document this with usage and memcache installation
 */
class PMO_MyMemCache implements PMO_MemCache{

	/**
	 * holds the PMO_MyMemcache object
	 * @var object
	 * @static
	 */
	protected static $INSTANCE;

	/**
	 * holds the Memcache object
	 * @var object
	 */
	protected $memcache;

	/**
	 * instanciates the class and stores it to $this->memcache
	 */
	private function __construct(){
		$this->memcache = new Memcache();
	}

	/**
	 * return the current instance of PMO_MyMemCache
	 *
	 * the instance will be created on the first call to factory()
	 *
	 * @return object 			the PMO_MuMecache instance
	 */
	public function factory(){
		if(!isset(self::$INSTANCE))
			self::$INSTANCE = new PMO_MyMemCache();
		
		return self::$INSTANCE;		
	}

	/**
	 * establishes a connection with the memcache server
	 *
	 * parameters are taken from your {@link PMO_MyConfig} configuration
	 *
	 * @return bool 				TRUE if the connection succeeded
	 * @throw Exception 			if the connection could not be established
	 */
	public function connect(){
		if(!$this->memcache->connect(PMO_MyConfig::factory()->get('PMO_MyMemCache.HOST'),PMO_MyConfig::factory()->get('PMO_MyMemCache.PORT')))
			throw new Exception("Error: connexion to memcache failed!");
			
		return TRUE;
	}

	/**
	 * closes the connection to the memcache server
	 */
	public function close(){
		$this->memcache->close();
	}

	/**
	 * loads a PMO_Object from the cache
	 *
	 * @param object $object 		the PMO_Object to load
	 * @return PMO_Object
	 */
	public function get(PMO_Object $object){
		$sign = sha1($object);
		return $this->memcache->get($sign);
	}
	
	/**
	 * replaces an existing PMO_Object into the cache
	 *
	 * @param object $object 		the PMO_Object to replace with
	 */
	public function replace(PMO_Object $object){
		$sign = sha1($object);
		$this->memcache->replace($sign, $object);
	}

	/**
	 * inserts a new PMO_Object into the cache
	 *
	 * @param object $object 		the PMO_Object to insert
	 */
	public function set($key, PMO_Object $object){
		$sign = sha1($object);
		$this->memcache->set($sign, $object, PMO_MyConfig::factory()->get('PMO_MyMemCache.TIMEOUT'));
	}

	/**
	 * deletes a PMO_Object from the cache
	 *
	 * @param object $object 		the PMO_Object to be deleted
	 */
	public function delete(PMO_Object $object){
		$sign = sha1($object);
		$this->memcache->delete($sign, 0);
	}
	
	/**
	 * invalidates all objects from the cache
	 */
	public function flush(){
		$this->memcache->flush();
	}

}

?>
