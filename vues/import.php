<?php 
	require 'lib/PHPExcel.php';
	require_once 'lib/PHPExcel/IOFactory.php';
?>

<div class="width800">
	<form method="post" enctype="multipart/form-data">
		Upload File: <input type="file" name="spreadsheet"/>
		<input type="submit" name="submit" value="Submit" />
	</form>
</div>

<?php 
	//Check valid spreadsheet has been uploaded
 //Check valid spreadsheet has been uploaded
if(isset($_FILES['spreadsheet'])){
	if($_FILES['spreadsheet']['tmp_name']){
		if(!$_FILES['spreadsheet']['error'])
		{

			$inputFile = $_FILES['spreadsheet']['tmp_name'];
			$extension = strtoupper(pathinfo($_FILES['spreadsheet']['name'], PATHINFO_EXTENSION));
			if($extension == 'XLS' || $extension == 'XLSX' || $extension == 'ODS'){

        //Read spreadsheeet workbook
				try {
					$inputFileType = PHPExcel_IOFactory::identify($inputFile);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($inputFile);
				} catch(Exception $e) {
					die($e->getMessage());
				}

        //Get worksheet dimensions
				$sheet = $objPHPExcel->getSheet(2); 
				$highestRow = $sheet->getHighestRow(); 
				$highestColumn = $sheet->getHighestColumn();


        //Loop through each row of the worksheet in turn
				for ($row = 2; $row <= $highestRow; $row++){ 
                //  Read a row of data into an array
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                //Insert into database
					echo '<br>';
					echo 'nouvelle ligne : ';
					print_r($rowData);
				}
			}
			else{
				echo "Please upload an XLS, XLSX or ODS file " . $extension . " given" ;
			}
		}
		else{
			echo $_FILES['spreadsheet']['error'];
		}
	}
}

//------ Following is used to fetch and save images
// $count = 1;
// foreach ($objPHPExcel->getSheetByName("Photographs")->getDrawingCollection() as $drawing) {
// 	if ($drawing instanceof PHPExcel_Worksheet_MemoryDrawing) {
// 		ob_start();
// 		call_user_func(
// 			$drawing->getRenderingFunction(),
// 			$drawing->getImageResource()
// 		);
// 		$imageContents = ob_get_contents();
// 		//Save graph image
// 		$fp = @fopen("picture".$count.".png" , "w");
// 		@fwrite($fp , $imageContents);
// 		@fclose($fp);
// 		ob_end_clean();
// 		$count++;
// 	}
// }


?>