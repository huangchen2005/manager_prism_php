<?php
class MachineAction extends Action
{
        public function search()
        {
                $Model = new Model();
                $this->machine = $Model->query("select  TIMESTAMPDIFF(day,curdate(),lis_ed_time) as stat,machine.id,machine.sn,machine.machine_name,machine.eth0_ip,machine.visit_ip,machine.pro_version,machine.lis_bg_time,machine.lis_ed_time,custom.custom_name,machine.belong_to,machine.machine_type from machine,custom where machine.custom_id=custom.id order by machine.machine_name;");
                $this->display();
        }

	public function search_ma()
	{
		$ma_name=$this->_post('machine_name');
                $Model = new Model();
                $this->machine = $Model->query("select  TIMESTAMPDIFF(day,curdate(),lis_ed_time) as stat,machine.id,machine.sn,machine.machine_name,machine.eth0_ip,machine.visit_ip,machine.pro_version,machine.lis_bg_time,machine.lis_ed_time,custom.custom_name,machine.belong_to,machine.machine_type from machine,custom where machine.custom_id=custom.id and machine.machine_name like '%$ma_name%';");
                $this->display();
	}

	public function add()
	{
		$Model = new Model();
		$this->custom = $Model->query("select id,custom_name from custom");

/*
		$conf_key_count = $Model->query("select count(*) as count from (select id from config group by config_key)t1;");
		$this->conf_key = $Model->query("select config_key from config group by config_key;");
		$key_count = count($this->conf_key);


		for($i=0;$i<$key_count;$i++)
		{
			$tmp_key=$this->conf_key[$i]["config_key"];
			$tmp_value=$Model->query("select config_value from config where config_key=\"$tmp_key\"");
			$value_count = count($tmp_value);
			for($j=0;$j<$value_count;$j++)
			{
				$conf_value["$tmp_key"][$j] = $tmp_value[$j]["config_value"];
			}
	
		}
		$this->assign("conf_value",$conf_value);
*/
		$this->display();
	}

	public function insert()
	{
                $Machine = D('machine');
                if($Machine->create())
                {
                        $result = $Machine->add();
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
                        $this->error($Machine->getError());
                }	
	}

        public function detail($id=0)
        {
                $Model = new Model();
                $this->machine = $Model->query("select machine.id as id,machine.sn,machine.machine_name,machine.eth0_ip,machine.visit_ip,machine.pro_version,machine.lis_bg_time,machine.lis_ed_time,custom.custom_name,machine.belong_to,machine.machine_type,machine.conf_path from machine,custom where machine.custom_id=custom.id and machine.id=$id;");
                $this->display();
        }


        public function read($id=0)
        {
                $Model = new Model();
                $this->machine = $Model->query("select machine.custom_id,machine.id,machine.sn,machine.machine_name,machine.eth0_ip,machine.visit_ip,machine.pro_version,machine.lis_bg_time,machine.lis_ed_time,custom.custom_name,machine.belong_to,machine.machine_type,machine.conf_path from machine,custom where machine.custom_id=custom.id and machine.id=$id;");
                $this->custom = $Model->query("select id,custom_name from custom");
                $this->display();
        }

        public function update_read($id=0)
        {
                $Model = new Model();
                $this->machineup = $Model->query("select id,machine_id,update_time,update_pro,svn_info,ps from machine_update where id=$id;");
                $this->display();
        }

        public function error_read($id=0)
        {
                $Model = new Model();
                $this->machineerr = $Model->query("select id,machine_id,error_time,error_pro,svn_info,ps from machine_error where id=$id;");
                $this->display();
        }

	public function update()
	{
                $Machine = D('machine');

                if($Machine->create())
                {
                        $result = $Machine->save();
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
                        $this->error($Machine->getError());
                }	
	}

	public function machine_update()
	{
                $MachineUp = D('machine_update');

                if($MachineUp->create())
                {
                        $result = $MachineUp->save();
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
                        $this->error($Machine->getError());
                }	
	}

	public function machine_error()
	{
                $MachineErr = D('machine_error');

                if($MachineErr->create())
                {
                        $result = $MachineErr->save();
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
                        $this->error($Machine->getError());
                }	
	}
        public function del($id=0)
        {
                $Machine = M('machine');
                $result = $Machine->delete($id);
                if($result)
                {
                        $this->success('删除成功');
                }
                else
                {
                        $this->error('删除失败');
                }
        }

