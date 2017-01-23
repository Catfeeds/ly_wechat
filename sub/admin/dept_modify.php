<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100103);
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
                            $s_funname='DeptAdd'; 
                            if($_GET[id]>0)
                            {
                            	$o_dept=new Base_Dept($_GET['id']);
                            	$s_funname='DeptModify'; 
                            	echo(Text::Key('DeptModifyTitle'));
								if($o_dept->getName()==null || $o_dept->getName()=='')
								{
									echo("<script>location='dept_index.php'</script>");
									exit(0);
								}
                            }else{
                            	echo(Text::Key('DeptAddTitle'));
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
	                     		<label><span class="must">*</span> <?php echo(Text::Key('DeptName'))?>：</label>
	                     		<input name="Vcl_Name" maxlength="20" id="Vcl_Name" type="text" style="width:100%" class="form-control"/>
	                     	</div>	
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Address'))?>：</label>
	                     		<input name="Vcl_Address" maxlength="255" id="Vcl_Address" type="text" style="width:100%" class="form-control"/>
	                     	</div>	
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Phone'))?>：</label>
	                     		<input name="Vcl_Phone" maxlength="20" id="Vcl_Phone" type="text" style="width:100%" class="form-control"/>
	                     	</div>	
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Fax'))?>：</label>
	                     		<input name="Vcl_Fax" maxlength="20" id="Vcl_Fax" type="text" style="width:100%" class="form-control"/>
	                     	</div>	                     	
							<div class="item">
							<button id="user_add_btn" type="button" class="btn btn-default cancel" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="location='<?php echo($_SERVER['HTTP_REFERER'])?>'"><?php echo(Text::Key('Cancel'))?></button>
							<button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="dept_modify()"><?php echo(Text::Key('Submit'))?></button>
							</div>
                     	</div>
                     </form>
<script src="js/control.fun.js" type="text/javascript"></script>
<script type="text/javascript">
<?php 
if($_GET['id']>0)
{
?>
$('#Vcl_Name').val('<?php echo($o_dept->getName())?>');
$('#Vcl_Address').val('<?php echo($o_dept->getAddress())?>');
$('#Vcl_Phone').val('<?php echo($o_dept->getPhone())?>');
$('#Vcl_Fax').val('<?php echo($o_dept->getFax())?>');
<?php 
}
?>
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>