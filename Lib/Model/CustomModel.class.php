<?php
class CustomModel extends Model
{
	protected $_validate = array(
		array('custom_name','require','客户名称必须'),
	);

}
?>
