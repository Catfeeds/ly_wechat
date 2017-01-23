<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100301);
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
                            echo(Text::Key('UserInfoTitle'));
                            $o_user=new Base_User($O_Session->getUid());
                            $o_user_info=new Base_User_Info($O_Session->getUid());
                            $o_user_custom=new Base_User_Info_Custom($O_Session->getUid());
                            $o_user_role=new Base_User_Role($O_Session->getUid());
                            ?>
                            </div>
                            </div>
                    </div>
                    <form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
						<input type="hidden" name="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
						<input type="hidden" name="Vcl_BackUrl" value="<?php echo($_SERVER['HTTP_REFERER'])?>"/>
						<input type="hidden" name="Vcl_FunName" value="InfoModify"/>
                    	<div class="sss_form">
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('UserName'))?>：</label>
	                     		<fieldset disabled>
	                     		<input name="Vcl_UserName" value="" maxlength="20" id="Vcl_UserName" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" readonly="readonly"/>
	                     		</fieldset>	
	                     	</div>
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('Name'))?>：</label>
	                     		<input name="Vcl_Name" maxlength="20" id="Vcl_Name" type="text" style="width:100%" placeholder="<?php echo(Text::Key('NameAlt'))?>" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Sex'))?>：</label><br/>
	                     		<input tabindex="19" type="radio" id="line-radio-1"value="<?php echo(Text::Key('Man'))?>"  name="Vcl_Sex" id="Vcl_Man" <?php if ($o_user_info->getSex()=='男')echo('checked')?>/>
            						<label for="line-radio-1" class="radio_text"> <?php echo(Text::Key('Man'))?></label>&nbsp;&nbsp;
            						<input tabindex="20" type="radio" value="<?php echo(Text::Key('Woman'))?>" id="line-radio-2" name="Vcl_Sex" id="Vcl_Woman" <?php if ($o_user_info->getSex()=='女')echo('checked')?>/>
            						<label for="line-radio-2" class="radio_text"> <?php echo(Text::Key('Woman'))?></label>
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Birthday'))?>：</label>
		                     		<div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    					<input class="form-control" size="16" type="text" id="Vcl_Birthday" name="Vcl_Birthday" readonly style="background-color:white">
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                					</div>
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Email'))?>：</label>
	                     		<input name="Vcl_Email" id="Vcl_Email" maxlength="50" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Phone'))?>：</label>
	                     		<input name="Vcl_Phone" id="Vcl_Phone"  maxlength="15" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Department'))?>：</label><br/>
	                     		<fieldset disabled>
	                     		<input  maxlength="20" id="Vcl_DeptId" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" readonly="readonly"/>
	                     		</fieldset>	
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('PrimaryRole'))?>：</label><br/>
	                     		<fieldset disabled>
	                     		<input  maxlength="20" id="Vcl_Role0" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" readonly="readonly"/>
	                     		</fieldset>		
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('DiskSpace'))?>：</label><br/>
	                     		<fieldset disabled>
	                     		<input name="Vcl_DiskSpace" value="" maxlength="20" id="Vcl_DiskSpace" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" readonly="readonly"/>
	                     		</fieldset>	
	                     	</div>
							<div class="item">
							<button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="info_modify()"><?php echo(Text::Key('Save'))?></button>
							</div>
                     	</div>
                     	 
                     </form>
<script src="js/control.fun.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo(RELATIVITY_PATH)?>js/bootstrap/js/date/css/bootstrap-datetimepicker.css"/>
<script src="<?php echo(RELATIVITY_PATH)?>js/bootstrap/js/date/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="<?php echo(RELATIVITY_PATH)?>js/bootstrap/js/date/js/locales/bootstrap-datetimepicker.zh-CN.js" type="text/javascript"></script>
<script type="text/javascript">
$('#Vcl_UserName').val('<?php echo($o_user->getUserName())?>');
$('#Vcl_Name').val('<?php echo($o_user_info->getName())?>');
$('#Vcl_DiskSpace').val('<?php echo($o_user_info->getDiskSpace()/1073741824)?>'+' G');
$('#Vcl_Email').val('<?php echo($o_user_info->getEmail())?>');
$('#Vcl_Birthday').val('<?php if ($o_user_custom->getBirthday()!='0000-00-00')echo($o_user_custom->getBirthday())?>');
$('#Vcl_Phone').val('<?php echo($o_user_info->getPhone())?>');
$('#Vcl_DeptId').val('<?php $o_role=new Base_Dept($o_user_role->getDeptId());echo($o_role->getName())?>');
$('#Vcl_Role0').val('<?php $o_role=new Base_Role($o_user_role->getRoleId());echo($o_role->getName())?>');

$('.form_date').datetimepicker({
    language:  'zh-CN',
    weekStart: 1,
    todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	forceParse: 0
});
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>