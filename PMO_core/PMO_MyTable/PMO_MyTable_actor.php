<?php 
class PMO_MyTable_actor extends PMO_MyTable{

	protected $table_name = 'actor';
	protected $table_attribute = Array(
				'actor_id'=> Array(
				'Field'=> 'actor_id'
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