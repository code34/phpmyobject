<?php 
class PMO_MyTable_staff extends PMO_MyTable{

	protected $table_name = 'staff';
	protected $table_attribute = Array(
				'staff_id'=> Array(
				'Field'=> 'staff_id'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> 'PRI'
				,'Default'=> ''
				,'Extra'=> 'auto_increment'
				,'Perm'=> 'rw'
				)
				,'first_name'=> Array(
				'Field'=> 'first_name'
				,'Type'=> 'string'
				,'Null'=> 'NO'
				,'Key'=> ''
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'last_name'=> Array(
				'Field'=> 'last_name'
				,'Type'=> 'string'
				,'Null'=> 'NO'
				,'Key'=> ''
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'address_id'=> Array(
				'Field'=> 'address_id'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> 'MUL'
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'email'=> Array(
				'Field'=> 'email'
				,'Type'=> 'string'
				,'Null'=> 'YES'
				,'Key'=> ''
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'store_id'=> Array(
				'Field'=> 'store_id'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> 'MUL'
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'active'=> Array(
				'Field'=> 'active'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> ''
				,'Default'=> '1'
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'username'=> Array(
				'Field'=> 'username'
				,'Type'=> 'string'
				,'Null'=> 'NO'
				,'Key'=> ''
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'password'=> Array(
				'Field'=> 'password'
				,'Type'=> 'string'
				,'Null'=> 'YES'
				,'Key'=> ''
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