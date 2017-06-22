<?php
/*
 *
 *	统一格式：A-BB-CC
 *	A:错误级别， 1代表数据库级错误;2代表系统级错误;3代表用户级错误;
 *	B:项目或模块名称，一般公司不会超过99个项目；
 *	C:
 *
 *
*/
 
	
$message=array(

	0   => '操作成功',
	
//  
	
// mysql 数据库: (10开头) 10+mysql 错误码

	102006 => '数据库连接失败',
	101002 => '数据库',

// 	系统级错误 (代码漏洞)
		20100 => '账号类型错误',
		20101 => 'request 参数错误',
		20102 => '短信验证码code或phone空或phone格式错误',
		20103 => '无效的SMS类型',
		20104 => 'password_hash failed'
		20105 => 'SMS参数未配置'
		20106 => '数据加载失败',
		20107 => 'SERVER参薯错误',
		

// 服务级错误 started with 3 (与用户行为相关)

	// 验证 started with 301
	
		30101 => 'crsf_token 校验失败',
		30102 => '图片验证码错误',
		30103 => '请输入图片验证码',
		30104 => '无效的IP地址'   ,
		

		// 短信验证码
		30110 => '短信验证码错误',
		30111 => '短信验证码过期',
		30112 => '短信验证码发送失败', //服务受限
		30113 => '短信验证码已发送错误  ', //  2分钟内已发送一次
		30114 => '短信验证码错误或者过期'   ,
		30115 => '短信验证码为空'   ,
		30116 => '短信发送受限'   ,

		 
		
		// 各类格式错误
		30120 => '手机号码格式错误',
		30121 => '邮箱格式错误',
		30122 => '用户名格式错误',

		
	
	// 用户模块 started with 302
	
		//帐号密码错误
			30200 => '账号格式错误',
			// 登陆
			30201 => '帐号密码错误',
			30202 => '帐号密码错误超过3次',
			30207 => '登陆失败次数超过6次，2小时后重试'
			30208 => '账号密码不能为空'
		 
			
			// 注册
			30203 => '注册密码不相同',
			30204 => '注册失败',
			30205 => '账号已被注册',
			30206 => '账号不存在',
			30207 => '服务受限',
			
			
			
	


);
	 