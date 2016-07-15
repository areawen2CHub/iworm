<?php
/* Smarty version 3.1.29, created on 2016-07-15 06:53:17
  from "D:\workspace\iworm\smarty\templates\index.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5788885d1c2472_27477585',
  'file_dependency' => 
  array (
    '429a48ef09aa20c05fd26ed404a960ca4ead831c' => 
    array (
      0 => 'D:\\workspace\\iworm\\smarty\\templates\\index.html',
      1 => 1468565590,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5788885d1c2472_27477585 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
<meta charset="utf-8">
<meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0 , maximum-scale=1.0, user-scalable=0">
	<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<link rel="stylesheet" href="./content/css/subgroups.base.css" media="screen">
<link rel="stylesheet" href="./content/css/subgroups.main.css">
<link rel="stylesheet" href="./content/css/font-awesome.min.css">
</head>
<body>

<!-- mainbody start -->
<div class="mainbdoy">
	<div class="blank-100"></div>
	<div class="blank-100"></div>
	<div class="container">
		<div class="row" style="margin:0 20%">
			<div class="col-md-12">
				<form class="form-inline" method="get" action="list.php">
		        <div class="form-group">
		            <input type="text" class="form-control" id="keywordsid" name="keywords" placeholder="关键字..." value="">
		            <button type="submit" class="btn btn-search"></button>
		        </div>
		    	</form>
			</div>
			<div class="blank-8"></div>
			<div class="col-md-12">
				<div class="hotwords">
			       <?php
$_from = $_smarty_tpl->tpl_vars['keywords']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_item_0_saved_item = isset($_smarty_tpl->tpl_vars['item']) ? $_smarty_tpl->tpl_vars['item'] : false;
$__foreach_item_0_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$__foreach_item_0_saved_local_item = $_smarty_tpl->tpl_vars['item'];
?>
			       	<span><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</span>
			       <?php
$_smarty_tpl->tpl_vars['item'] = $__foreach_item_0_saved_local_item;
}
if ($__foreach_item_0_saved_item) {
$_smarty_tpl->tpl_vars['item'] = $__foreach_item_0_saved_item;
}
if ($__foreach_item_0_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_item_0_saved_key;
}
?>
			    </div>
			</div>
		</div>
	</div>
</div>
<!-- mainbody end -->
</body>
</html><?php }
}
