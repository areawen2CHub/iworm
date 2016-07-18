<?php if(!defined('SUBGROUPS')) exit('System Error!');

/**
 * 全局通用枚举类
 */
class enumHelpr{
	public $isUsing;
	
	//	构造函数
	public function __construct(){
		$this->initialise();
	}
	
	//	兼容低版本
	public function enumHelpr(){
		$this->initialise();
	}
	
	//	初始化
	private function initialise(){
		$this->isUsing = new enumIsUsing();
	}
}


/**
 * 是否已使用
 * @author zhangxianwen
 * @update 2016-07-18
 */
class enumIsUsing{
	//	已使用
	public $isUsing = 1;
	//	未使用
	public $noUsing = 0;
}
?>