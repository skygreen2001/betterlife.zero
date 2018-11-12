<?php
/**
 +--------------------------------------------------<br/>
 * 定时触发器<br/>
 * 参考Ecmall定时触发器的做法，和模拟实现Linux的Cron计划任务定时执行功能
 +--------------------------------------------------<br/>
 * @category betterlife
 * @package core.main
 * @author skygreen
 */
class Cron
{
	/* 配置 */
	public $_config = array();

	/* 任务列表 */
	private $_tasks  = null;

	/* 当前时间 */
	public $_now    = 0;
	private $_lock_fp = null;

	/**
	 * 启动运行计划任务定时执行
	 */
	public static function run()
	{
		$exitflag_file="unlock.cron";
		if (file_exists($exitflag_file)||file_exists(__DIR__.DS."core".DS."main".DS.$exitflag_file))
		{
			return;
		}
		register_shutdown_function(create_function('', '
			$cron = new Cron();//计划任务实例
			$cron->execute();//执行
		'));
	}

	/**
	 * 提供给工具类循环使用
	 */
	public static function run_once()
	{
		global $cron;
		if (!$cron){
			$cron = new Cron();//计划任务实例
		}else{
			$cron->_now = time();
		}
		$cron->execute();//执行
	}

	public function __construct($setting=array())
	{
		$this->Cron($setting);
	}
	public function Cron($setting)
	{
		$this->_now = time();   //以服务器当前时间为主
		$this->_config($setting);
	}

	/**
	 * 配置
	 * @param string $key 配置项名称
	 *        array  $key 配置项数组
	 * @param mixed  $value 配置项值
	 * @return void
	 */
	public function _config($key, $value = '')
	{
		if (is_array($key))
		{
			$this->_config = array_merge($this->_config, $key);
		}
		else
		{
			$this->_config[$key] = $value;
		}
	}

	/**
	 *    初始化任务
	 *
	 *    @author    Garbin
	 *    @return    void
	 */
	public function _init_tasks()
	{
		foreach (Gc::$module_names as $module_name)
		{
			$timerconfigpath=Gc::$nav_root_path."home".DS.$module_name.DS."src".DS."timertask".DS;
			if (file_exists($timerconfigpath."cron.config.xml"))
			{
				$this->_config['task_list'][$module_name]=$timerconfigpath."cron.config.xml";
			}
		}

		if (empty($this->_config['task_list'])||((count($this->_config['task_list']))<=0))
		{
			return;
		}else{
			foreach ($this->_config['task_list'] as $module_name=>$task_file) {
				$arrObjDatas=UtilXmlSimple::fileXmlAttributesToArray(Gc::$nav_root_path."home".DS.$module_name.DS."src".DS."timertask".DS."cron.config.xml");
				foreach ($arrObjDatas as $arrObjData) {
					if (array_key_exists("name",$arrObjData))
					{
						$key=$arrObjData["name"];
						unset($arrObjData["name"]);
						$this->_tasks[$key]=$arrObjData;
					}
					$this->_tasks[$key]["module_name"]=$module_name;
				}
			}
		}

		if ((empty($this->_tasks))||((count($this->_tasks))<=0))
		{
			return;
		}
		$update = false;
		foreach ($this->_tasks as $task => $config)
		{
			if (strtoupper(trim($config['status']))=="OPEN"){
				if (empty($config['due_time']))
				{
					$update = true;
					$this->_tasks[$task]['due_time'] = $this->get_due_time($config);
				}
			}
		}
		$update && $this->update();
	}

	private function execute_ignored()
	{
		set_time_limit(1800); //半个小时
		ignore_user_abort(true);//忽略用户退出
		$this->execute();
	}


	/**
	 *    执行
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    void
	 */
	public function execute()
	{
		$this->_init_tasks();
		/* 获取到期的任务列表 */
		$due_tasks = $this->get_due_tasks();
		/* 没有到期的任务 */
		if (empty($due_tasks))
		{
			return;
		}

		/* 执行任务 */
		$this->run_task($due_tasks);

		/* 更新任务列表 */
		$this->update_tasks($due_tasks);
	}

	/**
	 *    获取到期的任务列表
	 *
	 *    @author    Garbin
	 *    @param    none
	 *    @return    array
	 */
	public function get_due_tasks()
	{
		$tasks = array();
		if (empty($this->_tasks))
		{
			return $tasks;
		}
		foreach ($this->_tasks as $task => $config)
		{
			if (strtoupper(trim($config['status']))=="OPEN"){
				if ($this->is_due($config))
				{
					$tasks[] = $task;
				}
			}
		}

		return $tasks;
	}

	/**
	 *    执行任务列表
	 *
	 *    @author    Garbin
	 *    @param     array $tasks
	 *    @return    void
	 */
	public function run_task($tasks)
	{
		if (empty($tasks))
		{
			return;
		}
		foreach ($tasks as $task)
		{
			$this->_run_task($task);
		}
	}

	/**
	 *    更新任务列表
	 *
	 *    @author    Garbin
	 *    @param     array $tasks
	 *    @return    void
	 */
	public function update_tasks($tasks)
	{
		if (empty($tasks))
		{
			return;
		}
		foreach ($tasks as $task)
		{
			$this->_update_task($task);
		}
		$this->update();
	}

	/**
	 * 判断计划是否到期
	 * @param     array $task_config
	 * @return    bool
	 */
	public function is_due($task_config)
	{
		if ($task_config['cycle'] == 'none' && $task_config['last_time'])
		{
			return false;
		}
		$due_time = $task_config['due_time'];
		return ($this->_now >= $due_time);
	}

	/**
	 *    获取下次到期时间
	 *
	 *    @author    Garbin
	 *    @param     array $config
	 *    @return    int
	 */
	public function get_due_time($config)
	{
		$due_time = 0;
		switch ($config['cycle'])
		{
			/* 自定义的以当前时间为下次到期时间 */
			case 'custom':
				$due_time = $this->_now + $config['interval'];
			break;

			/* 每日定点 */
			case 'daily':
				/* 获取当日的时间点 */
				$today_due_time = strtotime(date('Y-m-d', $this->_now) . " {$config['hour']}:{$config['minute']}");

				if ($this->_now >= $today_due_time)
				{
					/* 如果已过这个时间点，则下次到期时间+周期1天 */
					$due_time = $today_due_time + 3600 * 24;
				}
				else
				{
					/* 否则就以当日的到期时间点为下次到期时间 */
					$due_time = $today_due_time;
				}
			break;

			/* 每周定点 */
			case 'weekly':
				$next_week_due_time = strtotime(date('Y-m-d', strtotime("next {$config['day']}"))." {$config['hour']}:{$config['minute']}");
				$this_week_due_time = $next_week_due_time - 7 * 24 * 3600;
				if ($this->_now >= $this_week_due_time)
				{
					/* 若已过了本周的时间点，则下次到期是下周的时间点 */
					$due_time = $next_week_due_time;
				}
				else
				{
					/* 否则为本周的时间点 */
					$due_time = $this_week_due_time;
				}
			break;

			/* 每月定点 */
			case 'monthly':
				$this_month_time = date('Y-m', $this->_now) . "-{$config['day']} {$config['hour']}:{$config['minute']}";
				$this_month_due_time = strtotime($this_month_time);                 //本月到期时间
				$next_month_due_time = strtotime($this_month_time . ' +1 month');   //下月到期时间
				if ($this->_now >= $this_month_due_time)
				{
					/* 已过本月时间点 */
					$due_time = $next_month_due_time;
				}
				else
				{
					/* 未过本月时间点 */
					$due_time = $this_month_due_time;
				}
			break;
			default:
				return false;
			break;
		}
		return $due_time;
	}

	/**
	 *    运行指定任务
	 *
	 *    @author    Garbin
	 *    @param     string $task_name
	 *    @return    bool
	 */
	public function _run_task($task_name)
	{
		$task_config = empty($this->_tasks[$task_name]['config']) ? array() : $this->_tasks[$task_name]['config'];
		$task_class_name = ucfirst($task_name) . 'Task';
		$task  = new $task_class_name($task_config);
		$task->run();
	}

	/**
	 * 更新任务列表
	 * @return    void
	 */
	public function update()
	{
		foreach (Gc::$module_names as $module_name) {
			$timerconfigpath=Gc::$nav_root_path."home".DS.$module_name.DS."src".DS."timertask".DS;
			if (file_exists($timerconfigpath."cron.config.xml"))
			{
				$filename=$timerconfigpath."cron.config.xml";
				$xml=UtilXmlSimple::fileXmlToObject($filename);
				if ($xml)
				{
					foreach ($xml as $xml_attributes) {
						$attributes=$xml_attributes->attributes();
						foreach($this->_tasks as $task_name=>$task) {
							if (property_exists($attributes,"name")&&($attributes->name==$task_name)){
								if ($task["module_name"]==$module_name){
									unset($task["module_name"]);
									foreach ($task as $key=>$value) {
										if (property_exists($attributes,$key)){
											$attributes->$key=$value;
										}else{
											if ($value!=null){
												if ($attributes){
													$attributes->addAttribute($key, $value);
												}
											}
										}
									}
								}
							}
						}
					}
				}
				$xml->asXML($filename);
			}
		}
	}

	/**
	 * 更新上次执行时间
	 * @param     string $task_name
	 * @return    void
	 */
	public function _update_task($task)
	{
		if (!isset($this->_tasks[$task]))
		{
			return;
		}

		if (isset($this->_tasks[$task]['status']))
		{
			if (strtoupper(trim($this->_tasks[$task]['status']))!="OPEN")
			{
				return;
			}
		}else{
			return;
		}

		/* 更新上次执行时间 */
		$this->_tasks[$task]['last_time'] = $this->_now;

		/* 更新下次到期时间 */
		$this->_tasks[$task]['due_time']  = $this->get_due_time($this->_tasks[$task]);
	}
}

/**
 * 任务基础类
 * @author skygreen
 */
class BaseTask extends BBObject
{
	public $_config = null;

	public function __construct($config)
	{
		$this->BaseTask($config);
	}

	public function BaseTask($config)
	{
		$this->_config = $config;
	}

	/**
	 * 运行任务
	 * @author    Garbin
	 */
	public function run() {}
}
?>
