<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100401);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';
$s_fun='MeetingKeyAgent';
$s_item='SigninFlag';
$s_page=1;
$s_sort='A';
$n_activity_id=$_GET['id'];
$s_key=$n_activity_id;
if($_COOKIE [$s_fun.'Item'])
{
	$s_item=$_COOKIE [$s_fun.'Item'];
	$s_page=$_COOKIE [$s_fun.'Page'];
	$s_sort=$_COOKIE [$s_fun.'Sort'];
	//$s_key=$_COOKIE [$s_fun.'Key']; //因为Key当参数用了，所以就不要获取了
}
ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单
?>
                    <div class="panel panel-default sss_sub_table">
                        <div class="panel-heading" style="position:static;">
                            <div class="caption" id="table_title">
                            <strong><?php 
                                //如果会议已经过期，那么不显示群发提醒按钮
                                require_once RELATIVITY_PATH . 'sub/wechat/include/db_table.class.php';
			        			$o_activity=new WX_Activity($n_activity_id);
			        			echo($o_activity->getTitle());
                                ?></strong>
                            &nbsp;&nbsp;&nbsp;&nbsp;Key Agent 签到监控&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-danger">每30秒自动更新</span></div>
                            <div class="caption" id="status">&nbsp;&nbsp;&nbsp;&nbsp;</div>
                            <button id="user_add_btn" type="button" class="btn btn-primary" aria-hidden="true" style="float: right;
                                margin-top: 0px; outline: medium none" onclick="location='meeting_list.php'">
                                <?php echo(Text::Key('Back'))?></button>
                            </div>
                        <table class="table table-striped">
                            <thead>
                                <tr></tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="sss_page"></div>
					<script src="js/control.fun.js" type="text/javascript"></script>
					<script>
					table_sort('<?php echo($s_fun)?>','<?php echo($s_item)?>','<?php echo($s_sort)?>',<?php echo($s_page)?>,'<?php echo($s_key)?>')
					</script>
		
<script>
var table='<?php echo($s_fun)?>';
//需要每30秒刷新一下记录
setInterval("table_sort('<?php echo($s_fun)?>','<?php echo($s_item)?>','<?php echo($s_sort)?>',<?php echo($s_page)?>,'<?php echo($s_key)?>')",20000) 
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>