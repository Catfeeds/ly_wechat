<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100101);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';
$s_fun='UserTable';
$s_item='UserName';
$s_page=1;
$s_sort='A';
$s_key='';
if($_COOKIE [$s_fun.'Item'])
{
	$s_item=$_COOKIE [$s_fun.'Item'];
	$s_page=$_COOKIE [$s_fun.'Page'];
	$s_sort=$_COOKIE [$s_fun.'Sort'];
	$s_key=$_COOKIE [$s_fun.'Key'];
}
ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单
?>

                    <div class="panel panel-default sss_sub_table">
                        <div class="panel-heading">
                            <div class="caption"><?php echo(Text::Key('UserListTitle'))?></div>
							<div class="row">
							  <div class="col-lg-6">
							    <div class="input-group">
							      <input id="Vcl_KeyUser" type="text" class="form-control" placeholder="<?php echo(Text::Key('UserSearch'))?>" value="<?php echo($s_key)?>">
							      <span class="input-group-btn">
							        <button class="btn btn-primary" type="button" onclick="search_for_user()"><span  class="glyphicon glyphicon-search"></span></button>
							      </span>
							    </div>
							  </div>
							</div> 
                            <button id="user_add_btn" type="button" title="<?php echo(Text::Key('AddUserAlt'))?>" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" data-toggle="tooltip" onclick="location='user_modify.php'">
                                <span  class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo(Text::Key('AddButton'))?></button></div>
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
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>