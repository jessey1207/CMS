<!-- Manages interactions with database for incident reports -->
<!-- ======================================================================= -->
<?php
include_once'connect_db.php';
/* Class for database communication */
class IncidentMgr {
	/* returns all incident reports */
	public function getAllIncid() {
		$allData = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php',
			'fetch_data',
			'rid,create_date_time,assistance,postal_code,address','incident_report',
			Null);
    return $allData;
	}

	/* returns all resolved incident reports */
	public function getResolvedIncid() {
		$resData = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php',
			'fetch_data',
			'rid,create_date_time,assistance,postal_code,address','incident_report',
			'status=Resolved');
    return $resData;
	}

	/* returns all unresolved incident reports */
	public function getUnresolvedIncid() {
		$unresData = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php',
			'fetch_data',
			'rid,create_date_time,assistance,postal_code,address','incident_report',
			'status=Unresolved');
		return $unresData;
	}

	/* returns data of specified incident */
	public function getIncid(string $id) {
		$sql = 'rid='.$id;
		$incData = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php',
			'fetch_data',
			'*','incident_report',$sql);
		return $incData;
	}

	/* set specified incident to resolved */
	public function resolve(string $id) {
		$sql='rid='.$id;
		if (sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php',
					'data_verification',
					'status=Resolved','incident_report',$sql)['status']==TRUE) {
			$message = "This incident has already been resolved.";
		} else {
			sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php',
				'alter_data',
				'status=Resolved','incident_report',$sql);
			$message = "This incident is now resolved.";
		}
		/* notify user whether status has been changed */
		echo "<script type='text/javascript'>alert('$message');</script>";
		echo "<script type='text/javascript'>
			window.location= '/incidents/incident_reports.php';</script>";
		return;
	}
}

$IncidentMgr = new IncidentMgr();
/* operator requesting a specific incident to be resolved */
if(isset($_POST['formID'])){
	$formnum=$_POST['formID'];
      $IncidentMgr->resolve($formnum);
}

?>