	public function build($id=0)
	{
                $Model = new Model();
                $this->machine = $Model->query("select id,machine_name,visit_ip,conf_path from machine where id=$id;");
		$this->version = $Model->query("select * from pro_version;");
		$this->pro = $Model->query("select pro_name,pro_value from program;");
                $this->display();
	}

	public function build_detail($id=0)
	{
                $Model = new Model();
                $this->build = $Model->query("select machine_id as id,tm_base,version,pro,status,svn_info from build where machine_id=$id order by tm_base;");
                $this->display();
	}

	public function compile()
	{
                $Model = new Model();
		$machine_id=$this->_post('machine_id');
                $this->machine = $Model->query("select id,machine_name,visit_ip,conf_path from machine where id=$machine_id;");
		$this->conf_path = $this->machine[0]["conf_path"];
		$this->version=$this->_post('version');
		$this->pro=$this->_post('pro');
		$category=$Model->query("select pro_belong_to from pro_version where pro_version_name=\"$this->version\"");
		$this->category=$category[0]["pro_belong_to"];
		$this->svn_info = exec("sudo /var/ftp/packs/build.sh $this->category $this->version $this->pro $this->conf_path",$res,$ret);
		#$this->svn_info = exec("sudo /var/ftp/packs/build.sh trunk trunk all_http $this->conf_path",$res,$ret);
		$array_pr=explode('/',$this->conf_path);
		$this->pr_name=$array_pr[count($array_pr)-1];

		$this->ret=$ret;
		if ($this->ret == 0)
		{
			$this->re="编译成功";
		}
		else
		{
			$this->re="编译失败";
		}
		$tm_base=date('Y-m-d H:i:s',time());
		//$Model->execute("insert into build (tm_base,machine_id,version,pro,status,svn_info) values (\"$tm_base\",$this->machine_id,\"$this->version\",\"$this->pro\",\"$this->result\",\"$this->svn_info\")");
		$Model->execute("insert into build (tm_base,machine_id,version,pro,status,svn_info) values (\"$tm_base\",\"$machine_id\",\"$this->version\",\"$this->pro\",\"$this->re\",\"$this->svn_info\")");

		$this->display();
	}

/*
	public function send()
	{
                $Model = new Model();
		$this->machine_id=$this->_post('machine_id');
		$this->version=$this->_post('version');
		$this->pro=$this->_post('pro');
		$category=$Model->query("select pro_belong_to from pro_version where pro_version_name=\"$this->version\"");
		$this->category=$category[0]["pro_belong_to"];
		$this->visit_ip=$this->_post('visit_ip');
		$svn_info=exec("/var/ftp/packs/send_new.sh $this->category $this->version $this->pro $this->visit_ip",$res,$ret);
		//$svn_info=exec("/var/ftp/packs/send_new.sh trunk trunk tools 192.168.1.77",$res,$ret);
		$this->ret=$ret;
		if ($ret == 0)
			$this->result="更新成功";
		else
			$this->result="更新失败";
		$this->display();
	}
*/
	public function search_update($id=0)
	{
                $Model = new Model();
		$this->machine_id=$this->_get('id');
                $this->update_info = $Model->query("select  machine_update.id,machine_id,machine_name,update_time,update_pro,svn_info,ps from machine,machine_update where machine.id = machine_update.machine_id and machine_id=$id order by update_time;");
                $this->display();
	}


	public function add_update($id=0)
	{
		$this->machine_id=$this->_get('id');
		$Model = new Model();
		$machine=$Model->query("select machine_name from machine where id=$id");
		$this->machine_name=$machine[0]["machine_name"];
		$this->display();
	}

	public function add_update1($id=0)
	{
                $MachineUp = D('machine_update');
                if($MachineUp->create())
                {
                        $result = $MachineUp->add();
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
                        $this->error($Machine->getError());
                }
	}

        public function del_update($id=0)
        {

                $MachineUp = M('machine_update');
                $result = $MachineUp->delete($id);
                if($result)
                {
                        $this->success('删除成功');
                }
                else
                {
                        $this->error('删除失败');
                }
        }
	
	public function search_error($id=0)
	{
                $Model = new Model();
		$this->machine_id=$this->_get('id');
                $this->error_info = $Model->query("select  machine_error.id,machine_id,machine_name,error_time,error_pro,svn_info,ps from machine,machine_error where machine.id = machine_error.machine_id and machine_id=$id order by error_time;");
                $this->display();
	}


