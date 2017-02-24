<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100401);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';
$s_fun='MeetingAuditList';
$s_item='AuditFlag';
$s_page=1;
$s_sort='A';
$n_activity_id=$_GET['id'];
$s_key=$n_activity_id;
if($_COOKIE [$s_fun.'Item'])
{
	$s_item=$_COOKIE [$s_fun.'Item'];
	$s_page=$_COOKIE [$s_fun.'Page'];
	$s_sort=$_COOKIE [$s_fun.'Sort'];
	$s_otherkey=$_COOKIE [$s_fun.$s_key.'OtherKey']; //因为Key当参数用了，所以就不要获取了
}
//echo($s_fun.$_COOKIE [$s_fun.'Key']);
ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单
?>
                    <div class="panel panel-default sss_sub_table">
                        <div class="panel-heading" style="position:static;">
                            <div class="caption" id="table_title">报名审核</div>
                            <div class="caption" id="status">&nbsp;&nbsp;&nbsp;&nbsp;</div>
                            <button id="user_add_btn" type="button" class="btn btn-primary" aria-hidden="true" style="float: right;
                                margin-top: 0px; outline: medium none" onclick="location='meeting_list.php'">
                                <?php echo(Text::Key('Back'))?></button>
                            <button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;
                                margin-top: 0px; margin-right:10px; outline: medium none" onclick="window.open('output_all.php?id=<?php echo($n_activity_id)?>','_blank')">
                                <span  class="glyphicon glyphicon-download-alt"></span>&nbsp;<?php echo(Text::Key('OutputAll'))?></button>
                                <?php 
                                //如果会议已经过期，那么不显示群发提醒按钮
                                require_once RELATIVITY_PATH . 'sub/wechat/include/db_table.class.php';
                                $o_date = new DateTime ( 'Asia/Chongqing' );
			        			$today=$o_date->format ( 'Y' ) . '-' . $o_date->format ( 'm' ) . '-' . $o_date->format ( 'd' );
			        			$o_activity=new WX_Activity($n_activity_id);
			        			if(strtotime($today) <= strtotime($o_activity->getActivityDate())){
			        				?>
			        				<button id="user_add_btn" type="button" class="btn btn-warning" aria-hidden="true" style="float: right; margin-top: 0px; margin-right:10px; outline: medium none" onclick="send_reminder(<?php echo($n_activity_id)?>,'<?php echo(rawurlencode($o_activity->getTitle()))?>','<?php echo(rawurlencode($o_activity->getActivityDate().'（周'.$o_activity->getWeek().'）'.$o_activity->getActivityTime()));?>','<?php echo(rawurlencode($o_activity->getRemFirst()));?>','<?php echo(rawurlencode($o_activity->getRemRemark()));?>')">
                                		<span  class="glyphicon glyphicon-bell"></span>
                                		&nbsp;群发提醒
                                	</button>
			        				<?php
			        			}
                                ?>
	                             <div class="row" style="margin-right:-5px;">
								  <div class="col-lg-6">
								    <div class="input-group" style="width:300px;" >
								      <input id="Vcl_KeyUser" type="text" class="form-control" placeholder="姓名、电话、公司、手机或邮箱" value="<?php echo($s_otherkey)?>">
								      <span class="input-group-btn">
								        <button class="btn btn-primary" type="button" onclick="search_for_user()"><span  class="glyphicon glyphicon-search"></span></button>
								      </span>
								    </div>
								  </div>
								</div> 
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
					table_sort('<?php echo($s_fun)?>','<?php echo($s_item)?>','<?php echo($s_sort)?>',<?php echo($s_page)?>,'<?php echo($s_key)?>','<?php echo($s_otherkey)?>')
					</script>
		
<script>
get_audit_status(<?php echo($n_activity_id)?>);
var table='<?php echo($s_fun)?>';
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>