<?php
namespace App\Module\M;

use Min\App;

class CronController extends \Min\Controller
{
	 
	
	public function shareview_get()
	{
		$time = $_GET['time'];
		
		$sign	=  md5((md5(config_get('private_key') . ($time*($time%188))));
		
		if ($sign == $_GET['sign']) {
			$cache = $this->cache('share_view');
			$status = $cache->incr('current_status');
			
			if (is_numeric($status)  && $status == 1) {

				$log = $cache->pop('share_view_logs');
				
				while (!empty($log) && $cache->getDisc() != $log) {
					$arr = json_decode($log, true);
					if (!empty($arr['data_id'])) {
						$this->request('\\App\\Service\\ShareView::view', $arr, self::EXITNONE, true);
					}
					
					$log = $cache->pop('share_view_logs');
				}
				
				$cache->set('current_status', 0);
			} 
			
			$this->success();
			 
		}
		
		exit('SUCCESS');

	}
	
	 
}