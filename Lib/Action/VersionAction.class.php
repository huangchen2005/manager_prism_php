<?php
class VersionAction extends Action
{
	public function search()
	{
		$Version = M('pro_version');
		$this->version = $Version->select();
		$this->display();
    	}
	

	public function insert()
	{
  		$Model = new Model();
		$this->svn_addr=$this->_post('svn_addr');
		$this->from_svn_addr=$this->_post('from_svn_addr');

		$strArr=explode('/',$this->svn_addr);
		$len=count($strArr);
		$this->pro_version_name=$strArr[$len-1];
		$this->pro_belong_to=$strArr[$len-2];

   		$Model->execute("insert into pro_version (pro_version_name,pro_belong_to,svn_addr,from_svn_addr) values (\"$this->pro_version_name\",\"$this->pro_belong_to\",\"$this->svn_addr\",\"$this->from_svn_addr\")");

     		$this->svn = exec("sudo /var/ftp/packs/svn_cp.sh $this->from_svn_addr $this->svn_addr $this->pro_version_name $this->pro_belong_to",$res,$ret);

		$this->display();


	}

	public function read($id=0)
	{
		$Version = M('pro_version');
		$this->version = $Version->find($id);
		$this->display();
	}


	public function update()
	{
		$Version = D('pro_version');
		
		if($Version->create())
		{
			$result = $Version->save();
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
         	$Model = new Model();
                $this->svn_info = $Model->query("select svn_addr,pro_version_name,pro_belong_to from pro_version where id=$id;");
                $this->svn_addr = $this->svn_info[0]["svn_addr"];
                $this->pro_version_name = $this->svn_info[0]["pro_version_name"];
                $this->pro_belong_to = $this->svn_info[0]["pro_belong_to"];

	 	$this->svn = exec("sudo /var/ftp/packs/svn_rm.sh $this->svn_addr $this->pro_version_name $this->pro_belong_to",$res,$ret);

		$Version = M('pro_version');
		$result = $Version->delete($id);
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
