<?php
namespace Vendor\Ali;

class AliBase
{
    public function __construct()
    {
        require_once  __DIR__ . '/api_sdk/vendor/autoload.php';
		
		\Aliyun\Core\Config::load();
 
    }
}
