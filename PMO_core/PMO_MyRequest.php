<?php
/**
 * This file contains PMO_MyRequest class which is used to build a SQL query
 * using provided fields, tables names, where clause, etc.
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
require_once(dirname(__FILE__).'/PMO_Request.php');

/**
 * This class is used to provide a SQL query to PMO_MyController
 * 
 * You provide the different parts of the query by using the 
 * corresponding methods. Each method returns the PMO_MyRequest object
 * which allows you to link the methods together, e.g. 
 * 
 * <code>
 * $request = new PMO_MyRequest;
 * $request->field('film_id')->from('film_actor')->limit('10')
 * $controller = new PMO_MyController;
 * $map = $cntroller->objectquery($request)
 * </code>
 *
 * This could also be written like this:
 *
 * <code>
 * $controller = new PMO_MyController;
 * $request = new PMO_MyRequest;
 * $controller->objectquery($request->field('film_id')->from('film_actor')->limit('10'));
 * </code>
 *
 * Most methods accept a variable number of arguments. So you can do something like this:
 *
 * <code>
 * $request = new PMO_MyRequest;
 * $request->field('film_id','actor_id')->from('film_actor')->limit('10')
 * </code>
 *
 * @package 	PhpMyObject
 * @subpackage PMO_Core
 */
class PMO_MyRequest implements PMO_Request{
	
	/**
	 * the SQL query fields
	 *
	 * if none is provided, a SELECT * will be performed.
	 *
	 * @var array
	 * @access protected
	 */
	protected $field;

	/**
	 * the table names of the query
	 * 
	 * @var array
	 * @access protected
	 */
	protected $from;

	/**
	 * the where clause of the query
	 * 
	 * @var array
	 * @access protected
	 */
	protected $where;
	
	/**
	 * the order clause
	 * 
	 * @var array
	 * @access protected
	 */
	protected $order;

	/**
	 * the having clause
	 * 
	 * @var array
	 * @access protected
	 */
	protected $having;

	/**
	 * the limit clause
	 *
	 * @var array
	 * @access protected
	 */
	protected $limit;

	/**
	 * the groupby clause
	 * 
	 * @var array
	 * @access protected
	 */
	protected $groupby;
	
	public function __construct(){
		$this->field = new PMO_MyArray();
		$this->from = new PMO_MyArray();
		$this->where = new PMO_MyArray();
		$this->order = new PMO_MyArray();
		$this->having = new PMO_MyArray();
	}
	
	
	/**
	 * stores the provided column names from which data will be read.
	 *
	 * This method accepts a variable number of arguments. 
	 *
	 * <code>
	 * $request = new PMO_MyRequest;
	 * $request->field('column1', 'column2')->from('mytable');
	 * </code>
	 *
	 * If you do not provide column names, a "SELECT *" will be performed.
	 *
	 * @param string $field,...	any number of column names you want to read
	 */
	public function field($field){
		$arg = func_get_args();
		$this->field->merge($arg);
		return $this;
	}

	/**
	 * stores the table names on which the request is going to be run
	 *
	 * This method accepts a variable number of arguments. 
	 *
	 * <code>
	 * $request = new PMO_MyRequest;
	 * $request->field('column1', 'column2')->from('mytable1', 'mytable2');
	 * </code>
	 *
	 * @param string $from,...		any number of table names you want to read from
	 */
	public function from($from){
		$arg = func_get_args();	
		$this->from->merge($arg);
		return $this;
	}
	
	/**
	 * stores the where clause
	 *
	 * This method accepts a variable number of arguments. 
	 * You create the where clause by providing conditions.
	 *
	 * <code>
	 * $request = new PMO_MyRequest;
	 * $request->from('MyTable')->where('name = "value"', 'active = 1');
	 * </code>
	 *
	 * {@link toString()} will create your where clause by linking them with
	 * the 'AND' keyword.
	 *
	 * @param string $where,...	any number of conditions to build your 
	 *										where clause with
	 */
	public function where($where){
		$arg = func_get_args();
		$this->where->merge($arg);
		return $this;
	}
	
	/**
	 * store the order clause using the provided $order parameter
	 *
	 * This method accepts a variable number of arguments. 
	 * You create the order clause by providing the field names.
	 *
	 * <code>
	 * $request = new PMO_MyRequest;
	 * $request->from('MyTable')->where('active = 1')->order('lastname','firstname');
	 * </code>
	 *
	 * @param string $order,...  the order field name
	 */
	public function order($order){
		$arg = func_get_args();
		$this->order->merge($arg);
		return $this;
	}
	
	/**
	 * stores the having clause
	 *
	 * @param string $having,...	 the having clause
	 */
	public function having($having){
		$arg = func_get_args();
		$this->having->merge($arg);
		return $this;
	}

	/**
	 * stores the groupby clause
	 *
	 * @param string $groupby,...	 the groupby clause
	 */	
	public function groupby($groupby){
		$this->groupby = $groupby;
		return $this;		
	}
	
	/**
	 * stores the limit clause
	 *
	 * @param string|integer $limit	  the limit you want to impose to your query
	 */
	public function limit($limit){
		$this->limit = $limit;
		return $this;
	}	

	/**
	 * Build the actual SQL query using all the previoulsy 
	 * provided parts
	 *
	 * You should not need to use this directly. Since the 
	 * {@link PMO_MyController::objectquery()} takes a PMO_MyRequest object
	 * and use this method to call {@see PMO_MyController::query()}
	 *
	 * @todo					implement having
	 * @return string		the SQL query
	 */
	public function toString(){
							
		if($this->field->count() > 0){
			$query = "SELECT " . $this->field->implode(',');
		}else{
			$query = "SELECT *";
		}
							
		if($this->from->count() > 0){
			$query .= " FROM " . $this->from->implode(',');
		}else{
			throw new Exception("Error: FROM clause is empty");
		}
							
		if($this->where->count() > 0){
			$query .= " WHERE " . $this->where->implode(' AND ');
		}
		
		if(isset($this->groupby))
			$query .= " GROUP BY " . $this->groupby;
		
		if($this->having->count() > 0){
			$query .= " HAVING " . $this->having->implode(' AND ');
		}		

		if(count($this->limit) > 0){
			$query .= " LIMIT " . $this->limit;
		}
		
		return $query;
	}
	
	/**
	 * resets the PMO_MyRequest object so that it can reused
	 */
	public function reset(){
		$this->field = new PMO_MyArray();
		$this->from = new PMO_MyArray();
		$this->where = new PMO_MyArray();
		$this->order = new PMO_MyArray();
		$this->having = new PMO_MyArray();
		$this->groupby = new PMO_MyArray();	
	}
		
	public function getLinked(){
		foreach($this->from as $tablename){
			$table = PMO_MyTable::factory($tablename);
			$fk = $table->getFk();

			if($fk->count() > 0)
				foreach($fk as $column=>$value){
					foreach($value['Fk'] as $table2=>$column2){
						$this->from($table2);
						$this->where($table->getTableName().".".$column." = ".$table2.".".$column2);
					}
				}
		}
		return $this;
	}
	
}

?>
