<?php

class LoginMgr{
    public function logIn(){
        include_once'connect_db.php';
        $username =$_POST['username'];
        $password= $_POST['password'];
        $passwordmd= md5($_POST['password']);
        $domain = $_POST['domain'];

        if (!strcmp($domain,"Admin")){
            $admin = 1;
        }
        else{
            $admin = 0;
        }
        //Error handlers
        //Check for empty fields
        if(empty($username) || empty($password)){
            $_SESSION['emptydata'] = 1;
            header("Location: ../accounts/appearance/index.php");
            exit();
        }

        else{
            //check if all fields exist in database
            //echo $passwordmd;
            $result = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','data_verification',"password=$passwordmd",'account',"user_name=$username, is_admin = $admin");
            $resultCheck = $result['status'];
            //if no such user,direct to login page
            if($resultCheck){
		      $_SESSION['username'] = $username;
                $_SESSION['domain'] = $domain;
                //Admin login
                if(!strcmp($domain,"Admin")){
                    header("Location: ../accounts/appearance/mhaHome.php");
                    exit();
                }
                //CCO Login
                elseif(!strcmp($domain,"CCO")){
                    header("Location: ../accounts/appearance/ccoHome.php");

                }
                
            }
            else{
		$_SESSION['wrongdata'] = 1;
                header("Location: ../accounts/appearance/index.php");
                exit();
                
            }
        }
    }
}
    if(!isset($_SESSION)){
	session_start();
    }
    $LoginMgr = new LoginMgr();

    if(isset($_POST['login'])){
        $LoginMgr->logIn();
    }
?>
