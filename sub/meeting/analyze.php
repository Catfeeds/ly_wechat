<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100405);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';
ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单
?>

                    <div class="panel panel-default sss_sub_table" style="overflow:hidden;">
                        <div class="panel-heading">
                            <div class="caption">1. 总报名人数</div>
                        </div>
                        <div style="text-align:center">
							<div style="margin:0 auto;margin-top:30px;width:500px;height:500px">
							    <svg id="chart_all"></svg>
							</div>	
						</div>
						<div class="panel-heading" style="border-top:1px solid #ddd">
                            <div class="caption">2. 各分会场审核人数比例</div>
                        </div>
                        <div style="text-align:center;width:25%;float:left">
							<div style="margin:0 auto;margin-top:10px;margin-bottom:10px;width:200px;height:210px;">
								<div class="btn bg-primary" style="cursor:inherit;">北京</div>
								<svg id="chart_audit_bj"></svg>	
							</div>
						</div>	
						<div style="text-align:center;width:25%;float:left">
							<div style="margin:0 auto;margin-top:10px;margin-bottom:10px;width:200px;height:210px;">
								<div class="btn btn-success" style="cursor:inherit;">上海</div>
								<svg id="chart_audit_sh"></svg>	
							</div>
						</div>	
						<div style="text-align:center;width:25%;float:left">
							<div style="margin:0 auto;margin-top:10px;margin-bottom:10px;width:200px;height:210px;">
								<div class="btn btn-info" style="cursor:inherit;">成都</div>
								<svg id="chart_audit_cd"></svg>	
							</div>
						</div>	
						<div style="text-align:center;width:25%;float:left">
							<div style="margin:0 auto;margin-top:10px;margin-bottom:10px;width:200px;height:210px;">
								<div class="btn btn-warning" style="cursor:inherit;">成都荷航</div>
								<svg id="chart_audit_hh"></svg>	
							</div>
						</div>									
                    </div>
<link href="js/nv.d3.css" rel="stylesheet" type="text/css">
    <script src="js/d3.min.js" charset="utf-8"></script>
    <script src="js/nv.d3.js"></script>      
<style>
.nvd3 .nv-pieLabels .nv-label text {
            font-size: 16px;
			fill: #777777 !important;
			font-weight:blod;
			font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
        }
.nvd3 .nv-series text {
        font-size: 14px;
		padding: 10px;
        font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
        }
.nvd3.nv-pie.nv-chart-donut1 .nv-pie-title {
        fill: #F5C581;
        font-family: 微软雅黑, Microsoft Yahei, Hiragino Sans GB, tahoma, arial, 宋体;
        }
</style>         
	
