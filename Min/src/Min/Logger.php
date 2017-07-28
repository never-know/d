<?php 
namespace Min;
class Logger
{
	private $logs 				= [];
	private $allowed 			= [];
	private $default_file_size 	= '1024000';
	private $levels = [
        'DEBUG' => 100,
        'INFO' => 200,
        'NOTICE' => 250,
        'WARNING' => 300,
        'ERROR' => 400,
        'CRITICAL' => 500,
        'ALERT' => 550,
        'EMERGENCY' => 600 	// send sms
    ]; 
	
	public function __construct($option = [])
    {
		if (empty($option)) $option = config_get('logger');
        foreach ($this->levels as $key => $value) {
			if (!empty($option[$key])) $this->allowed[$key] = $option[$key];
		}
    }
	 
	public function log($message, $level = 'ERROR', $channel = 'default', $extra = [])
	{	
		//var_dump($message);
		$level = strtoupper($level);
		if (empty($this->allowed[$level])) return;

		$this->logs[] = ['level'=>$level, 'channel'=> $channel, 'message'=> $message, 'extra'=> $extra];
		
		if (isset($this->allowed[$level]['handler'])) {
			$handler = new $this->allowed[$level]['handler'];
			$handler->handler($message);
		}
	}
	
	public function record()
	{
		if(empty($this->logs)) return;
		
		$dest_file = LOG_PATH.date('/Y/m/');
		
		if (!is_dir($dest_file)) {
			mkdir($dest_file, 0777, true);
			touch($dest_file);
			chmod($dest_file, 0777);
		}
		
		$dest_file .= date('/Y-m-d').'.log';
		
		if (is_file($dest_file) && ($this->default_file_size < filesize($dest_file))) {
			rename($dest_file, $dest_file.'-BAK-'.time().'.log');
		}
		
		$has_error = '';
		
		$records = '<a> '  
				. date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])
				. ' | '
				. ip_address('ip')
				. ' | '
				. session_id()
				. ' | '
				. (session_get('USER_ID') ?: 0)
				. ' | '
				. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
				. ' | '
				. ($_SERVER['HTTP_REFERER']??'')
				. ' | '
				. getmypid()
				. PHP_EOL;
				
		foreach ($this->logs as $log) {
		
			$records .= '| '. str_pad($log['channel'], 6) . ' | ' . str_pad($log['level'], 7) . ' | ' . $log['message'] ;
			
			if (!empty($log['extra'])) {
				$records .= ' | more: '. json_encode($log['extra'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
			}
			
			$records .= PHP_EOL;
			
			if ($log['level'] != 'INFO' && $log['level'] != 'DEBUG' && empty($has_error)) {
				$has_error = str_pad($log['level'], 10) . " @ " . date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) . ' | ' . session_id() . PHP_EOL ;
			}
		}
		
		$records .= '</a>' . PHP_EOL;
		
		error_log($records, 3, $dest_file, '');
		if (!empty($has_error)) {
			error_log($has_error, 3, $dest_file.'.error', '');
		}
		
	}
	
}