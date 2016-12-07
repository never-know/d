<?php
namespace Min;

class Service
{	
	final public function success($result = [], $message = '操作成功')
	{	
		$result['code'] = 0;
		$result['message'] = $message;
		return $result;
	}

	final public function error($code, $message = '')
	{	
		return ['code' => $code, 'message' => $message];
	}
}