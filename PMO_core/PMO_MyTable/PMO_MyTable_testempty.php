<?php 
class PMO_MyTable_testempty extends PMO_MyTable{

	protected $table_name = 'testempty';
	protected $table_attribute = Array(
				'id'=> Array(
				'Field'=> 'id'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> 'PRI'
				,'Default'=> ''
				,'Extra'=> 'auto_increment'
				,'Perm'=> 'rw'
				)
				,'pseudo'=> Array(
				'Field'=> 'pseudo'
				,'Type'=> 'string'
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