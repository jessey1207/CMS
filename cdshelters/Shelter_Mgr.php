<!-- Manages interactions with database for Civil Defence Shelters -->
<!-- ======================================================================= -->
<?php
include_once'connect_db.php';
/* Class for database communication */
class ShelterMgr {
	/* inserts new shelter into database */
	public function createShelter() {
		$convertPCToCoor = file_get_contents('https://developers.onemap.sg/commonapi/search?searchVal='.strval($_POST['postcode']).'&returnGeom=Y&getAddrDetails=N&pageNum=1');
		$convertPCToCoor = json_decode($convertPCToCoor,true);
		$lat = $convertPCToCoor['results'][0]['LATITUDE'];
		$long = $convertPCToCoor['results'][0]['LONGITUDE'];

		$sql='postal_code='.$_POST['postcode'].',address='.$_POST['address'].
			',description='.$_POST['extra'].
			',establish_date='.$_POST['dateEstablished'].
			',latitude='.$lat.
			',longitude='.$long;
		sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php',
			'insert_data',Null,'CD_shelter',$sql);
		/* notify user whether shelter has been created */
		echo "<script type='text/javascript'>alert('Shelter created successfully.');
			</script>";
		echo "<script type='text/javascript'>
			window.location= '/cdshelters/cd_shelters.php';</script>";
		return;
	}

	/* updates the existing shelter in the database with new changes */
	public function updateShelter() {

		$convertPCToCoor = file_get_contents('https://developers.onemap.sg/commonapi/search?searchVal='.strval($_POST['postcode']).'&returnGeom=Y&getAddrDetails=N&pageNum=1');
		$convertPCToCoor = json_decode($convertPCToCoor,true);
		$lat = $convertPCToCoor['results'][0]['LATITUDE'];
		$long = $convertPCToCoor['results'][0]['LONGITUDE'];

		$condition='postal_code='.$_POST['prevPostcode'];
		$sql='postal_code='.$_POST['postcode'].',address='.$_POST['address'].
			',description='.$_POST['extra'].
			',establish_date='.$_POST['dateEstablished'].
			',latitude='.$lat.
			',longitude='.$long;
		sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php',
			'alter_data',$sql,'CD_shelter',$condition);
		/* notify user whether shelter has been updated */
		echo "<script type='text/javascript'>alert('Shelter updated successfully.');
			</script>";
		echo "<script type='text/javascript'>
			window.location= '/cdshelters/cd_shelters.php';</script>";
		return;
	}
}

$ShelterMgr = new ShelterMgr();
/* check whether user needs a shelter created or updated */
if(isset($_POST['postcode'])){
	if($_POST['prevPostcode']=='000000') {
		$ShelterMgr->createShelter();
	} else {
		$ShelterMgr->updateShelter();
	}
}
?>
