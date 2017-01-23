<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100501);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';
$s_fun='UserList';
$s_item='RegisterDate';
$s_page=1;
$s_sort='D';
if($_COOKIE [$s_fun.'Item'])
{
	$s_item=$_COOKIE [$s_fun.'Item'];
	$s_page=$_COOKIE [$s_fun.'Page'];
	$s_sort=$_COOKIE [$s_fun.'Sort'];
	$s_key=$_COOKIE [$s_fun.'Key']; //因为Key当参数用了，所以就不要获取了
}
ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单
?>
                    <div class="panel panel-default sss_sub_table">
                        <div class="panel-heading">
                            <div class="caption" id="table_title">注册粉丝列表</div>
                            <div class="caption" id="status">&nbsp;&nbsp;&nbsp;&nbsp;</div>
                            <button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;
                                margin-top: 0px; margin-right:10px;margin-left:10px;  outline: medium none" onclick="window.open('output_all.php','_blank')">
                                <span  class="glyphicon glyphicon-download-alt"></span>&nbsp;<?php echo(Text::Key('OutputAll'))?></button>
                            <div class="row">
							  <div class="col-lg-6">
							    <div class="input-group">
							      <input id="Vcl_KeyUser" type="text" class="form-control" placeholder="模糊搜索粉丝信息" value="<?php echo($s_key)?>">
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
					table_sort('<?php echo($s_fun)?>','<?php echo($s_item)?>','<?php echo($s_sort)?>',<?php echo($s_page)?>,'<?php echo($s_key)?>')
					</script>
		
<script>
var table='<?php echo($s_fun)?>';
get_user_status();
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>