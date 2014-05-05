<?php 
require 'lib/PHPExcel.php';
require_once 'lib/PHPExcel/IOFactory.php';
if(isset($_POST['idCat'])){
?>

<div class="width800">
	<form method="post" enctype="multipart/form-data">
		Upload File: <input type="file" name="spreadsheet"/>
		<input type="submit" name="submit" value="Submit" />
                <input type="hidden" name="idSociete" value="<?php echo $_POST['idSociete']; ?>" />
                <input type="hidden" name="idCat" value="<?php echo $_POST['idCat']; ?>" />
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

		// Check if barCodeProduit exist in DB
				$allBarCodes = array();
				$sql = 'SELECT barCodeProduit FROM produit';    
				$req = $auth->query($sql);    
				while($row = $req->fetch()) {    
					$allBarCodes[] = $row['barCodeProduit'];  
				}    
				$req->closeCursor();

        //Loop through each row of the worksheet in turn
				for ($row = 2; $row <= $highestRow; $row++){ 
                //  Read a row of data into an array
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                //Insert into database
					$codeProduit = mysql_real_escape_string($rowData[0][0]);
					$libelleProduit = mysql_real_escape_string($rowData[0][1]);
                                        $quantiteProduit = $rowData[0][2];
					$prixProduit = $rowData[0][3];
					$barCodeProduit = mysql_real_escape_string($rowData[0][5]);

					if($codeProduit != "" && $barCodeProduit != ""){
						if (in_array($barCodeProduit, $allBarCodes)) {
							$query = "UPDATE produit SET codeProduit = '". $codeProduit ."', barCodeProduit = '". $barCodeProduit ."', libelleProduit = '". $libelleProduit ."', quantiteProduit = '". $quantiteProduit ."', prixProduit = '". $prixProduit ."', idSociete = '". $_POST['idSociete'] ."'  WHERE  barCodeProduit = '". $barCodeProduit . "'";
                                                        $auth->exec($query);
                                                        
                                                } elseif (!in_array($barCodeProduit, $allBarCodes)) {
							$query = $auth->prepare("INSERT INTO produit (codeProduit, barCodeProduit, libelleProduit, quantiteProduit, prixProduit, idSociete, idCategorie) VALUES (:codeProduit, :barCode, :libelleProduit, :quantiteProduit, :prixProduit, :idSociete, :idCat)");
                                                        $query->bindValue(":codeProduit", $codeProduit, PDO::PARAM_STR);
                                                        $query->bindValue(":barCode", $barCodeProduit, PDO::PARAM_STR);
                                                        $query->bindValue(":libelleProduit", $libelleProduit, PDO::PARAM_STR);
                                                        $query->bindValue(":quantiteProduit", $quantiteProduit, PDO::PARAM_INT);
                                                        $query->bindValue(":prixProduit", $prixProduit, PDO::PARAM_STR);
                                                        $query->bindValue(":idSociete", $_POST['idSociete'], PDO::PARAM_INT);
                                                        $query->bindValue(":idCat", $_POST['idCat'], PDO::PARAM_INT);
                                                        $query->execute();
                                                        
						} else {
							$query = 
                                                        $auth->exec($query);
						}
						
					}
					// else : ligne vide 
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

}
?>