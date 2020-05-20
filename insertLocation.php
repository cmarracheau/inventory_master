<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$initialStock = 0;
	$baseImageFolder = '../../data/item_images/';
	$itemImageFolder = '';
	
	if(isset($_POST['LocationDetailsItemLocationID'])){
		
		$LocationID = htmlentities($_POST['LocationDetailsID']);
		$CustomerID = htmlentities($_POST['LocationDetailsCustomerID']);
		$LocationName = htmlentities($_POST['LocationDetailsLocationName']);
		$Address = htmlentities($_POST['LocationDetailsAddress']);
		$City = htmlentities($_POST['LocationDetailsCity']);
		$Country = htmlentities($_POST['LocationDetailsCountry']);
        $Phone = htmlentities($_POST['LocationDetailsPhone']);
        $Email = htmlentities($_POST['LocationDetailsEmail']);
        $Ofrate = htmlentities($_POST['LocationDetailsOfrate']);
        $K9rate = htmlentities($_POST['LocationDetailsK9rate']);
        $Stime = htmlentities($_POST['LocationDetailsSTime']);
        $Etime = htmlentities($_POST['LocationDetailsEtime']);
		
		// Check if mandatory fields are not empty
		if(!empty($LocationID) && !empty($CustomerID) && !empty($LocationName) && !empty($Address) && !empty($City) && !empty($Country) && isset($Ofrate) && isset($K9rate)){
			
			// Sanitize Location ID
			$LocationID = filter_var($LocationID, FILTER_SANITIZE_STRING);
			
			// Validate Officer Rate. It has to be a number
			if(filter_var($Ofrate, FILTER_VALIDATE_INT) === 0 || filter_var($Ofrate, FILTER_VALIDATE_INT)){
				// Valid Ofrate
			} else {
				// Officer Rate is not a valid number
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid number for quantity</div>';
				exit();
			}
			
			// Validate K9 rate. It has to be a number or floating point value
			if(filter_var($K9rate, FILTER_VALIDATE_FLOAT) === 0.0 || filter_var($K9rate, FILTER_VALIDATE_FLOAT)){
				// Valid float (unit price)
			} else {
				// K9 rate is not a valid number
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid number for unit price</div>';
				exit();
			}
			
			// Validate discount only if it's provided
			'if(!empty($discount)){
				if(filter_var($discount, FILTER_VALIDATE_FLOAT) === false){
					// Discount is not a valid floating point number
					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid discount amount</div>';
					exit();
				}
			}'
			
			// Create image folder for uploading images
			'$itemImageFolder = $baseImageFolder . $itemNumber;
			if(is_dir($itemImageFolder)){
				// Folder already exist. Hence, do nothing
			} else {
				// Folder does not exist, Hence, create it
				mkdir($itemImageFolder);
			}'
			
			// Calculate the stock values
			$LocationSql = 'SELECT LocationID FROM Location WHERE LocationID=$LocationID';
			$LocationStatement = $conn->prepare($LocationSql);
			$LocationStatement->execute(['LocationID' => $LocationID]);
			if($LocationStatement->rowCount() > 0){
				//$row = $LocationStatement->fetch(PDO::FETCH_ASSOC);
				//$quantity = $quantity + $row['stock'];
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Item already exists in DB. Please click the <strong>Update</strong> button to update the details. Or use a different Item Number.</div>';
				exit();
			} else {
				// Location does not exist, therefore, you can add it to DB as a new Location
				// Start the insert process
				$insertLocationSql = 'INSERT INTO Location(LocationID, CustomerID,LocationName,Address, City,Country,Phone,Email,Ofrate,K9rate,Stime,Etime) VALUES(:LocationID, :CustomerID, :LocationName, :Address, :City, :Country, :Phone,:Email,:Ofrate,K9rate,Stime,Etime)';
				$insertLocationStatement = $conn->prepare($insertLocationSql);
				$insertLocationStatement->execute(['LocationID' => $LocationID, 'CustomerID' => $CustomerID, 'LocationName' => $LocationName, 'Address' => $Address, 'City' => $City, 'Country' => $Country, 'Phone' => $Phone,'Email'=> $Email,'Ofrate'=>$Ofrate,'K9rate'=>$K9rate,'Stime'=>$Stime,'Etime'=>$Etime]);
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Item added to database.</div>';
				exit();
			}

		} else {
			// One or more mandatory fields are empty. Therefore, display a the error message
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter all fields marked with a (*)</div>';
			exit();
		}
	}
?>