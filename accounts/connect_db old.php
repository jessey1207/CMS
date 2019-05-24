<?php
    function sender($token,$url,$execute,$select,$from,$where){
        if($from != null){
            $fields = array(
                 'token' => $token,
                 'function' => $execute,
                 'select'=>$select,
                 'from'=>$from,
                 'where'=>$where);
        }
        else{
            return "You must specify the table to operate.";
        }
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result= curl_exec($ch);
        //echo $result;
        $trial=json_decode($result,TRUE);
	//print_r($trial);
	//print_r(json_decode($trial['message'],TRUE)); //for fetch_data to decode message
	return $trial;
        //print_r($trial); //return array with 3 keys, status for query success/fail, message for fetch_data's raw data or error message when status=false.
        
    }
    //sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','alter_data',Null,'PSI','north=99,south=16,east=190,west=231');
    /*  
    sender($token,           $url,                             $execute,     $select,$from,$where)
    sender('Dasebase-#6675','192.168.64.2/db/database_mgr.php','delete_data',Null,'account','user_name=HR'); 
    ||fetch_data: (attribute(*, s), table, condition(Null,s)) ******e.g '*','account','is_admin=1' 
    ||insert_data: (Null, table, value) ******e.g Null,'CD_shelter','postal_code=123,address=city'
    ||data_verification: (verify,table, condition(m)) ******e.g 'postal_code=98765','CD_shelter','establish_date=1990-12-1'
    ||alter_data:(value ,table,condition(m)) ******e.g 'user_name=LAB','account','email=99@gmail.com'
    ||delete_data:(Null, table, condition(m)) ******e.g Null,'account','user_name=HR'
    ||register:(Null,'observer_list',value) *******e.g  address=192.168.64.2/db/database.php,cat=map
    */
    /*
    account(user_name=,email=,password=,is_admin=1/0) 
    CD_shelter(postal_code,address,description,establish_date,aid)
    incident_report(first_name,last_name,phone_number,postal_code,address,assistance,description,create_date_time=NULL,status,resolve_date_time,aid
    subscription_list(phone_number,start_date=NULL,aid)
    dengue(the_date=NULL,no_of_case,raw_data)
    PSI(date_time=NULL,north,south,east,west)
    weather(date_time=NULL,temperature)
    resetpw(email,token)
    observer_list(address,cat)*/
    /*   $email="1923@gmail.com";
    $token="31899831U";
    sender('Dasebase-#6675','192.168.64.2/db/database_mgr.php','insert_data',NULL,'resetpw',"email=$email,token=$token");*/
