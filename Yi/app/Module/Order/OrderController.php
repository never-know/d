<?php
namespace App\Module\Order;

use Min\App;

class OrderController{

	public function __construct($action){
		if( $action == 'view') {
			$this->view();
		}elseif($action == 'add'){
			$this->add();
		}elseif($action == 'confirm'){
			$this->checkout();
		}
		exit();
	}
	
	private function view(){ 
	 
		 app::layout('cart');
	
	}
	private function add(){ 
	 
		 app::layout();
	
	}
	private function confirm(){ 
	 
		 app::layout('cart');
	
	}

}