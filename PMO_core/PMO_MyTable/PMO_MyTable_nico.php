<?php 
class PMO_MyTable_nico extends PMO_MyTable{

	protected $table_name = 'nico';
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
				,'nom'=> Array(
				'Field'=> 'nom'
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