<?php
require_once "database.php";
require_once "connect_db.php";
abstract class subject
{
    abstract protected function register($argument);
    abstract protected function unregister($address);
    abstract protected function notify($argument); //, $row_being_modify
}
class database_mgr extends subject
{
    public function __construct()
    {   
        $this->database= new database();
    }
    private function fetch_data($ar){
        return $this->database->fetch_data($ar);
    }
    private function insert_data($ar){
        return $this->database->insert_data($ar);
    }
    private function data_verification($ar){
        return $this->database->data_verification($ar);
    }
    private function alter_data($ar){
        return $this->database->alter_data($ar);
    }
    private function delete_data($ar){
        return $this->database->delete_data($ar);
    }
    private function token_verification($token){
        if($token=='Dasebase-#6675'){
            return True;
        }
        else{
            return False;
        }
    }
    public function communication($argument){
        if(array_key_exists('token',$argument)){ 
            if($this->token_verification($argument['token'])&&array_key_exists('function',$argument)){
                $query=$argument['function'];
                $table=$argument['from']; 
                if($query==="insert_data"){
                    if($this->check_duplicate($table,$argument)){
                        echo "Checking Success";
                        $query=$argument['function'];
                        $temp=$this->{$query}($argument);
                        print_r($temp);
                        $this->notify($argument); 
                    }
                    else{
                        echo "Duplicate Same Recording Reject.";
                    }
                }
                else{
                    $query=$argument['function'];
                    $temp=$this->{$query}($argument);
                    print_r($temp);
                }
            }
            else{
                echo "Token not right or didn't specify function.";
            }
        }
        else{
            echo "No token.";
        }
    }
    public function getALL(){
        print_r($this->fetch_data(array('select'=>"*",'from'=>"incident_report",'where'=>Null)));
        print_r($this->fetch_data(array('select'=>"*",'from'=>"CD_shelter",'where'=>Null)));
    }
    private function function_call($argument){
        $query=$argument['function'];
        return $this->{$query}($argument);
    }
    protected function register($argument){
        echo $this->insert_data($argument);
    }
    protected function unregister($argument){
        echo $this->delete_data($argument);
    }
    protected function notify($argument){ //function, table, $row_being_modify
        $query=$argument['function'];
        $table=$argument['from'];
        $where=$argument['where'];
        $special_case=null;
        if($query=='insert_data'){ //only perform notification when db has operation or $query== 'delete_data'
            $temp=$this->data_verification(array('select'=>"listen=$table",'from'=>"observer_list",'where'=>"listen=$table")); //check if the table being perform has listener
            // print_r($this->fetch_data(array('select'=>"address",'from'=>"observer_list",'where'=>"listen=$table")));
            if(json_decode($temp,True)['status']){ // sample {"message":"Verify Pass","connection":"Datebase connection establish success","status":true}, need to decode and get status.
                $sending_list=json_decode(json_decode($this->fetch_data(array('select'=>"address",'from'=>"observer_list",'where'=>"listen=$table")),True)['message'],True);//fetch the listener address subscribe to current table operation. Fetch data must decode 2 times for message content.
                if($argument['from']=="incident_report"){
                    $special_case=json_encode($this->to_map_format_for_incident());
                }
                else if($argument['from']=="CD_shelter"){
                    $special_case=json_encode($this->to_map_format_for_cds());
                }
                $content_to_send=$where;
                /*                if($query=='insert_data'){
                    $row_being_modify=$this->fetch_data(array('select'=>"*",'from'=>"$table",'where'=>"$where"));
                }*/

                for($i=0;$i<count($sending_list);$i++){
                    echo sender('Sub-Com-#1000',$sending_list[$i]['address'],$query,$content_to_send,$table,$special_case); // pass in token, sending address, what's the function perform, content and which table it operate.
                    echo "<br>------------<br>";
                }
            }
        }
    }
    protected function check_duplicate($table,$check){
        if($table=="dengue"||$table=="weather"||$table=="haze"){
            $temp=$this->fetch_data(array('select'=>"*",'from'=>"$table",'where'=>Null));
            echo $table." And ".$check['from'];
            $temp_message=json_decode($temp,true)['message'];
            $temp_rawdata=json_decode($temp_message,true);
        // print_r($temp_rawdata);
            $check_raw=$check['where'];
            $c=0;
            if($temp_rawdata){
                for($i=0;$i<count($temp_rawdata);$i++){
                    $temp_last=$temp_rawdata[$i]['raw_data'];
                    if($temp_last===trim($check_raw,'raw_data=')){ //strcmp($temp_last,trim($check_raw,'raw_data='))||
                        $c+=1;
                        echo "************";
                        echo "Yes, same\n";
                    }
                    else{
                        echo "--------------";
                        echo "Not the same\n";
                    }
                }
            }
         //   echo $c;
            if($c==0){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return true;
        }
    }
    private function to_map_format_for_incident(){
        $mapData = array();
        $raw=$this->function_call(array('function'=>'fetch_data','select'=>"*",'from'=>"incident_report",'where'=>"status=Unresolved"));
        $process1=json_decode($raw,true)['message'];
        $ar=json_decode($process1,true);
        print_r($ar);
        for($i = 0; $i<sizeof($ar); $i++)
        {
            $convertPCToCoor = file_get_contents('https://developers.onemap.sg/commonapi/search?searchVal='.strval($ar[$i]['postal_code']).'&returnGeom=Y&getAddrDetails=N&pageNum=1');
            $convertPCToCoor = json_decode($convertPCToCoor, true); 
            $lat = $convertPCToCoor['results'][0]['LATITUDE'];
            $lng = $convertPCToCoor['results'][0]['LONGITUDE'];
            $mapData[$i] = array(3, $lat, $lng, $ar[$i]['address']."<br>". $ar[$i]['description'], $i);
        }
        return $mapData;

    }

    private function to_map_format_for_cds(){
        $mapData = array();
        $raw=$this->function_call(array('function'=>'fetch_data','select'=>"*",'from'=>"CD_shelter",'where'=>Null));
        $process1=json_decode($raw,true)['message'];
        $ar=json_decode($process1,true);
        print_r($ar);
        for($i = 0; $i<sizeof($ar); $i++)
        {
            $mapData[$i] = array(4,  $ar[$i]['latitude'],  $ar[$i]['longitude'], $ar[$i]['address'], $i);
        }
        return $mapData;
    }




}    
    if($_POST){
        $d=new database_mgr();
        $d->communication($_POST);   
    }
 /*   if($_GET){
        $d=new database_mgr();
        $d-> getAll();
    }*/

