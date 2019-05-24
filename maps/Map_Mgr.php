<?php
require_once "connect_db.php";
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

class Map_Mgr
{
	private $finalData = array();

	function __construct(){
		//$this->register_observer();
		//$this->push_mapdata();
		
	}
	private function register_observer(){
		echo "Yes";
        sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','register',Null,'observer_list','address=172.21.146.197/Map/Map_Mgr.php,listen=weather');
        sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','register',Null,'observer_list','address=172.21.146.197/Map/Map_Mgr.php,listen=dengue');
        sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','register',Null,'observer_list','address=172.21.146.197/Map/Map_Mgr.php,listen=CD_shelter');
        sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','register',Null,'observer_list','address=172.21.146.197/Map/Map_Mgr.php,listen=haze');
        sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','register',Null,'observer_list','address=172.21.146.197/Map/Map_Mgr.php,listen=incident_report');
    }



    public function write_textfile($arr){
    	if($arr['table']=='incident_report' || $arr['table']=='CD_shelter'){
    		$arr=$arr['row'];
    		$arr=json_decode($arr,true);
    	}
    	else{
    		$arr=$arr['data'];
    		$temp=explode('=',$arr);
    		$arr=json_decode($temp[1],true);
    	}
    	print_r($arr);
    	switch($arr[0][0]) {
    		case 0:
    			$haze = fopen('haze.txt','w'); //dirname(__FILE__) .
    			$data1 = json_encode($arr);
				fwrite($haze, $data1);
				fclose($haze);
				break;
			case 1:
    			$weather = fopen('weather.txt','w');
    			$data2 = json_encode($arr);
				fwrite($weather, $data2);
				fclose($weather);
				break;
			case 2:
    			$dengue = fopen('dengue.txt','w');
    			$data3 = json_encode($arr);
				fwrite($dengue, $data3);
				fclose($dengue);
				break;
			case 3:
    			$incident = fopen('incident.txt','w');
    			$data4 = json_encode($arr);
				fwrite($incident, $data4);
				fclose($incident);
				break;
			case 4:
    			$cds = fopen('cds.txt','w');
    			$data5 = json_encode($arr);
				fwrite($cds, $data5);
				fclose($cds);
				break;
			default:
				echo "ERROR!";
				break;

    	}
    	$this->push_mapdata();
    	
    }

    private function push_mapdata(){

    	$mapData = array();
    	echo "IM HERE!!!!!!!!!!";
    	$data1 = fopen('haze.txt', "r"); //dirname(__FILE__) .
    	while(!feof($data1)){
    		$line1 = fgets($data1);
    	}
    	fclose($data1);
		$hazeArr = json_decode($line1, TRUE);
		print_r($hazeArr);
		//echo $hazeArr;
		
		$data2 = fopen('weather.txt', "r");
		while(!feof($data2)){
    		$line2 = fgets($data2);
    	}
    	fclose($data2);
		$weatherArr = json_decode($line2, TRUE);

		$data3 = fopen('dengue.txt', "r");
		while(!feof($data3)){
    		$line3 = fgets($data3);
    	}
    	fclose($data3);
		$dengueArr = json_decode($line3, TRUE);

		$data4 = fopen('incident.txt', "r");
		while(!feof($data4)){
    		$line4 = fgets($data4);
    	}
    	fclose($data4);
		$incidentArr = json_decode($line4, TRUE);

		$data5 = fopen('cds.txt', "r");
		while(!feof($data5)){
    		$line5 = fgets($data5);
    	}
    	fclose($data5);
    	$cdsArr = json_decode($line5, TRUE);

    	$mapData[0] = $hazeArr;
    	$mapData[1] = $weatherArr;
    	$mapData[2] = $dengueArr;
    	$mapData[3] = $incidentArr;
    	$mapData[4] = $cdsArr;
    	$this->finalData = $mapData;

    	return $mapData;
    }

    public function getMapData(){
    	return $this->finalData;
    }
}
//echo "T1";
/*$Map_Mgr = new Map_Mgr;
	if($_POST){
		$Map_Mgr->write_textfile($_POST);
	}*/

/*
	$temp = array(
			array(1,2,3,mus,1),
			array(1,2,3,mrwu,1)
			);

	$f = fopen('new.txt', w);
	fwrite($f, json_encode($temp));
	fclose($f);

*/
$Map_Mgr = new Map_Mgr;
while(true){
	if($_POST){
		$newData = $_POST;
		//$message = json_decode($newData, TRUE);
		$Map_Mgr->write_textfile($newData); //$newData
		echo "Complete";
		return;
	}
	$data = $Map_Mgr->getMapData();
	$data = json_encode($data);
	echo "data: $data"."\n\n";
	echo PHP_EOL;
	ob_end_flush();	
	flush();
	sleep(1);
}


//echo json_encode($Map_Mgr->getMapData());

//if(TRUE){
	//$array = $_POST['array'];
	//$message = json_decode($array, TRUE);
	//write_textfile($message);
	//$result = $Map_Mgr->forwardRequest("http://localhost/CMS/index.php", $Map_Mgr->getMapData());
	//session_start();
	//$_SESSION['array'] = $Map_Mgr->getMapData();
	//print_r($result['response']);
   //if ($result['status'] === 200) {
     //   header('Location: http://localhost/CMS/index.php');
    /*} /*else {
        header('Location: http://www.example.com/error.php');
    }*/
//}
?>