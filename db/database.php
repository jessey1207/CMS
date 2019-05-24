<?php
class database
{
    public function __construct()
    {
        $this->message=array();
        $this->state=mysqli_connect('localhost','root','');
        $this->message["message"]="";
        if($this->state){
            $this->message["connection"]="Datebase connection establish success";
            $this->message["status"]=True;
            $this->construct_database();
            mysqli_select_db($this->state,'CMS');
        }
        else{
            $this->message["connection"]="Datebase connection establish fail";
            $this->message["status"]=False;
        }
    }
    private function construct_database(){
        mysqli_query($this->state,"CREATE DATABASE IF NOT EXISTS CMS CHARSET utf8");
        mysqli_select_db($this->state,'CMS');
        mysqli_query($this->state,"CREATE TABLE IF NOT EXISTS account (
            user_name VARCHAR(20) NOT NULL UNIQUE,
            email VARCHAR(20) NOT NULL PRIMARY KEY, 
            password VARCHAR(50) NOT NULL,
            is_admin BOOLEAN DEAFULT '0' NOT NULL)");
        mysqli_query($this->state,"CREATE TABLE IF NOT EXISTS subscription_list(
            phone_number INT(10) UNSIGNED NOT NULL PRIMARY KEY,
            start_date DATE NOT NULL)");
        mysqli_query($this->state,"CREATE TABLE IF NOT EXISTS weather(
            date_time DATETIME NOT NULL PRIMARY KEY, 
            raw_data MEDIUMTEXT NOT NULL)");
        mysqli_query($this->state,"CREATE TABLE IF NOT EXISTS dengue(
            date_time DATETIME NOT NULL PRIMARY KEY, 
            raw_data MEDIUMTEXT NOT NULL)");
        mysqli_query($this->state,"CREATE TABLE IF NOT EXISTS haze(
            date_time DATETIME NOT NULL PRIMARY KEY, 
            raw_data MEDIUMTEXT NOT NULL)");
        mysqli_query($this->state,"CREATE TABLE IF NOT EXISTS CD_shelter (
            postal_code INT(7) UNSIGNED NOT NULL PRIMARY KEY,
            address TEXT NOT NULL, 
            description TEXT,
            establish_date DATE, 
            email VARCHAR(20) NOT NULL, 
            latitude DOUBLE UNSIGNED NOT NULL, 
            longitude DOUBLE UNSIGNED NOT NULL)");
        mysqli_query($this->state,"CREATE TABLE IF NOT EXISTS incident_report (
            rid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(20) NOT NULL,
            last_name VARCHAR(20) NOT NULL, 
            phone_number INT(10) UNSIGNED NOT NULL, 
            postal_code INT(7) UNSIGNED NOT NULL,
            address TEXT NOT NULL,
            assistance ENUM('Fire-Fighting','Rescue and Evacuation','Emergency Ambulance','Gas Leak Control') NOT NULL,
            description TEXT, 
            create_date_time DATETIME NOT NULL, 
            status ENUM('Unresolved','Resolved') DEFAULT 'Unresolved' NOT NULL,
            resolve_date_time DATETIME DEFAULT NULL,
            email VARCHAR(20) NOT NULL)");
        mysqli_query($this->state,"CREATE TABLE IF NOT EXISTS resetpw(
            id INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY, 
            email VARCHAR(20) NOT NULL,
            token VARCHAR(255) NOT NULL UNIQUE,
            FOREIGN KEY(email) REFERENCES account(email) ON UPDATE CASCADE ON DELETE CASCADE)");
        mysqli_query($this->state,"CREATE TABLE IF NOT EXISTS observer_list(
            address VARCHAR(255) NOT NULL, 
            listen VARCHAR(20) NOT NULL,
            PRIMARY KEY(address,listen))");
        mysqli_query($this->state,"
            CREATE TRIGGER no_empty_insert_account BEFORE INSERT ON account 
            FOR EACH ROW 
            BEGIN 
            IF NEW.user_name='' THEN SET NEW.user_name=NULL;
            ELSEIF NEW.password='' THEN SET NEW.password=NULL;
            ELSEIF NEW.is_admin=Null THEN SET NEW.password=0;
            end if;
            end;");   
        mysqli_query($this->state,"
            CREATE TRIGGER no_empty_alter_account BEFORE UPDATE ON account 
            FOR EACH ROW 
            BEGIN 
            IF NEW.user_name='' THEN SET NEW.user_name=NULL;
            ELSEIF NEW.password='' THEN SET NEW.password=NULL;
            end if;
            end;"); 
        mysqli_query($this->state,"
            CREATE TRIGGER no_repeat_report BEFORE INSERT ON incident_report 
            FOR EACH ROW 
			BEGIN
            DECLARE old_date DATETIME;
            select create_date_time
            into old_date
            from incident_report
            where first_name= new.first_name and last_name=new.last_name and phone_number=new.phone_number and postal_code=new.postal_code and address=new.address and assistance=new.assistance and description=new.description and status=new.status and resolve_date_time=new.resolve_date_time;
            IF TIMESTAMPDIFF(SECOND,new.create_date_time,old_date) < 86400
            then signal sqlstate '45000' SET MESSAGE_TEXT = 'No same record in one day';
            end if;");

        if(mysqli_error($this->state)){
         $this->message["message"]=mysqli_error($this->state);
         return $this->message;
        }
    }
    public function fetch_data($content){ 
        $ar=array();
        if($content['where']!=Null){
            $w=$this->where_parsing($content['where']);
            $w="WHERE ".$w;
        }
        else{
            $w="";
        }
        $result = mysqli_query($this->state, "SELECT $content[select] FROM $content[from] ".$w);
        if(empty($result)==False and mysqli_num_rows($result)!=0){
            for($i=0;$i<mysqli_num_rows($result);$i++) {
                //if($content['from']=='dengue'||$content['from']=='weather'){
                    $tp=mysqli_fetch_assoc($result);
                    foreach ($tp as $key=>$value)
                   // if($content['from']=='dengue'){
                        $tp[$key]=str_replace("~","'",$tp[$key]); //replace ~ to '  $tp['raw_data']=str_replace("~","'",$tp['raw_data']);
                    //}
                        $tp[$key]=str_replace("&","\\",$tp[$key]); //replace & to \, additional escape require
                    array_push($ar,$tp);
              //  }
              //  else{
               //     array_push($ar, mysqli_fetch_assoc($result));
               // }
            }
            $this->message["status"]=True;
            $this->message["message"]=json_encode($ar);
        }
        else{
            $this->message["status"]=False;
            $this->message["message"]="No record";
        }
        return json_encode($this->message);
    }

    public function insert_data($ar) 
    {
        $t = date("Y-m-d H:i:s", time());
        $d = date("Y-m-d", time());
        $value=array();
        if($ar['from']=='haze' || $ar['from']=='weather'||$ar['from']=='dengue'){
            $store=explode("=",$ar['where']);
            $store[1]=str_replace("'","~",$store[1]);
            $store[1]=str_replace('\\','&',$store[1]);
            $value[trim($store[0])]=$store[1];
        }
        else{
         $temp=explode(",",$ar['where']);
         for($i=0;$i<count($temp);$i++){
            $store=explode("=",$temp[$i]);
            $store[1]=str_replace("'","~",$store[1]);
            $store[1]=str_replace('\\','&',$store[1]);
            $value[trim($store[0])]=trim($store[1]);
         }
        }
        switch ($ar['from']) {
            case "account":
                mysqli_query($this->state, "INSERT INTO account(user_name,email,password,is_admin) VALUES ('$value[user_name]','$value[email]','$value[password]','$value[is_admin]')") ? $this->message["status"]=True : $this->message["status"]=False;
                break;
            case "subscription_list":
                mysqli_query($this->state, "INSERT INTO subscription_list(phone_number,start_date) VALUES ($value[phone_number],'$d')") ? $this->message["status"]=True : $this->message["status"]=False; 
                break;
            case "weather":
                mysqli_query($this->state, "INSERT INTO weather(date_time,raw_data) VALUES ('$t','$value[raw_data]')") ? $this->message["status"]=True : $this->message["status"]=False; 
                break;
            case "dengue":
                mysqli_query($this->state, "INSERT INTO dengue(date_time,raw_data) VALUES ('$t','$value[raw_data]')") ? $this->message["status"]=True : $this->message["status"]=False; 
                break;
            case "haze":
                mysqli_query($this->state, "INSERT INTO haze(date_time,raw_data) VALUES ('$t','$value[raw_data]')") ? $this->message["status"]=True : $this->message["status"]=False; 
                break;
            case "CD_shelter":
                mysqli_query($this->state, "INSERT INTO CD_shelter(postal_code,address,description,establish_date,email,latitude,longitude) VALUES ('$value[postal_code]','$value[address]','$value[description]','$value[establish_date]','banana@monkey.com','$value[latitude]','$value[longitude]')") ? $this->message["status"]=True : $this->message["status"]=False; 
                break;
            case "incident_report":
                mysqli_query($this->state, "INSERT INTO incident_report(first_name,last_name,phone_number,postal_code,address,assistance,description,create_date_time,email) VALUES ('$value[first_name]','$value[last_name]','$value[phone_number]','$value[postal_code]','$value[address]','$value[assistance]','$value[description]','$t','ahlee980729@gmail.com')") ? $this->message["status"]=True : $this->message["status"]=False; 
                break;
            case "resetpw":
                mysqli_query($this->state, "INSERT INTO resetpw(email,token) VALUES ('$value[email]','$value[token]')") ? $this->message["status"]=True : $this->message["status"]=False; 
                break;
            case "observer_list":
                mysqli_query($this->state, "INSERT INTO observer_list(address,listen) VALUES ('$value[address]','$value[listen]')") ? $this->message["status"]=True : $this->message["status"]=False; 
                break;
            default:
                $this->message["status"]=False;;
        }
        if($this->message["status"]==False){
            $this->message["message"]=mysqli_error($this->state);
        }
        else{
            $this->message["message"]=$ar['from']." insert successfully.";
        }
        return json_encode($this->message);
    }
    public function data_verification($content){ 
        $target = $this->where_parsing($content['where']);
        $check = explode("=",$content['select']);
        $result=mysqli_query($this->state,"SELECT $check[0] FROM $content[from] WHERE $target");
        if($result and mysqli_num_rows($result)>0) {
            for ($i = 0; $i < mysqli_num_rows($result); $i++) {
             //   $st=array("check0"=>trim(mysqli_fetch_assoc($result)[$check[0]]),"check1"=>$check[1]);
              //  return json_encode($st);
                if (trim(mysqli_fetch_assoc($result)[$check[0]]) == $check[1]) {
                    $this->message["status"]=True;
                    $this->message["message"]= "Verify Pass";
                } else {
                    $this->message["status"]=False;
                    $this->message["message"]= "Verify Fail";
                }
            }
        }
        else{
            $this->message["status"]=False;
            $this->message["message"]= "No record";
        }
        return json_encode($this->message);
    }
    public function alter_data($content){
        $s=$this->select_parsing($content['select']);
        $w=$this->where_parsing($content['where']);
        $result=mysqli_query($this->state,"UPDATE $content[from] SET $s WHERE $w");
        mysqli_error($this->state)!=Null ? $this->message["status"]=False : $this->message["status"]=True ;

        if($this->message["status"]==False){
            $this->message["message"]=mysqli_error($this->state);
        }
        else{
            $this->message["message"]="Change made successfully.";
        }
        return json_encode($this->message);
    }
    public function delete_data($content){
        $temp = explode(",",$content['where']);
        $w=$this->where_parsing($content['where']);
        $result=mysqli_query($this->state,"DELETE FROM $content[from] WHERE $w");
        mysqli_error($this->state)!=Null ? $this->message["status"]=False : $this->message["status"]=True ;
        if($this->message["status"]==False){
            $this->message["message"]=mysqli_error($this->state);
        }
        else{
            $this->message["message"]="Delete successfully.";
        }
        return json_encode($this->message);
    }
    private function select_parsing($content){
        $temp = explode(",",$content);
        $s='';
        for($i=0;$i<count($temp);$i++){
            $temp2 = explode("=", $temp[$i]);
            $s= $s.$temp2[0]."='".$temp2[1]."'";
            if($i!=(count($temp)-1)){
                $s=$s.",";
            }
        }
        return $s;
    }
    private function where_parsing($content){
        $temp = explode(",",$content);
        $s='';
        for($i=0;$i<count($temp);$i++){
            $temp2 = explode("=", $temp[$i]);
            $s= $s.$temp2[0]."='".$temp2[1]."'";
            if($i!=(count($temp)-1)){
                $s=$s." AND ";
            }
        }
        return $s;
    }
}
