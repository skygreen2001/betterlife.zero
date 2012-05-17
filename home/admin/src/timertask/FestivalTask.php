<?php
/**
 * 定时轮循节假日 
 * @category ele
 * @package web.front.timertask
 * @author skygreen
 */
class FestivalTask extends BaseTask
{
	/**
	 * 定时轮循节假日 
	 */
	public function run()
	{
		LogMe::log("轮询节假日!");
	}
}
?>
