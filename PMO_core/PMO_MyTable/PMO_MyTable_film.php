<?php 
class PMO_MyTable_film extends PMO_MyTable{

	protected $table_name = 'film';
	protected $table_attribute = Array(
				'film_id'=> Array(
				'Field'=> 'film_id'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> 'PRI'
				,'Default'=> ''
				,'Extra'=> 'auto_increment'
				,'Perm'=> 'rw'
				)
				,'title'=> Array(
				'Field'=> 'title'
				,'Type'=> 'string'
				,'Null'=> 'NO'
				,'Key'=> 'MUL'
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'description'=> Array(
				'Field'=> 'description'
				,'Type'=> 'string'
				,'Null'=> 'YES'
				,'Key'=> ''
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'release_year'=> Array(
				'Field'=> 'release_year'
				,'Type'=> 'string'
				,'Null'=> 'YES'
				,'Key'=> ''
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'language_id'=> Array(
				'Field'=> 'language_id'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> 'MUL'
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'original_language_id'=> Array(
				'Field'=> 'original_language_id'
				,'Type'=> 'int'
				,'Null'=> 'YES'
				,'Key'=> 'MUL'
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'rental_duration'=> Array(
				'Field'=> 'rental_duration'
				,'Type'=> 'int'
				,'Null'=> 'NO'
				,'Key'=> ''
				,'Default'=> '3'
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'rental_rate'=> Array(
				'Field'=> 'rental_rate'
				,'Type'=> 'string'
				,'Null'=> 'NO'
				,'Key'=> ''
				,'Default'=> '4.99'
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'length'=> Array(
				'Field'=> 'length'
				,'Type'=> 'int'
				,'Null'=> 'YES'
				,'Key'=> ''
				,'Default'=> ''
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'replacement_cost'=> Array(
				'Field'=> 'replacement_cost'
				,'Type'=> 'string'
				,'Null'=> 'NO'
				,'Key'=> ''
				,'Default'=> '19.99'
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'rating'=> Array(
				'Field'=> 'rating'
				,'Type'=> 'string'
				,'Null'=> 'YES'
				,'Key'=> ''
				,'Default'=> 'G'
				,'Extra'=> ''
				,'Perm'=> 'rw'
				)
				,'special_features'=> Array(
				'Field'=> 'special_features'
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