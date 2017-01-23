<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100601);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';
$s_fun='GroupList';
$s_item='GroupName';
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
                            <div class="caption" id="table_title">用户标签列表</div>
                            <button title="" class="btn btn-success" id="user_add_btn" aria-hidden="true" style="float: right;" onclick="location='group_add.php'" type="button" data-original-title="添加一个新用户" data-toggle="tooltip" data-placement="left">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;添加标签</button>
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
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>