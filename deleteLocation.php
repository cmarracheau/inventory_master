<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$LocationID = htmlentities($_POST['locationDetailsLocationID']);
	
	if(isset($_POST['locationDetailsLocationID'])){
		
		// Check if mandatory fields are not empty
		if(!empty($LocationID)){
			
			// Sanitize item number
			$LocationID = filter_var($LocationID, FILTER_SANITIZE_STRING);

			// Check if the item is in the database
			$locationSql = 'SELECT LocationID FROM Location WHERE LocationID=:LocationID';
			$locationStatement = $conn->prepare($locationSql);
			$locationStatement->execute(['LocationID' => $LocationID]);
			
			if($locationStatement->rowCount() > 0){
				
				// Item exists in DB. Hence start the DELETE process
				$deleteLocationSql = 'DELETE FROM Location WHERE LocationID=:LocationID';
				$deleteLocationStatement = $conn->prepare($deleteLocationSql);
				$deleteLocationStatement->execute(['LocationID' => $LocationID]);

				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Item deleted.</div>';
				exit();
				
			} else {
				// Location does not exist, therefore, tell the user that he can't delete that item 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Item does not exist in DB. Therefore, can\'t delete.</div>';
				exit();
			}
			
		} else {
			// Location is empty. Therefore, display the error message
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter the item number</div>';
			exit();
		}
	}
?>