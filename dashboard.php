<?php
define ( 'RELATIVITY_PATH', '' );
define ( 'MODULEID', 100000);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';
ExportMainTitle(MODULEID,$O_Session->getUid());
?>
<div class="collapse navbar-collapse sss_main_sub_nav">
	<ul class="nav navbar-nav">
		
	</ul>
</div>
<div class="panel panel-default sss_sub_table" style="padding:30px 15px 30px 15px;overflow:hidden">
	<div style="padding-left:15px;padding-right:15px;overflow:hidden;width:33%;float:left;">
		<div style="background-color:#2ecc71;overflow:hidden;padding:15px;color: #fff;width:100%;margin-bottom:15px;">
			<div style="float:left;width:70px;overflow:hidden"><span  class="glyphicon glyphicon-user" style="font-size:15px"></span><span  class="glyphicon glyphicon-user" style="font-size:30px"></span><span  class="glyphicon glyphicon-user" style="font-size:15px"></span></div>
			<div style="float:left;padding-left:15px;overflow:hidden">
			正常用户<br/>
			<span style="font-size:20px;">150+</span>
			</div>
			<div style="float:left;padding-left:20px;overflow:hidden">
			已停用<br/>
			<span style="font-size:20px;">150</span>
			</div>
			<div style="float:left;padding-top:10px;width:100%">
			<div style="float:left;width:40%">创建角色 <br/>
			<span style="font-size:16px;">15</span> 个
			</div>
			<div style="float:left;width:40%">创建角色 <br/>
			<span style="font-size:16px;">15</span> 个
			</div>
			<div style="float:left;width:20%">创建角色 <br/>
			<span style="font-size:16px;">15</span> 个
			</div>
			</div>
		</div>
	</div>                                       
</div>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>