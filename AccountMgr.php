<?php
include_once'connect_db.php';
class AccountMgr{

    public function createAcc(){
        include_once'connect_db.php';
        //include_once'../communication/communication_mgr.php';
        $username = $_POST['username'];
        $email= $_POST['email'];
        $domain = "CCO";
        $password = uniqid(true);
        $hashed = md5($password);
        //check if data inserted is empty
        if(empty($email) || empty($username)){
                $_SESSION['emptydata'] = 1;
                header("Location: ../accounts/appearance/setUp.php");
                exit();
        }
        //verify if email exists in database
        $result = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','data_verification',"email=$email",'account',"email=$email");
        $resultCheckEmail = $result['status'];

        //verify if username exists in database
        $result = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','data_verification',"user_name=$username",'account',"user_name=$username");
        $resultCheckUsername = $result['status'];
        
        //If account already exist, redirect to previous page
        if($resultCheckEmail){
            $_SESSION['emailused'] = 1;
            header("Location: ../accounts/appearance/setUp.php");
            exit(); 
        }
        elseif($resultCheckUsername){
            $_SESSION['usernameused'] = 1;
            header("Location: ../accounts/appearance/setUp.php");
            exit(); 
        }

        else{
          //add information to database
            $insert = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','insert_data',Null,'account',"user_name=$username,email=$email,password=$hashed,is_admin=0"); 

            $result = $insert['status'];

            if ($result){
                //try{
                    //send email
                    // email->__construct();
                    // $ar[0] = $email;
                    // email->setRecipients($ar);
                    // $url="http://172.21.146.197/accounts/appearance/index.php";
                    //    $subject = 'CMS Account Creation';0
                    //    $body    = " Your CMS account has been created.<br> Your username is '$username' <br> Password is '$password' and domain is CCO. <br> Please click <a href='$url'> this link </a> to login ";
                     //   email->sendMail($subject, $body);
                    $url="http://172.21.146.197/accounts/appearance/index.php";
                    $sentEmail = sender('Use-Com-#2000','172.21.146.197/communication/communication_mgr.php','send_email',$email,'Account Creation',"Your CMS account has been created.<br> Your username is '$username' <br> Password is '$password' and domain is CCO. <br> Please click <a href='$url'> this link </a> to login"); 
                    $_SESSION['accountcreated'] = 1;

                    header("Location: ../accounts/appearance/mhaHome.php");
                    exit();
                //}
/*                catch (Exception $e) {
                     $_SESSION['emailfail'] = 1;
                    header("Location: ../accounts/appearance/mhaHome.php");
                    exit();
                }*/
            }
            else{
                $_SESSION['failToCreate'] = 1; 
                header("Location: ../accounts/appearance/setUp.php");
                exit();
            }
        }
            
        //below is the original email function + content
/*            //send to user about account info
            $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
            try {
               // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';   
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'crisismanagement0001@gmail.com';                 // SMTP username
                $mail->Password = 'CMSAdmin123';                           // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
                $mail->setFrom('crisismanagement0001@gmail.com', 'CMS admin');
                $mail->addAddress($email,'CCO');     // Add a recipient

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

            //Content
                $url="http://172.21.146.197/accounts/appearance/index.php";
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'CMS Account Creation';
                $mail->Body    = " Your CMS account has been created.<br> Your username is '$username' <br> Password is '$password' and domain is CCO. <br> Please
                    click <a href='$url'> this link </a> to login ";
                $mail->send();
                $_SESSION['accountcreated'] = 1;
                header("Location: ../accounts/appearance/mhaHome.php");
                exit();
            }   
            catch (Exception $e) {
                 $_SESSION['emailfail'] = 1;
                header("Location: ../accounts/appearance/mhaHome.php");
                exit();
            }
*/
        
    }
    
    public function resetPW(){
        include_once'connect_db.php';
        $token = $_SESSION['token'];
        $newPW = $_POST["newPW"];
        $cfmNewPW = $_POST["cfmNewPW"];
        $hashedPW =md5($newPW);
        
        //check if empty
        if(empty($newPW) || empty($cfmNewPW)){
            $_SESSION['emptydata'] = 1;
            header("Location: ../accounts/appearance/resetPW.php?token=$token");
            exit();
        }
        //check if password too short
        if(strlen($newPW)<8){
            $_SESSION['pwtooshort'] = 1;
            header("Location: http://172.21.146.197/accounts/appearance/resetPW.php?token=$token");
            exit();
            }
        //check if both password matches
        elseif($newPW != $cfmNewPW){
            $_SESSION['notmatch'] = 1;
            header("Location: http://172.21.146.197/accounts/appearance/resetPW.php?token=$token");
            exit();
        }
        //retrieve email from database
        $fetch = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','fetch_data','email','resetpw',"token=$token");
        $result = json_decode($fetch['message'],TRUE);
        $email=$result[0]['email'];
        //update password for the account
        $update = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','alter_data',"password = $hashedPW",'account',"email=$email");
        $result = $update['status'];
        if($result){
            //delete used token from resetPW
            $delete = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','delete_data',Null,'resetpw',"token=$token");
            $result = $delete['status'];
            if ($result){
                $_SESSION['pwUpdated'] = 1;
                header("Location: ../accounts/appearance/index.php");
                exit();
            }
            else{
                exit("Something went wrong in delete");
            }
        }
        else{
            exit("Something went wrong in update");
        }
    }
    
