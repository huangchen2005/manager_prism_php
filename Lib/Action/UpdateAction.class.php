<?php
class CustomAction extends Action
{

	public function insert()
	{
		$Custom = D('custom');
		if($Custom->create())
		{
			$result = $Custom->add();
			if($result)
			{
				$this->success('添加成功');
			}
			else
			{
				$this->error('添加失败');
			}
		}
		else
		{
			$this->error($Custom->getError());
		}
	}

	public function read($id=0)
	{
		$Custom = M('custom');
		$this->custom = $Custom->find($id);
		$this->display();
	}


	public function update()
	{
		$Custom = D('custom');
		
		if($Custom->create())
		{
			$result = $Custom->save();
			if($result)
			{
				$this->success('修改成功');
			}
			else
			{
				$this->error('修改失败');
			}
		}
		else
		{
			$this->error($Custom->getError());
		}
	}

	public function del($id=0)
	{
		$Custom = M('custom');
		$result = $Custom->delete($id);
		if($result)
		{
			$this->success('删除成功');
		}
		else
		{
			$this->error('删除失败');
		}
	}
}
