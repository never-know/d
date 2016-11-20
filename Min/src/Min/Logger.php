<?php 
namespace Min;
class Logger{

	private $logs 				= [];
	private $allowed 			= [];
	private $channel 			= 'default';
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
		if (empty($option)) $option = parse_ini_file(CONF_PATH.'/logger.ini');
        foreach ($this->levels as $key => $value) {
			if (!empty($option[$key])) $this->allowed[$key] = $option[$key];
		}
    }

	public function init($channel = '')
	{
		if (!empty($channel)) $this->channel = $channel;
		return $this;
	}
	
	public function log($message, $level = 'ERROR', $extra = [], $channel = '')
	{	
		$level = strtoupper($level);
		if (empty($this->allowed[$level])) return;
		if (empty($channel)) $channel = $this->channel;
		
		$this->logs[] = ['level'=>$level, 'channel'=> $channel, 'message'=> $message, 'extra'=> $extra];
		
		if (isset($this->allowed[$level]['handler'])) {
			$handler = new $this->allowed[$level]['handler'];
			$handler->handler($message);
		}
	}
	
	public function record()
	{
		if(empty($this->logs)) return;
		
		$content = date( 'Y/m/d H:i:s',$_SERVER['REQUEST_TIME'])
				. '  [IP: '
				. ip_address()
				. ']  [ '
				. $_SERVER['REQUEST_URI']
				. ' ] '
				. PHP_EOL;
				
		$dest_file = LOG_PATH.date('/Y/m/');
		
		if (!is_dir($dest_file)) {
			mkdir($dest_file, 0777, true);
			touch($dest_file);
			chmod($dest_file, 0777);
		}
		
		$dest_file .= date('/Y-m-d-').'.log';
		
		if (is_file($dest_file) && ($this->default_file_size < filesize($dest_file))) {
			rename($dest_file, $dest_file.'-BAK-'.time().'.log');
		}
		
		$records = '';
		foreach ($this->logs as $log) {
			$records .= '[' . $log['channel'] . '] [' . $log['level'] . '] [' . $log['message'] . ']';
			if (!empty($log['extra'])) {
				$records .= ' ['. json_encode($log['extra'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . ']';
			}
			$records .= PHP_EOL;	
		}
		
		error_log($content.$records, 3, $dest_file, '');
	}
	
}