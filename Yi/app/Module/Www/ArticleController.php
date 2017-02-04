<?php
namespace App\Module\Www;

use Min\App;

class ArticleController extends \Min\Controller
{
	 

	public function index_get()
	{
		$result = ['menu_active' => 'article', 'title' =>'文字列表'];
		$this->response($result);
	}
	
	public function edit_get()
	{
		$result = ['menu_active' => 'article_edit', 'title' =>'新增文字'];
		$this->response($result);
	}
	
	public function edit_post()
	{
		$param['title'] = $_POST['title'];
		$param['desc'] 	= $_POST['desc'];
		$param['icon'] 	= $_POST['icon'];
		
		if(!empty($_POST['date_start']) && ! \validate('date_Y-m-d', $_POST['date_start'])) {
			$this->error(1, '开始日期格式错误');
		}
		if(!empty($_POST['date_end']) && ! \validate('date_Y-m-d', $_POST['date_end'])) {
			$this->error(1, '结束日期格式错误');
		}
		$param['start'] = $_POST['date_start'];
		$param['end'] 	= $_POST['date_end'];
		
		$this->response($result);
	}
	
	 


}