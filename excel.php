<?php
header ( 'Cache-Control: no-cache' );
header ( 'Pragma: no-cache' );
header ( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
header ( 'Last-Modified:' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
header ( 'content-type:text/html; charset=utf-8' );
require_once 'include/PHPExcel.php';
/*
$objWorksheet = $objPHPExcel->getActiveSheet ();
$highestRow = $objWorksheet->getHighestRow ();
$highestColumn = $objWorksheet->getHighestColumn ();
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

$excelData = array ();
for($row = 1; $row <= $highestRow; $row ++) {
	for($col = 0; $col < $highestColumnIndex; $col ++) {
		$excelData [$row] [] = ( string ) $objWorksheet->getCellByColumnAndRow ( $col, $row )->getValue ();
	}
}
echo($excelData);*/

function readexcel($filePath) {
	//echo($filePath);
	$PHPReader = new PHPExcel_Reader_Excel2007 ();
	if (! $PHPReader->canRead ( $filePath )) {
		
		$PHPReader = new PHPExcel_Reader_Excel2007 ();
		if (! $PHPReader->canRead ( $filePath )) {
			echo 'no Excel';
			return;
		}
	}
	$PHPExcel = $PHPReader->load ( $filePath );
	$currentSheet = $PHPExcel->getSheet ( 0 );
	

	$allColumn = $currentSheet->getHighestColumn ();

	
	$allRow = $currentSheet->getHighestRow ();
	$all = array ();
	for($currentRow = 1; $currentRow <= $allRow; $currentRow ++) {
		
		$flag = 0;
		$col = array ();
		for($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn ++) {
			
			$address = $currentColumn . $currentRow;
			
			$string = $currentSheet->getCell ( $address )->getValue ();
			
			$col [$flag] = $string;
			
			$flag ++;
		}
		$all [] = $col;
	}
	return $all;
}
$a=readexcel('test.xlsx');
echo($a[1][1]);
?>