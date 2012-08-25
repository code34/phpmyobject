<?php
/**
 * This file contains the test class used to check for PhpMyObject basic sanity.
 *
 * This file is part of the PhpMyObject project.
 * 
 * For questions, help, comments, discussion, etc., please join our
 * forum at {@link http://www.developpez.net/forums/forumdisplay.php?f=770} 
 * or our mailing list at {@link http://groups.google.com/group/pmo-dev}.
 *
 * PhpMyProject is free software: you can redistribute it and/or modify
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
 * @author     Nicolas Boiteux <nicolas_boiteux@yahoo.fr>
 * @link       http://pmo.developpez.com/
 * @since      PhpMyObject v0.1x
 * @version    $Revision:$
 * @version    $revision:$
 * @version    $Id:$
 * @version    $Id: $
 * @version    $id:$
 * @version    $id: $
 * @copyright  Copyright (C) 2007-2008 Nicolas Boiteux 
 * @license    GPLv3 {@link http://www.gnu.org/licenses/gpl}
 * @filesource
 */ 

/** requires the PMO_MyController class and the config file */
require_once(dirname(__FILE__).'/PMO_core/PMO_MyController.php');
require_once(dirname(__FILE__).'/your_config.php');

/**
 * This class tests PhpMyObject basic sanity.
 * @package PhpMyObject
 */
 class test {

 	/**
 	 * select de 5 enregistrements + fetch
 	 */
	public function test1(){
		echo "test 1 : select de 5 enregistrements + fetch<br>";	

		$controller = new PMO_MyController();
		$map = $controller->query("SELECT * FROM rental,inventory,customer,staff 
		WHERE rental.inventory_id=inventory.inventory_id
		AND customer.customer_id=rental.customer_id
		AND staff.staff_id=rental.staff_id
		limit 5;");		
		
		while ($result = $map->fetch()){
			$test = $result['rental']->rental_id;
		}	
	}
	
	public function test2(){
		echo "test 2 : factory d'un objet PMO_MyTable<br>";
		$table = PMO_MyTable::factory('country');
		if(get_class($table) == "PMO_MyTable_country")
			echo("class is ok!<br>");
		else
			die("Failed!<br>");
		//print '<pre>';print_r($table);print '</pre>';
	}
	
	/**
	 * test 3 : load object
	 */
	public function test3(){
		echo "test 3 : load object<br>";
		PMO_MyConfig::factory()->set('PMO_MyDbms.LOG', 'TRUE');
		$object = PMO_MyObject::factory('inventory');
		$object->inventory_id = 1;
		$object->load();
		print_r(PMO_MyDbms::factory()->getLog());
		echo("<br>");
		//print '<pre>';print_r($object);print '</pre>';
	}
	
 	public function test4(){
		echo "test 4 : select de 1 enregistrement<br>";
		$controller = new PMO_MyController();
		$map = $controller->query("SELECT * FROM rental,inventory,customer,staff 
		WHERE rental.inventory_id=inventory.inventory_id
		AND customer.customer_id=rental.customer_id
		AND staff.staff_id=rental.staff_id limit 1;");		

		$result = $map->fetch();
		$rental = $result['rental'];
	}	
	
	/**
	 * factory + load + save object
	 */
	public function test5(){
		echo "test 5 : factory + load + save object<br>";
		$object = PMO_MyObject::factory('inventory');
		$object->inventory_id = 1;
		$object->load();
		$object->last_update = "2006-02-15 05:09:18";
		$object->save();
	}
	
	/**
	 * toXML()
	 */
	public function test6(){
		echo "test 6 : toXML()<br>";
		$object = PMO_MyObject::factory('inventory');
		$object->inventory_id = 1;
		$object->load();
		print_r($object->toXml("nothing")."<br>");
		
	}
	
	/**
	 * getTable
	 */
 	public function test7(){
		echo "test 7 : getTable<br>";
		$object = PMO_MyObject::factory('inventory');
		$table = $object->getTable();

		if($table instanceof PMO_MyTable_inventory)
			echo("done!<br>");
		else
			die("Failed!<br>");		
		
		//print '<pre>';print_r($object);print '</pre>';
	}

	/**
	 * getMapByTable
	 */
 	public function test8(){
		echo "test 8 : getMapByTable<br>";
		$controller = new PMO_MyController();
		$map = $controller->query("SELECT * FROM rental,inventory,customer,staff 
		WHERE rental.inventory_id=inventory.inventory_id
		AND customer.customer_id=rental.customer_id
		AND staff.staff_id=rental.staff_id limit 1;");		
		
		$newmap = $map->getMapByTable('rental');
		
		if($newmap instanceof PMO_MyMap)
			echo("done!<br>");
		else
			die("Failed!<br>");		
		//print '<pre>';print_r($newmap);print '</pre>';
	}

	/**
	 * getMapByValue
	 */
 	public function test9(){
		echo "test 9 : getMapByValue<br>";
		$controller = new PMO_MyController();
		$map = $controller->query("SELECT * FROM rental,inventory,customer,staff 
		WHERE rental.inventory_id=inventory.inventory_id
		AND customer.customer_id=rental.customer_id
		AND staff.staff_id=rental.staff_id limit 1;");		
		
		$newmap = $map->getMapByValue('rental', 'rental_id', 4863);
		
		if($newmap instanceof PMO_MyMap)
			echo("done!<br>");
		else
			die("Failed!<br>");
		//print '<pre>';print_r($map);print '</pre>';
	}		
	
	/**
	 * changement de driver pdo mysql on another base
	 */
  	public function test10(){
		echo "test 10 : changement de driver pdo on another base<br>";
		$pdo = new PDO("mysql:host=localhost;dbname=test", "pmo", "pmo");
		$controller = new PMO_MyController($pdo);
		$map = $controller->query("SELECT * FROM address ;");

		if($map instanceof PMO_MyMap)
			echo("done!<br>");
		else
			die("Failed!<br>");
		//print '<pre>';print_r($map);print '</pre>';
	}

	/**
	 * getObjectByObject
	 */
  	public function test11(){
		echo "test 11 : getObjectByObject<br>";

		PMO_MyDbms::killInstance();
		$config = PMO_MyConfig::factory();
		$config->set('PMO_MyDbms.DRIVER','mysql');
		$config->set('PMO_MyDbms.PDODRIVER','mysql');
		$config->set('PMO_MyDbms.HOST','localhost');
		$config->set('PMO_MyDbms.USER','pmo');
		$config->set('PMO_MyDbms.PASS','pmo');
		$config->set('PMO_MyDbms.BASE','sakila');		
		
		$controller = new PMO_MyController();
		$map = $controller->query("SELECT * FROM rental,inventory,customer,staff 
		WHERE rental.inventory_id=inventory.inventory_id
		AND customer.customer_id=rental.customer_id
		AND staff.staff_id=rental.staff_id limit 1;");		
		
		$object = PMO_MyObject::factory('rental');
		$object->rental_id = 4863;
		$goodobject = $map->getObjectByObject($object);
		if($goodobject instanceof PMO_MyObject)
			echo("done!<br>");
		else
			die("Failed!<br>");
		//print '<pre>';print_r($goodobject);print '</pre>';
	}

	/**
	 * getObjectByValue
	 */
 	public function test12(){
		echo "test 12 : getObjectByValue<br>";
		$controller = new PMO_MyController();
		$map = $controller->query("SELECT * FROM rental,inventory,customer,staff 
		WHERE rental.inventory_id=inventory.inventory_id
		AND customer.customer_id=rental.customer_id
		AND staff.staff_id=rental.staff_id limit 1;");		
		
		$object = $map->getObjectByValue('rental', 'rental_id', 4863);
		if($object instanceof PMO_MyObject)
			echo("done!<br>");
		else
			die("Failed!<br>");
		//print '<pre>';print_r($object);print '</pre>';
 	}			

	/**
	 * getMapByObjectByValue
	 */
 	public function test13(){
		echo "test 13 : getMapByObjectByValue<br>";
		$controller = new PMO_MyController();
		$map = $controller->query("SELECT * FROM rental,inventory,customer,staff 
		WHERE rental.inventory_id=inventory.inventory_id
		AND customer.customer_id=rental.customer_id
		AND staff.staff_id=rental.staff_id limit 1;");		

		$object = PMO_MyObject::factory('rental');
		$object->rental_id = 4863;		
		$newobject = $map->getMapByObjectByValue($object);
		if($newobject instanceof PMO_MyMap)
			echo("done!<br>");
		else
			die("Fail!<br>");
		//print '<pre>';print_r($newobject);print '</pre>';
	}		
	
	/**
	 * classloader
	 */
	public function test14(){
		echo "test 14 : classloader<br>";
		$object = PMO_MyObject::factory('film_actor');
		if($object instanceof film_actor)
			echo("class is ok!<br>");
		else
			die("Fail!<br>");
	}

	/**
	 * getperm + setperm
	 */
 	public function test15(){
		echo "test 15 : getperm + setperm<br>";	

		$controller = new PMO_MyController();
		$map = $controller->query("SELECT * FROM rental,inventory,customer,staff 
		WHERE rental.inventory_id=inventory.inventory_id
		AND customer.customer_id=rental.customer_id
		AND staff.staff_id=rental.staff_id
		limit 5;");		
		
		$result = $map->fetch();
		$table = $result['rental']->getTable();
		echo $table->getPerm('inventory_id');
		$table->setPerm('inventory_id', 'rw');
		echo($table->getPerm('inventory_id')."<br>");
	}	

	/**
	 * select lazy loading
	 */
 	public function test16(){
		echo "test 16 : select lazy loading<br>";	

		$controller = new PMO_MyController();
		$map = $controller->query("SELECT rental_id,sum(customer_id),rental_date FROM rental GROUP BY customer_id ");		
		
		$result = $map->fetch();
		if($result['collector']->get('sum(customer_id)'))
			echo("Ok<br>");
		else
			die("Failed<br>");
		//print '<pre>';print_r($result['collector']);print '</pre>';
	}	

	/**
	 * save + delete
	 */
 	public function test17(){
		echo "test 17 : save + delete <br>";

		PMO_MyDbms::killInstance();
		$config = PMO_MyConfig::factory();
		$config->set('PMO_MyDbms.DRIVER','mysql');
		$config->set('PMO_MyDbms.PDODRIVER','mysql');
		$config->set('PMO_MyDbms.HOST','localhost');
		$config->set('PMO_MyDbms.USER','pmo');
		$config->set('PMO_MyDbms.PASS','pmo');
		$config->set('PMO_MyDbms.BASE','sakila');
		
		$object = PMO_MyObject::factory('nico');
		$object->nom = "ville";
		$object->save();
		
		$controller = new PMO_MyController();
		$map = $controller->query("SELECT id,nom FROM nico LIMIT 1;");		
		
		$result = $map->fetch();
		$object = $result['nico'];
		$object->delete();		
	}	
	
	/**
	 * raw query
	 */
	public function test18(){
		echo "test 18 : raw query<br>";	
		
		$pdo = new PDO("mysql:host=localhost;dbname=sakila", "pmo", "pmo");		
		
		$controller = new PMO_MyController($pdo);
				
		$map = $controller->rawquery("SELECT customer_id,rental_date FROM rental LIMIT 1;");		
		
		$result = $map->fetchArray();
		if(is_array($result))
			echo("done!<br>");
		else
			die("Fail!<br>");
		//print '<pre>';print_r($result);print '</pre>';		
	}

 	/**
	 * insert with autoincrement
	 */
	public function test19(){
		echo "test 19 verbose + autoincrement: <br/>";	
		PMO_MyConfig::factory()->set('PMO_MyDbms.LOG',TRUE);
		$objet = PMO_MyObject::factory('nico');
		$objet->nom = "toto";
		$objet->save();
		if(count(PMO_MyDbms::factory()->getLog() > 0)){
			echo("done! </br>");
		}else{
			die("Fail! </br>");
		}
		//print '<pre>';print_r($result);print '</pre>';		
	}	
	
	/**
	 * select dans une table vide
	 */
	public function test20(){
		echo "test 20 : select dans une table vide<br>";	

		$controller = new PMO_MyController();
		try{
			$pseudo = "wrongpseudo";
			$map = $controller->query("SELECT * FROM testempty WHERE pseudo = '".$pseudo."' ;");
			die("Fail!<br>");
		}catch(Exception $e){
			echo("done!<br>");
		}
	}
	
 	/**
	 * select de 1 enregistrement avec factory sans utiliser la primary key
	 */
	public function test21(){
		echo "test 21 : select et creation d'un enregistrement aléatoire<br>";	

		$pseudo = "pseudo".rand(1,1000);
		$objet = PMO_MyObject::factory('testempty');
		$objet->pseudo = $pseudo;
		try{
			$objet->load();
		}catch(Exception $e){
			$objet->save();
		}
	}	
	
	/**
	 * factory de PMO_MyConfig et affichage des paramêtres de configuration
	 */
	public function test22(){
		echo "test 22 : factory de PMO_MyConfig et affichage des paramêtres de configuration <br>";	
		$config = PMO_MyConfig::factory();
		$config->show();
	}
	
	/**
	 *  utilisation pdo externe Sqlite
	 */
	public function test23(){
	echo "<br>test 23 : utilisation pdo externe Sqlite <br>";
	$database = dirname(__FILE__).'/test2.db';	
	$pdo = new PDO("sqlite:".$database);
	$controller = new PMO_MyController($pdo);
     $map = $controller->query("SELECT * FROM test LIMIT 1 ;");

      if($map instanceof PMO_MyMap)
         echo("done!<br>");
      else
         die("Failed!<br>");

	//print '<pre>';print_r($map);print '</pre>';
	}

 	/**
	 * utilisation du transactionnel insert + save
	 */
 	public function test24(){
		echo "test 24 : utilisation du transactionnel insert <br/>";

		PMO_MyDbms::killInstance();
		$config = PMO_MyConfig::factory();
		$config->set('PMO_MyDbms.DRIVER','pdo');
		$config->set('PMO_MyDbms.PDODRIVER','mysql');
		$config->set('PMO_MyDbms.HOST','localhost');
		$config->set('PMO_MyDbms.USER','pmo');
		$config->set('PMO_MyDbms.PASS','pmo');
		$config->set('PMO_MyDbms.BASE','sakila');
		
		$dbms = PMO_MyDbms::factory();
		
		$dbms->beginTransaction();	
		$object = PMO_MyObject::factory('transaction');
		$object->nom = "testrollback";
		$object->save();
		if($dbms->commit())
			echo("done ! <br/>");
		else
			die("Failed ! <br/>");
	}
 
	/**
	 * suppresion d'une classe persistante PMO_MyTable et regénération
	 */
	public function test25(){
	echo "test 25 : suppresion d'une classe persistante PMO_MyTable et regénération <br/>";
	
	$filename = 'PMO_Core/PMO_MyTable/PMO_MyTable_nico.php';
	
	if(file_exists($filename))
		unlink($filename);

	$controller = new PMO_MyController();
	$map = $controller->query("SELECT id,nom FROM nico LIMIT 1;");		
		
	$result = $map->fetch();
	$object = $result['nico'];
	}
	
	/**
	 * utilisation de PMO_MyRequest pour  select sur 1 table
	 */
	public function test26(){
		echo "test 26 : select sur film actor avec PMO_MyRequest <br/>";
		$request = new PMO_MyRequest();
		$request->from('film_actor')->limit('10');
		
		$controller = new PMO_MyController();
		$map = $controller->objectquery($request);
		
		if($map instanceof PMO_MyMap)
         echo("done!<br>");
      	else
         die("Failed!<br>");
	}

    /**
    * utilisation de PMO_MyRequest pour select sur plusieurs tables
    */
	public function test27(){
      echo "test 27 : select sur plusieurs tables avec PMO_MyRequest</br>";
      $request = new PMO_MyRequest;
      $request->field('last_name', 'first_name', 'title', 'film_actor.actor_id', 'film.film_id')
              ->from('actor', 'film', 'film_actor')
              ->where('actor.actor_id = film_actor.actor_id', 'film.film_id = film_actor.film_id')
              ->order('last_name', 'first_name', 'title')
              ->limit('10');
              
	$controller = new PMO_MyController;	
	$map = $controller->objectquery($request);
		
      if ($map instanceOf PMO_MyMap)
         echo "done!<br>";
      else
         die("Failed!<br>");
   }	

	/**
	 * utilisation de setFk + select sur plusieurs tables avec PMO_MyRequest
	 */  
	public function test28(){
      echo "test 28 : select sur plusieurs tables avec PMO_MyRequest</br>";
      
		$table = PMO_MyTable::factory('film_actor');
		$table->setFk("actor_id", array("actor"=>"actor_id"));
		$table->setFk("film_id", array("film"=>"film_id"));      
      
		$request = new PMO_MyRequest;
		$request->field('last_name', 'first_name', 'title', 'film_actor.actor_id', 'film.film_id')
			->from('film_actor')
			->getLinked()
			->order('last_name', 'first_name', 'title')
			->limit('10');
		
		$controller = new PMO_MyController;	
		$map = $controller->objectquery($request);
		
		if ($map instanceOf PMO_MyMap)
			echo "done!<br>";
		else
			die("Failed!<br>");
	}		

	/**
	 * count du nombre d'objets renvoyé dans la map
	 */	
  	public function test29(){
		echo "test 29 count des rows<br>";
		$controller = new PMO_MyController();
		$map = $controller->query("SELECT * FROM rental,inventory,customer,staff 
		WHERE rental.inventory_id=inventory.inventory_id
		AND customer.customer_id=rental.customer_id
		AND staff.staff_id=rental.staff_id limit 5;");		
		
		$count = 0;
		
		while($result = $map->fetch()){
			$count++;
		}
		if($count == 4)
			die("failed ! </br>");
		else
			echo("ok! </br>");
		//print '<pre>';print_r($newmap);print '</pre>';
	}

	/**
	 * fetch par Table
	 */
   	public function test30(){
		echo "test 30 fetch par table<br>";
		$controller = new PMO_MyController();
		$map = $controller->query("SELECT * FROM rental,inventory,customer,staff 
		WHERE rental.inventory_id=inventory.inventory_id
		AND customer.customer_id=rental.customer_id
		AND staff.staff_id=rental.staff_id limit 1;");		
		
		$count = 0;
		while($table = $map->fetchTable('rental')){
			if($table instanceof PMO_Object)
				echo "Ok!</br>";
			else
				die("Failed!</br>");
		}
		
		
		//print '<pre>';print_r($map);print '</pre>';
	}	
	
	/**
	 * test memcache
	 */
	public function test31(){
		//echo("test 31: Memcache <br/>");
		//$memcache = new Memcache;
		//$memcache->connect('192.168.0.2', 11211) or die ("Connexion impossible");

		//$object = PMO_MyObject::factory('film_actor');
		//$object->actor_id = 1;
		
		//$memcache->set('actor_id1', $object, false, 10) or die ("Echec de la sauvegarde des données sur le serveur");
		
		//$object2 = $memcache->get('actor_id1');
		
		//if($object2 instanceof film_actor)
		//	echo("Ok<br>");
		//else
		//	die("Failed!<br>");
		
		//$memcache->close();		
	}
	
	/**
	 * test PMO_MyArray
	 */
	public function test32(){
		$test = new PMO_MyArray();
		$test->add('to', 'tooo');
		$test->add('ti', 'tiiii');
		
		echo("Test 32: PMO_MyArray foreach<br>");
		foreach($test as $key=>$value)
			if(is_string($value))
				echo "Ok!<br/>";
			else
				die("Failed!<br/>");
		
		echo("Test 32: PMO_MyArray while<br>");
		while($result = $test->each())
			if(is_array($result))
				echo "Ok!<br/>";
			else
				die("Failed!<br/>");
		
		echo("Test 32: PMO_MyArray fetch<br>");		
		while($result = $test->fetch())
			if(is_string($value))
				echo "Ok!<br/>";
			else
				die("Failed!<br/>");		
	}
 }


// need to set this to true
$config = PMO_MyConfig::factory();
$config->set('PMO_MyTable.CLASS_WRITE_ON_DISK', TRUE);

$test = new test();
$time_start = microtime(true);

$test->test25();
$test->test1();
$test->test2();
$test->test3();
$test->test4();
$test->test5();
$test->test6();
$test->test7();
$test->test8();
$test->test9();
$test->test10();
$test->test11();
$test->test12();
$test->test13();
//$test->test14();
$test->test15();
$test->test16();
$test->test17();
$test->test18();
$test->test19();
$test->test20();
$test->test21();
$test->test22();
$test->test23();
$test->test24();
$test->test26();
$test->test27();
$test->test28();
$test->test29();
$test->test30();
$test->test31();
$test->test32();
	
		$time_end = microtime(true);
		$time = $time_end - $time_start;
		echo "Did it in $time seconds<br>"; 
			
?>
