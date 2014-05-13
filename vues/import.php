<?php
if ($_SESSION['adm'] == 1) {
        ?>
        <div style="height: 45px;background-color: white;border-top: 2px solid #2db3e8;border-bottom-color: rgba(0,0,0,0.2);border-bottom: 1px solid rgba(0,0,0,0.1);">
            <div style="width: 1000px; margin: auto; padding-top: 10px;">
        <a href="<?php echo ROOTPATH; ?>/index.html">Index &nbsp;</a>
        <a href="<?php echo ROOTPATH; ?>/admin.html">Administration &nbsp;</a>
        <?php
        if (isset($_POST['idSociete'])) {
            echo "> <a href='societeadm." . $_POST['idSociete'] . ".html'>Société n°" . $_POST['idSociete'] . "</a>&nbsp; > Importer des produits";
        }
}

require 'lib/PHPExcel.php';
require_once 'lib/PHPExcel/IOFactory.php';
if(isset($_POST['idSociete'])){
?>
<div class="menuAccordion" style="padding: 0px; margin-top: 30px;color: #56595E; padding: 20px;">
<div class="width800">
	<form method="post" id="formImport" enctype="multipart/form-data">
		Upload File: <input type="file" name="spreadsheet"/>
		<input type="submit" name="submit" value="Submit" />
                <input type="hidden" name="idSociete" value="<?php echo $_POST['idSociete']; ?>" />
	</form>
</div>
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

					if($codeProduit != ""){
						preg_match("/[a-zA-Z]+/", $codeProduit, $output);
						$idCategorie = $output[0];

						if (in_array($barCodeProduit, $allBarCodes)) {
							$query = "UPDATE produit SET codeProduit = '". $codeProduit ."', barCodeProduit = '". $barCodeProduit ."', libelleProduit = '". $libelleProduit ."', minQte = '". $quantiteProduit ."', prixProduit = '". $prixProduit ."', idSociete = '". $_POST['idSociete'] ."', idCategorie = '". $idCategorie ."'  WHERE  barCodeProduit = '". $barCodeProduit . "'";
                                                        $auth->exec($query);

                                                } elseif (!in_array($barCodeProduit, $allBarCodes)) {
							$query = $auth->prepare("INSERT INTO produit (codeProduit, barCodeProduit, libelleProduit, minQte, prixProduit, idSociete, idCategorie) VALUES (:codeProduit, :barCode, :libelleProduit, :quantiteProduit, :prixProduit, :idSociete, :idCategorie)");
                                                        $query->bindValue(":codeProduit", $codeProduit, PDO::PARAM_STR);
                                                        $query->bindValue(":barCode", $barCodeProduit, PDO::PARAM_STR);
                                                        $query->bindValue(":libelleProduit", $libelleProduit, PDO::PARAM_STR);
                                                        $query->bindValue(":quantiteProduit", $quantiteProduit, PDO::PARAM_INT);
                                                        $query->bindValue(":prixProduit", $prixProduit, PDO::PARAM_STR);
                                                        $query->bindValue(":idSociete", $_POST['idSociete'], PDO::PARAM_INT);
                                                        $query->bindValue(":idCategorie", $idCategorie, PDO::PARAM_STR);
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
    <div id="pleaseWait" style="display: none;position: fixed;left: 50%;top: 25%;border-radius: 5px;background-image: url(img/cream_pixels.png);margin-left: -193px;z-index: 999;width: 355px;box-shadow: inset 0px 1px 0 rgba(255,255,255,1);border: 1px solid;border-color: rgba(0,0,0,0.1);border-bottom-color: rgba(0,0,0,0.2); padding: 15px"><img src="img/ajax-loader.gif" alt="" /> &nbsp; Importation des données en cours... Veuillez patienter.</div>    
        
        <script>
            $("#formImport").submit(function(){
                $("body").append('<div style="position: fixed;left: 0px;top: 0px;background-color: rgb(235, 235, 228);height: 100%;width: 100%;z-index: 100;opacity: 0.7;"></div>');
                $("#pleaseWait").show();
            });
        </script>