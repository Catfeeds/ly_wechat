<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100102);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';
$s_fun='RoleTable';
$s_item='Name';
$s_page=1;
$s_sort='A';
if($_COOKIE [$s_fun.'Item'])
{
	$s_item=$_COOKIE [$s_fun.'Item'];
	$s_page=$_COOKIE [$s_fun.'Page'];
	$s_sort=$_COOKIE [$s_fun.'Sort'];
}
ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单

?>

                    <div class="panel panel-default sss_sub_table">
                        <div class="panel-heading">
                        <div class="caption">
                            <?php echo(Text::Key('RoleList'))?>
                            </div>
                            <button id="user_add_btn" type="button" title="<?php echo(Text::Key('AddRoleAlt'))?>" class="btn btn-success" aria-hidden="true" style="float: right;
                                outline: medium none" data-placement="left" data-toggle="tooltip" onclick="location='role_modify.php'">
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
					table_sort('<?php echo($s_fun)?>','<?php echo($s_item)?>','<?php echo($s_sort)?>',<?php echo($s_page)?>)
					</script>
<script>
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>