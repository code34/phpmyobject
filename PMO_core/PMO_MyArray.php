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

/**
 * PMO_MyArray adds iterators to PMO stores.
 *
 * @package		PhpMyObject
 * @subpackage PMO_Core
 */
class PMO_MyArray implements Countable,Iterator{

	/**
	* holds the PMO objects
	* @var array
	*/
	protected $array = array();

	public function append($value){
		$this->array[] = $value;
	}	
	
	public function add($key, $value){
		$this->array[$key] = $value;
	}
	
	public function offsetset($key, $value){
		$this->array[$key] = $value;
	}
	
	public function offsetget($key){
		return $this->array[$key];
	}
	
	public function offsetExists($key){
		if(isset($this->array[$key]))
			return TRUE;
			
		return FALSE;
	}
	
	public function arsort(int $sort_flags = NULL){
		return asort($this->array, $sort_flags);
	}
	
	public function asort(int $sort_flags = NULL){
		return asort($this->array,$sort_flags);
	}
		
	public function change_key_case(){
		$this->array = array_change_key_case($this->array);
	}

	/**
	* already taken care by ArrayIterator as count();
	*/
	public function count_values(){
		return array_count_values($this->array);
	}
	
	public function count(int $mode = NULL){
		return count($this->array, $mode);
	}

	public function current(){
		return current($this->array);
	}
		
	public function each(){
		if($this->valid()){
			$value = array($this->key()=>$this->current());
			$this->next();
			return $value;
		}else{
			return NULL;
		}
	}
	
	public function end(){
		return end($this->array);
	}
	
	public function get($key){
		return $this->array[$key];
	}
	
	public function in_array(mixed $needle, array $haystack, bool $strict = NULL){
		return in_array($needle, $this->array, $strict);
	}

	public function implode($char){
		return implode($char, $this->array);
	}
	
	public function key(){
		return key($this->array);
	}
	
	/**
	* return true if the key exists, false otherwise
	*
	* Note  l'array est indexé numériquement. Donc la clé est un entier,
	*	   à moins d'utiliser un map hash comme dans MyMapHash?
	*
	* @return bool	  true if the key exists, false otherwise
	*/
	public function key_exists( mixed $key){
		  return array_key_exists($key, $this->array);
	}	
	
	public function krsort(int $sort_flags = NULL){
		return krsort($this->array, $sort_flags);
	}
	
	public function ksort(int $sort_flags = NULL){
		return ksort($this->array, $sort_flags);
	}

	public function fetch(){
		if($this->valid() == TRUE){
			$value = $this->current();
			$this->next();
			return $value;
		}else{
			return NULL;
		}
	}
		
	public function flip(){
		$this->array = array_flip($this->array);
	}
	
	public function multisort(){
		return $this->array = array_multisort($this->array);
	}

	/**
	* merge any number of array to the main array
	*
	* arrays must be of the type provided when the object was istanciated
	*
	* @var array $arg,...	 any number of array to merge
	*/
	public function merge($arg){
	if (count($arg) > 0){
		$this->array = array_merge($this->array, $arg);
	}
	else
		throw new Exception("Error: No argument provided");
	}

	public function natcasesort(){
		$this->array = natcasesort($this->array);
	}
	
	public function natsort(){
		$this->array = natsort($this->array);
	}
	
	public function next(){
		return next($this->array);	
	}	
	
	public function pad(int $pad_size, mixed $pad_value){
		return array_pad($this->array, $pad_size, $pad_value);
	}

	/**
	* returns the last element from the array and removes it
	*
	* @return object
	*/
	public function pop(){
		return array_pop($this->array);
	}

	public function product(){
		return array_product($this->array);
	}

	public function prev(){
		return prev($this->array);
	}	
	
	/**
	* adds an object to the internal store
	*
	* @param object $var
	*/
	public function push($var){
		if(isset($this->instanceof))
			if($var instanceof $this->instanceof)
				$this->array[] = $var;
			else
				throw new Exception("Error of type");
	}
	
	public function rand(int $num_req){
		return array_rand($this->array, $num_req);
	}

	public function reverse(bool $preserve_keys = NULL){
		$this->array = array_reverse($this->array, $preserve_keys);
	}

	public function reset(){
		return reset($this->array);
	}	
	
	public function unique(){
		$this->array = array_unique($this->array);
	}

	/**
	* prepends a new object to the store
	*
	* @var object $var
	*/
	public function unshift(Object $var){
		return array_unshift($this->array, $var);
	}

	public function values(){
		return array_values($this->array);
	}
	
	public function rewind(){
		return $this->reset();
	}	
	
	public function rsort(int $sort_flags = NULL){
		return rsort($this->array, $sort_flags);
	}

	public function shuffle(){
		return shuffle($this->array);
	}

	public function search(mixed $needle, bool $strict = NULL){
		return array_search($needle,$strict);
	}

	/**
	* returns an object from the beginnig if the store and remvoes it
	*
	* @return object
	*/
	public function shift(){
		return array_shift($this->array);
	}
	
	public function slice(int $offset,int $length = NULL, bool $preserve_keys = NULL){
		return array_slice($this->array, $offset, $length, $preserve_keys);
	}
	
	public function sum(){
		return array_sum($this->array);
	}	
	
	public function sort(int $sort_flags = NULL){
		return sort($this->array, $sort_flags);
	}	
	
	public function valid(){
		if($this->current())
			return TRUE;
		
		$this->rewind();
		return FALSE;
	}
}

?>
