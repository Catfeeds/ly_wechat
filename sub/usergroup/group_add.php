<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100602);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';

ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单

?>
					<div class="panel panel-default sss_sub_table">
                        <div class="panel-heading">
                        <div class="caption">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 
                            <?php
                            $s_funname='GroupAdd'; 
                            if($_GET[id]>0)
                            {
                            	require_once RELATIVITY_PATH . 'sub/wechat/include/db_table.class.php';
                            	$o_group=new WX_Group($_GET[id]);
                            	if($o_group->getGroupName()==null || $o_group->getGroupName()=='')
								{
									echo("<script>location='group_list.php'</script>");
									exit(0);
								}
                            	$s_funname='GroupModify'; 
                            	echo('修改标签');
                            }else{
                            	echo('添加标签');
                            }
                            ?>
                            </div>
                            </div>
                    </div>
                    <form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
						<input type="hidden" name="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
						<input type="hidden" name="Vcl_BackUrl" value="<?php echo($_SERVER['HTTP_REFERER'])?>"/>
						<input type="hidden" name="Vcl_FunName" value="<?php echo($s_funname)?>"/>
						<input type="hidden" name="Vcl_Id" value="<?php echo($_GET['id'])?>"/>
                    	<div class="sss_form">	                     	
	                     	<div class="item">
	                     		<label><span class="must">*</span> 标签名称：</label>
	                     		<input name="Vcl_GroupName" id="Vcl_GroupName" maxlength="50" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
							<div class="item">
							<?php 
							if($_GET['id']>0)
							{
								?>
								<button id="user_add_btn" type="button" class="btn btn-default cancel" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="location='<?php echo($_SERVER['HTTP_REFERER'])?>'"><?php echo(Text::Key('Cancel'))?></button>
								<?php
							}
							?>
							<button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="group_modify()"><?php echo(Text::Key('Submit'))?></button>
							</div>
                     	</div>
                     </form>
<script src="js/control.fun.js" type="text/javascript"></script>
<script type="text/javascript">
<?php 
if($_GET['id']>0)
{
?>
$('#Vcl_GroupName').val('<?php echo($o_group->getGroupName())?>');
<?php 
}
?>
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>