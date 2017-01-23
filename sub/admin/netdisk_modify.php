<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100105);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';

ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单

?>
					<div class="panel panel-default sss_sub_table">
                        <div class="panel-heading">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 
                            <?php
                            	$o_user=new Base_User($_GET['id']);
                            	$o_user_info=new Base_User_Info($_GET['id']);
                            	echo(Text::Key('NetdiskModify'));
								if($o_user->getUserName()==null || $o_user->getUserName()=='')
								{
									echo("<script>location='netdisk_index.php'</script>");
									exit(0);
								}
								?>
                            </div>
                    </div>
                    <form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
						<input type="hidden" name="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
						<input type="hidden" name="Vcl_BackUrl" value="<?php echo($_SERVER['HTTP_REFERER'])?>"/>
						<input type="hidden" name="Vcl_FunName" value="NetdiskModify"/>
						<input type="hidden" name="Vcl_Id" value="<?php echo($_GET['id'])?>"/>
                    	<div class="sss_form">
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('UserName'))?>：</label>
	                     		<fieldset disabled>
	                     		<input name="Vcl_UserName" value="" maxlength="20" id="Vcl_UserName" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" readonly="readonly"/>
	                     		</fieldset>
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('DiskSpace'))?>：</label><br/>
	                     		<select name="Vcl_DiskSpace" id="Vcl_DiskSpace" class="selectpicker" data-style="btn-default">
	                     			<option value="1">1 G</option>
        							<option value="2">2 G</option>
        							<option value="5">5 G</option>
        							<option value="10">10 G</option>
        							<option value="20">20 G</option>
        							<option value="30">30 G</option>
        							<option value="50">50 G</option>
        							<option value="100">100 G</option>
   								</select>
	                     	</div>
							<div class="item">
							<button id="user_add_btn" type="button" class="btn btn-default cancel" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="location='<?php echo($_SERVER['HTTP_REFERER'])?>'"><?php echo(Text::Key('Cancel'))?></button>
							<button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="netdisk_modify()"><?php echo(Text::Key('Submit'))?></button>
							</div>
                     	</div>
                     </form>
<script src="js/control.fun.js" type="text/javascript"></script>
<script type="text/javascript">
$('#Vcl_UserName').val('<?php echo($o_user->getUserName())?>');
$('#Vcl_DiskSpace').val('<?php echo($o_user_info->getDiskSpace()/1073741824)?>');
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>