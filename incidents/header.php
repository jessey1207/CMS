<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link rel ="stylesheet" type ="text/css" href="/accounts/appearance/header.css">
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <a class = "navbar-brand" href ="#">
      <img src ="avatar.png" id ="avatar"></a>
       <p class="navbar-text">Crisis Management System</p> 
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-user" id="user"> </i> <?php echo $_SESSION['username']?><span class="caret"></span></a>
        <ul class="dropdown-menu">
        
                    <li> <a href="/accounts/appearance/changePW.php" >Change Password</a></li>
                     <li><a href = "/accounts/appearance/logout.php"> Log out</a></li>
     
        </ul>
    </ul>
  </div>
</nav>
</body>
</html>
