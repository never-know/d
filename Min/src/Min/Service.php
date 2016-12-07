<?php
namespace Min;

class Service
{	
	final public function success($message = '操作成功', $body = [])
	{	
		if (!empty($body)) $result['body'] = $body;
		$result['code'] = 0;
		$result['message'] = $message;
		return $result;
	}

	final public function error($code, $message = '')
	{	
		return ['code' => $code, 'message' => $message];
	}
}