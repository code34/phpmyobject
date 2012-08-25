<?php 
class PMO_MyTable_rental extends PMO_MyTable{

	protected $table_name = 'rental';
	protected $table_attribute = Array(
				'rental_id'=> Array(
				'Field'=> 'rental_id'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> 'PRI'
				,'Default'=> ''
				,'Extra'=> 'auto_increment'
				,'Perm'=> 'rw'
				)
				,'rental_date'=> Array(
				'Field'=> 'rental_date'
				,'Type'=> 'string'
				,'Null'=> 'NO'
				,'Key'=> 'MUL'
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'inventory_id'=> Array(
				'Field'=> 'inventory_id'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> 'MUL'
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'customer_id'=> Array(
				'Field'=> 'customer_id'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> 'MUL'
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'return_date'=> Array(
				'Field'=> 'return_date'
				,'Type'=> 'string'
				,'Null'=> 'YES'
				,'Key'=> ''
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'staff_id'=> Array(
				'Field'=> 'staff_id'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> 'MUL'
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'last_update'=> Array(
				'Field'=> 'last_update'
				,'Type'=> 'string'
				,'Null'=> 'NO'
				,'Key'=> ''
				,'Default'=> 'CURRENT_TIMESTAMP'
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				);

	protected $table_classname = NULL;

}
?>