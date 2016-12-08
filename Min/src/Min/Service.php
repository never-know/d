<?php
namespace Min;

class Service
{	
	final public function success($body = [], $message = '操作成功')
	{	
		if (!empty($body)) $result['body'] = $body;
		$result['code'] = 0;
		$result['message'] = $message;
		return $result;
	}

	final public function error($message, $code)
	{	
		return ['code' => $code, 'message' => $message];
	}
}