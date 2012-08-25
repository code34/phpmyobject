<?php 
class PMO_MyTable_test extends PMO_MyTable{

	protected $table_name = 'test';
	protected $table_attribute = Array(
				'one'=> Array(
				'Field'=> 'one'
				,'Type'=> 'string'
				,'Null'=> 'NO'
				,'Key'=> 'PRI'
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'two'=> Array(
				'Field'=> 'two'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> ''
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				);

	protected $table_classname = NULL;

}
?>