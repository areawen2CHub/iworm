<?php
/* Smarty version 3.1.29, created on 2016-07-15 09:30:08
  from "D:\workspace\iworm\smarty\templates\list.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5788ad20e27de0_03103233',
  'file_dependency' => 
  array (
    '0b97a42c61799e7c8ad1c8f772a85c26532c8ce6' => 
    array (
      0 => 'D:\\workspace\\iworm\\smarty\\templates\\list.html',
      1 => 1468575005,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5788ad20e27de0_03103233 ($_smarty_tpl) {
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
<!-- 头部 start -->
<div class="header-home">
    <div class="header-main nav-fixed-situ">
        <div class="container">
	       <div class="row">
				<div class="col-md-6">
					<form class="form-inline" method="post" action="index.php">
			        <div class="form-group">
			            <input type="text" class="form-control" name="keywords" placeholder="关键字..." value="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
">
			            <button type="submit" class="btn btn-search"></button>
			        </div>
			    	</form>
				</div>
			</div>
            <div class="row">
                <div class="col-md-9 col-sm-8 col-xs-6">
                    <div class="header-menu">
                        <ul id="nav">
                           <li><a href=""><i class="icon-comments"></i>&nbsp;娱乐新闻</a></li>
                           <li><a href=""><i class="icon-book"></i>&nbsp;科技人文</a></li>
                           <li><a href=""><i class="icon-info-sign"></i>&nbsp;当下时政</a></li>
                           <li><a href=""><i class="icon-question-sign"></i>&nbsp;奇闻趣事</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 头部 end -->
<!-- mainbody start -->
<div class="mainbdoy">
	<div class="blank-100"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<ul>
					<li>
						<div class="image image-text-list">
                             <div class="inner-area-content">
                             	<?php
$_from = $_smarty_tpl->tpl_vars['searchList']->value;
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
                                <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value->url;?>
"><h3><?php echo $_smarty_tpl->tpl_vars['item']->value->title;?>
</h3></a>
                                <p><?php echo $_smarty_tpl->tpl_vars['item']->value->description;?>
</p>
                                <p><a href="<?php echo $_smarty_tpl->tpl_vars['item']->value->url;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value->url;?>
</a></p>
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
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- mainbody end -->
</body>
</html><?php }
}
