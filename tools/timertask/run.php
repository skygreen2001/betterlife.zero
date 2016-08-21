<?php
require_once ("../../init.php");

register_shutdown_function(create_function('','run_cron();'));

/**
 * 运行一个计划任务守护进程
 */
function run_cron()
{
    // Ignore user aborts and allow the script
    // to run forever
    ignore_user_abort(true);
    set_time_limit(0);

    while(true)
    {
        // 设定定时任务终止条件
        $exitflag_file="unlock.cron";
        if (file_exists($exitflag_file)||
            file_exists(__DIR__.DS."core".DS."main".DS.$exitflag_file)||
            file_exists(__DIR__.DS."tools".DS."timertask".DS.$exitflag_file))
        {
            break;
        }
        $now=date("Y-m-d H:i:s");
        Cron::run_once();

//        if (intval(date("i"))%5==0){
            // 写文件操作开始
//            $fp = fopen("test".$count.".txt", "w");
//            if($fp)
//            {
//                $flag=fwrite($fp,$now.":这里是文件内容www.betterlife.com\r\n");
//                if(!$flag)
//                {
//                    echo "写入文件失败";
//                    break;
//                }
//            }
//            fclose($fp);
            // 写文件操作结束
//            LogMe::log($now);
//        }

        // Sleep for 10 seconds
        sleep(10);
    }
}

echo 'Cron Cycle Stop!<br/>计划任务定时触发守护进程结束！';























?>
