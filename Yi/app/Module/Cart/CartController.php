<?php
namespace App\Module\Cart;

use Min\App;

class CartController{

	public function __construct($action){
		if( $action == 'view') {
			$this->view();
		}elseif($action == 'add'){
			$this->add();
		}elseif($action == 'confirm'){
			$this->confirm();
		}
		exit();
	}
	
	private function view(){ 
	 
		 layout('cart');
	
	}
	private function add(){ 
	 
		 layout();
	
	}
	private function confirm(){ 
	 
		 layout('checkout');
	
	}

}