	public function add_error($id=0)
	{
		$this->machine_id=$this->_get('id');
		$Model = new Model();
		$machine=$Model->query("select machine_name from machine where id=$id");
		$this->machine_name=$machine[0]["machine_name"];
		$this->display();
	}

	public function add_error1($id=0)
	{
                $MachineErr = D('machine_error');
                if($MachineErr->create())
                {
                        $result = $MachineErr->add();
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
                        $this->error($Machine->getError());
                }
	}

        public function del_error($id=0)
        {

                $MachineErr = M('machine_error');
                $result = $MachineErr->delete($id);
                if($result)
                {
                        $this->success('删除成功');
                }
                else
                {
                        $this->error('删除失败');
                }
        }

	public function get_lic($id=0)
	{
                $Model = new Model();
                $this->machine = $Model->query("select machine.custom_id,machine.id,machine.sn,machine.machine_name,machine.eth0_ip,machine.visit_ip,machine.pro_version,machine.lis_bg_time,machine.lis_ed_time,custom.custom_name,machine.belong_to,machine.machine_type,machine.conf_path from machine,custom where machine.custom_id=custom.id and machine.id=$id;");
                $this->display();
	}

	public function get_lic1($id=0)
	{
                $Model = new Model();
                $this->machine = $Model->query("select id,sn from machine where id=$id;");
		$this->machine_sn = $this->machine[0]["sn"];
		$this->lis_bg_time = $this->_post('lis_bg_time');
		$this->lis_ed_time = $this->_post('lis_ed_time');
		exec("sudo /var/ftp/packs/di_rsa/get_key.sh $this->lis_bg_time $this->lis_ed_time $this->machine_sn",$res,$ret);
                $this->display();
	}

	public function License()
	{
		$this->display();
	}

	public function get_lic_new($id=0)
	{
                $Model = new Model();
                $this->machine = $Model->query("select machine.custom_id,machine.id,machine.sn,machine.machine_name,machine.eth0_ip,machine.visit_ip,machine.pro_version,machine.lis_bg_time,machine.lis_ed_time,custom.custom_name,machine.belong_to,machine.machine_type,machine.conf_path from machine,custom where machine.custom_id=custom.id and machine.id=$id;");
                $this->display();
	}

	public function get_lic1_new($id=0)
	{
                $Model = new Model();
                $this->machine = $Model->query("select machine.id,machine.sn,custom.custom_name from machine,custom  where machine.custom_id=custom.id and machine.id=$id;");
		$this->machine_id = $this->machine[0]["id"];
		$this->machine_sn = $this->machine[0]["sn"];
		$this->custom_name = $this->machine[0]["custom_name"];
		$this->accounttype = $this->_post('accounttype');
		$this->detecttype = $this->_post('detecttype');
		$this->throughtput = $this->_post('throughtput');
		$this->lis_bg_time = $this->_post('lis_bg_time');
		$this->lis_ed_time = $this->_post('lis_ed_time');
		exec("sudo /var/ftp/packs/di_rsa_new/get_key.sh $this->lis_bg_time $this->lis_ed_time $this->machine_sn $this->custom_name $this->accounttype $this->detecttype $this->throughtput",$res,$ret);
                $this->ret=$ret;

        	$Model->execute("insert into build_license (machine_id,custom_name,accounttype,detecttype,throughtput,lis_bg_time,lis_ed_time,build_ret) values ($this->machine_id,\"$this->custom_name\",\"$this->accounttype\",\"$this->detecttype\",\"$this->throughtput\",\"$this->lis_bg_time\",\"$this->lis_ed_time\",$this->ret)");
                $this->display();
	}

	public function search_lic_new($id=0)
	{

		$Model = new Model();
		//$this->lic = $Model->query("select machine_id,machine_name,custom_name,build_time,accounttype detectype,throughtput,build_license.lis_bg_time,build_license.lis_ed_time,build_ret from build_license,machine where machine_id = machine.id and machine_id=$id;");
		$this->lic = $Model->query("select machine_id as id,machine_name,custom_name,build_time,accounttype, detecttype,throughtput,build_license.lis_bg_time,build_license.lis_ed_time,build_ret from build_license,machine where machine_id = machine.id and machine_id=$id;");
                $this->display();



	}

}
?>