    public function changePW(){
        include_once'connect_db.php';
        $username=$_SESSION['username'];
        $domain=$_SESSION['domain'];
        //change password
        if(isset($_POST['changePW'])){
            //check fields
            $oldPW= $_POST['oldPW'];
            $hashedOldPW= md5($_POST['oldPW']);
            $newPW = $_POST['newPW'];
            $cfmPW =$_POST['cfmNewPW'];
            
            //check whether data filled in is empty or not
            if(empty($oldPW) || empty($newPW) || empty($cfmPW)){
                $_SESSION['emptydata'] = 1;
                header("Location: ../accounts/appearance/changePW.php");
                exit();
            }
            
            //check whether old password is correct
            $result = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','data_verification',"password=$hashedOldPW",'account',"user_name=$username");
            $resultCheck = $result['status'];
            
            //wrong old password
            if(!$resultCheck){
                $_SESSION['wrongOldPW'] = 1;
                header("Location: ../accounts/appearance/changePW.php");
                exit();
            }
            
            //Checking if password meets requirement
            //password less than 8 char
            if(strlen($newPW)<8){
                $_SESSION['pwtooshort'] = 1;
                header("Location: ../accounts/appearance/changePW.php");
                exit();
            }
            //old password same as new password
            elseif($oldPW == $newPW){
                $_SESSION['same'] = 1;
                header("Location: ../accounts/appearance/changePW.php");
                exit();
            }
            //password does not match
            elseif($newPW != $cfmPW){
                $_SESSION['notmatch'] = 1;
                header("Location: ../accounts/appearance/changePW.php");
                exit();
            }

            //Begin changing of password
            //md5() used for hashing
            else{
                $hashedNewPW = md5($newPW);
                //updating to database
                $update = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','alter_data',"password = $hashedNewPW",'account',"user_name=$username");
                $result = $update['status'];

                if($result && $domain=="Admin"){
                    $_SESSION['pwchanged'] = 1;
                    header("Location: ../accounts/appearance/mhaHome.php");
                    exit();
                }

                elseif($result && $domain=="CCO"){
                    $_SESSION['pwchanged'] = 1;
                    header("Location: ../accounts/appearance/ccoHome.php");
                    exit();
                } 

                else{
                    //if password fail to change
                    $_SESSION['fail'] = 1;
                    header("Location: ../accounts/appearance/changePW.php");
                    exit();
                }
            }              
        }
    }
    
    public function forgetPW(){
        include_once 'connect_db.php';
        //include_once'../communication/communication_mgr.php';
        $token =uniqid(true);
        $_SESSION['token']=$token;

        $email=$_POST['email'];
        
        //if empty email is inserted
        if(empty($email)){
            $_SESSION['emptydata'] = 1;
            header("Location: ../accounts/appearance/forgetPW.php");
            exit();
        }
        
        //verify in database
        $result = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','data_verification',"email=$email",'account',"email=$email");
        $resultCheck = $result[status];

        //if no such user,direct to forgetPW page
        if(!$resultCheck){
            $_SESSION['emailNotFound'] = 1;
            header("Location: ../accounts/appearance/forgetPW.php");
            exit(); 
        }
        
        //Email found, send a link to user for password reset
        else{
            //insert to resetPW database
            $insert = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','insert_data',Null,'resetpw',"email=$email,token=$token");
            $result = $insert['status'];
            if($result){
                //try{
                    //send email
                    // email->__construct();
                    // $ar[0] = $email;
                    // email->setRecipients($ar);
                    // $url="http://172.21.146.197/accounts/appearance/resetPW.php?token=$token";
                    //    $subject = 'Password Reset';
                    //    $body    = " <h1>You requested a password reset, click <a href='$url'> this link </a> to do so";
                     //   email->sendMail($subject, $body);
                    $url="http://172.21.146.197/accounts/appearance/resetPW.php?token=$token";
                    sender('Use-Com-#2000','172.21.146.197/communication/communication_mgr.php','send_email',$email,'Reset Password',"You requested a password reset, click <a href='$url'> this link </a> to do so"); 
                    $_SESSION['emailSent'] = 1;
                    header("Location: ../accounts/appearance/index.php");
                    echo '<meta http-equiv="Refresh" content="0;url=appearance/index.php ">';
                    exit();
/*                }
                catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }*/
            }
            
            
        //below is the original email function + content
/*            $mail = new PHPMailer(true);                              // Passing `true` enables exception
           try {
               // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';   
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'crisismanagement0001@gmail.com';                 // SMTP username
                $mail->Password = 'CMSAdmin123';                           // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
                $mail->setFrom('crisismanagement0001@gmail.com', 'CMS admin');
                $mail->addAddress($email,'CCO');     // Add a recipient

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            //Content
                $url="http://172.21.146.197/accounts/appearance/resetPW.php?token=$token";
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Password Reset';
                $mail->Body    = " <h1>You requested a password reset
                    click <a href='$url'> this link </a> to do so";
                $mail->send();
                echo "<script type='text/javascript'>alert('Reset password link sent!')</script>";
                echo '<meta http-equiv="Refresh" content="0;url=appearance/index.php ">';
                exit();
            }   
            catch (Exception $e) {
                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            }
*/
        }
    }
    
    public function displayData(){
        include_once'connect_db.php';
        // for fetching all cco accounts
        $fetch = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','fetch_data','*','account','is_admin = 0');
        return json_decode($fetch['message'],TRUE);
    }

    public function verifyToken(){
        include_once'connect_db.php';
	$token = $_SESSION['token'];
	return sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','data_verification',"token=$token",'resetpw',"token=$token");
    }
}
    if(!isset($_SESSION)){
	session_start();
    }
    $AccountMgr = new AccountMgr();

    if(isset($_POST['create'])){
        $AccountMgr->createAcc();
    }
    elseif(isset($_POST['resetPW'])){
        $AccountMgr->resetPW();
    }
    elseif(isset($_POST['changePW'])){
        $AccountMgr->changePW();
    }
    elseif(isset($_POST['forgetPW'])){
        $AccountMgr->forgetPW();
    }
?>