<script>
<?php 
require_once RELATIVITY_PATH . 'sub/wechat/include/db_table.class.php';
$o_user=new WX_User_Info();
$o_user->PushWhere ( array ('&&', 'SceneId', '=',2) );
$n_bj=$o_user->getAllCount();
$o_user=new WX_User_Info();
$o_user->PushWhere ( array ('&&', 'SceneId', '=',3) );
$n_sh=$o_user->getAllCount();
$o_user=new WX_User_Info();
$o_user->PushWhere ( array ('&&', 'SceneId', '=',1) );
$n_cd=$o_user->getAllCount();
$o_user=new WX_User_Info();
$o_user->PushWhere ( array ('&&', 'SceneId', '=',6) );
$n_cd_hh=$o_user->getAllCount();
?>
			var data_all = [
                {key: "北京", y: 0,color:"#3078B6"},
                {key: "上海", y: 0,color:"#5AB75A"},
                {key: "成都", y: 0,color:"#59BFDE"},
                {key: "成都荷航", y: 0,color:"#F0AC4B"}
            ];
            var height = 500;
            var width = 500;
            nv.addGraph(function() {
                var chart = nv.models.pieChart()
                    .x(function(d) { return d.key })
                    .y(function(d) { return d.y })
                    .donut(true)
                    .width(width)
                    .height(height)
                    .title("<?php 
                    $o_user=new WX_User_Info();
                    echo($o_user->getAllCount())?>")
                    .padAngle(.02)
                    .donutLabelsOutside(true)
                    .valueFormat(d3.format(',r'))
                    .margin({top: 0, right: 0, bottom: 0, left: 0})
                    .color(nv.utils.defaultColor())
                    .id('donut1')
                    .legendPosition("top")
                    .showTooltipPercent(true);
                chart.legend.vers('furious');//图注反白
                d3.select("#chart_all")
                    .datum(data_all)
                    .transition().duration(1200)
                    .attr('width', width)
                    .attr('height', height)
                    .call(chart);
                setTimeout(function() {
                	data_all[0].y = <?php echo($n_bj)?>;
                	data_all[1].y = <?php echo($n_sh)?>;
                	data_all[2].y = <?php echo($n_cd)?>;
                	data_all[3].y = <?php echo($n_cd_hh)?>;
                    chart.update();
                }, 300);
                return chart;
            });

            
            var chart_audit_bj = [
            	{key: "已审核", y: 0,color:"#2F78B6"},
                {key: "未审核", y: 0,color:"#64A3D7"}
            ];
            nv.addGraph(function() {
                var chart = nv.models.pieChart()
                    .x(function(d) { return d.key })
                    .y(function(d) { return d.y })
                    .width(200)
                    .height(200)
                    .title("北京")
                    .showLegend(false)
                    .margin({top: 0, right: 0, bottom: 0, left: 0})
                    .showTooltipPercent(true);
                	
                d3.select("#chart_audit_bj")
                    .datum(chart_audit_bj)
                    .transition().duration(1200)
                    .attr('width', 200)
                    .attr('height', 200)
                    .call(chart);
                setTimeout(function() {
                	chart_audit_bj[0].y = <?php
                	$o_user=new WX_User_Info();
					$o_user->PushWhere ( array ('&&', 'SceneId', '=',2) );
					$o_user->PushWhere ( array ('&&', 'AuditFlag', '=',1) );
					echo($o_user->getAllCount());
                	?>;
                	chart_audit_bj[1].y = <?php 
                	echo($n_bj-$o_user->getAllCount())
                	?>;
                    chart.update();
                }, 300);
                return chart;
            });

            var chart_audit_sh = [
            	{key: "已审核", y: 0,color:"#5AB759"},
                {key: "未审核", y: 0,color:"#96D196"}
            ];
            nv.addGraph(function() {
                var chart = nv.models.pieChart()
                    .x(function(d) { return d.key })
                    .y(function(d) { return d.y })
                    .width(200)
                    .title("上海")
                    .showLegend(false)
                    .height(200)
                    .margin({top: 0, right: 0, bottom: 0, left: 0})
                    .showTooltipPercent(true);
            		
                d3.select("#chart_audit_sh")
                    .datum(chart_audit_sh)
                    .transition().duration(1200)
                    .attr('width', 200)
                    .attr('height', 200)
                    .call(chart);
                setTimeout(function() {
                	chart_audit_sh[0].y = <?php
                	$o_user=new WX_User_Info();
					$o_user->PushWhere ( array ('&&', 'SceneId', '=',3) );
					$o_user->PushWhere ( array ('&&', 'AuditFlag', '=',1) );
					echo($o_user->getAllCount());
                	?>;
                	chart_audit_sh[1].y = <?php 
                	echo($n_sh-$o_user->getAllCount())
                	?>;
                    chart.update();
                }, 300);
                return chart;
            });

            var chart_audit_cd = [
            	{key: "已审核", y: 0,color:"#59BFDE"},
                {key: "未审核", y: 0,color:"#8FD5E9"}
            ];
            nv.addGraph(function() {
                var chart = nv.models.pieChart()
                    .x(function(d) { return d.key })
                    .y(function(d) { return d.y })
                    .width(200)
                    .height(200)
                    .title("成都")
                    .showLegend(false)
                    .donut(true)
                    .padAngle(.04)
                    .margin({top: 0, right: 0, bottom: 0, left: 0})
                    //.donutLabelsOutside(true)
                    .showTooltipPercent(true);
            		
                d3.select("#chart_audit_cd")
                    .datum(chart_audit_cd)
                    .transition().duration(1200)
                    .attr('width', 200)
                    .attr('height', 200)
                    .call(chart);
                setTimeout(function() {
                	chart_audit_cd[0].y = <?php
                	$o_user=new WX_User_Info();
					$o_user->PushWhere ( array ('&&', 'SceneId', '=',1) );
					$o_user->PushWhere ( array ('&&', 'AuditFlag', '=',1) );
					echo($o_user->getAllCount());
                	?>;
                	chart_audit_cd[1].y = <?php 
                	echo($n_cd-$o_user->getAllCount())
                	?>;
                    chart.update();
                }, 300);
                return chart;
            });

            var chart_audit_hh = [
            	{key: "已审核", y: 0,color:"#F0AB49"},
                {key: "未审核", y: 0,color:"#F5C889"}
            ];
            nv.addGraph(function() {
                var chart = nv.models.pieChart()
                    .x(function(d) { return d.key })
                    .y(function(d) { return d.y })
                    .width(200)
                    .height(200)
                    .title("成都荷航")
                    .showLegend(false)
                    .margin({top: 0, right: 0, bottom: 0, left: 0})
                    .showTooltipPercent(true);
            		
                d3.select("#chart_audit_hh")
                    .datum(chart_audit_hh)
                    .transition().duration(1200)
                    .attr('width', 200)
                    .attr('height', 200)
                    .call(chart);
                setTimeout(function() {
                	chart_audit_hh[0].y = <?php
                	$o_user=new WX_User_Info();
					$o_user->PushWhere ( array ('&&', 'SceneId', '=',6) );
					$o_user->PushWhere ( array ('&&', 'AuditFlag', '=',1) );
					echo($o_user->getAllCount());
                	?>;
                	chart_audit_hh[1].y = <?php 
                	echo($n_cd_hh-$o_user->getAllCount())
                	?>;
                    chart.update();
                }, 300);
                return chart;
            });
            
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>