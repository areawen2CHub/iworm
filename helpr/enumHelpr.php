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
		//$this->isUsing = new enumIsUsing();
	}
}


/**
 * 是否已使用
 * @author zhangxianwen
 * @update 2016-07-18
 */
class enumIsUsing{
	//	已使用
	const isUsing = 1;
	//	未使用
	const noUsing = 0;
}
/**
 * 是否为空
 * @author zhangxianwen
 * @update 2016-07-19
 */
class enumIsEmpty{
	//	为空
	const isEmpty = 1;
	//	不为空
	const noEmpty = 0;
}
/**
 * 收录状态
 * @author zhangxianwen
 * @update 2016-07-19
 */
class enumIncState{
	//	已收录
	const isIncState = 1;
	//	未收录
	const noIncState = 0;
}
/**
 * 是否删除
 * @author zhangxianwen
 * @update 2016-07-19
 */
class enumIsDel{
	//	已删除
	const isDelete = 1;
	//	未删除
	const noDelete = 0;
}
/**
 * 是否审核
 * @author zhangxianwen
 * @update 2016-07-19
 */
class enumIsCheck{
	//	已删除
	const isCheck = 1;
	//	未删除
	const noCheck = 0;
}
?>