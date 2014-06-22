<?php
class SendMailAction extends Action
{
	public function search()
	{
		$Custom = M('custom');
		$this->custom = $Custom->select();
		$this->display();
    	}
}
