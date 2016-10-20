<?php

	function is_email($value) {
		return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', trim($value));
	}
	function is_tel($value) {
		return preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/', trim($value));
	}
	function is_phone($value) {
		return preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?1\d{10}$/', trim($value));
	}
	 function is_safe_account($value) {
		return preg_match ('/^[a-zA-Z]{1}[a-zA-Z0-9_\.]{3,31}$/', $value);
	}
	function is_username($value) {
		return preg_match ('/^[\w_-]{4,31}$/', $value);
	}
	
	function is_alphpha($value){
		return preg_match('/^[a-zA-Z]+$/',$value);
	}