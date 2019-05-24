<!-- Shows a table of all incident reports -->
<!-- User can create a new incident report or edit other incident reports -->
<!-- ======================================================================= -->
<?php session_start(); include('header.php'); include('footer.php');?>

<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  
  <link rel="stylesheet" type="text/css" href="reports_style.css">
</head>
<!-- start page header -->
<!--<div class="header">
  <div id="header-left">
    <img src="/incidents/CMSlogo.png" alt="CMS" id="CMSlogo" align="left">
    <h1>Crisis Management System</h1>
  </div>
  <div id="header-right">
    <img src="user.png" alt="CMS" id="user" align="left">
    <?php
      session_start();
      $username = $_SESSION['username'];
      echo '<h1>'.$username.'</h1>';
    ?>
  </div>
  <br style="clear:both" />-->
    <!-- end page header -->
</div>
<body>
  <!-- start headline -->
 <!-- <div id="headline1">
  <h2>Incident Reports</h2>
  </div>-->
  <!-- button to create new incident fom -->
  
      


      <div id ="top">
      <h2 id="heading">Incident Reports </h2>
      <form action="/incidents/incident_form.php" method="POST">
        <button type="submit" class="btn btn-default btn-lg" id ="incident" >Create Incident</button> 
      
      </form>
    </div>
    

    <!-- end headline -->
   
  </br>

  <!-- table filtering -->
  <?php
    include_once'Incident_Mgr.php';
    $IncidentMgr = new IncidentMgr();
    $reports = $IncidentMgr->getAllIncid();
    /* check what data needs to be displayed in the report table */
    if (isset($_POST['display'])) {
      $dispselection = $_POST['display'];
      if ($_POST['display']=='all') {
        $reports = $IncidentMgr->getAllIncid();
      } else if ($_POST['display']=='resolved') {
        $reports = $IncidentMgr->getResolvedIncid();
      } else if ($_POST['display']=='unresolved') {
          $reports = $IncidentMgr->getUnresolvedIncid();
      }
    } else {
      /* no filter chosen */
      $dispselection = '';
    }
  ?>
  <!-- checkboxes for table filtering -->
 
  <form id=select action="" method="POST">
    <div class="radio">
      <input type="radio" name="display" value="all"
        onclick="this.form.submit()" <?php if($dispselection == "all")
          { echo 'checked="checked"';} ?> />
      <label for="all">Show all</label>
    </div>
    <div class="radio">
      <input type="radio" name="display" value="resolved"
        onclick="this.form.submit()" <?php if($dispselection == "resolved")
          { echo 'checked="checked"';} ?> />
      <label for="resolved">Resolved</label>
    </div>
    <div class="radio">
      <input type="radio" name="display" value="unresolved"
        onclick="this.form.submit()" <?php if($dispselection == "unresolved")
          { echo 'checked="checked"';} ?> />
      <label for="unresolved">Unresolved</label>
    </div>
  </form>

  <?php
   /* function call to display report table */
    display($reports);

    /* function to control the format of the table data */
    function display($data) {
      /* start table header */
      echo
      '<table class="table table-bordered" id = "incidentTable">
     
        <tr>
          <thead>
            <th scope="col" >FormID</th>
            <th scope="col" >Date and Time</th>
            <th scope="col" >Type of Assistance</th>
            <th scope="col" >Postal Code</th>
            <th scope="col" >Address</th>
            <th scope="col">Edit</th>
          </thead>
        </tr>
        ';
        /* end table header */
      /* insert incident data into table rows from database */
      $reports = json_decode($data['message'],TRUE);
      $count=count($reports);
      for ($i = $count-1; $i >= 0; $i--) {
        $formID = $reports[$i]["rid"];
        echo
        '<tr>';
          echo '<td>'.$reports[$i]["rid"].'</td>';
          echo '<td>'.$reports[$i]["create_date_time"].'</td>';
          echo '<td>'.$reports[$i]["assistance"].'</td>';
          echo '<td>'.$reports[$i]["postal_code"].'</td>';
          echo '<td>'.$reports[$i]["address"].'</td>';
          echo '<td>
          <form id="toEdit" action="/incidents/edit_incident.php" method="POST">
            <input type="hidden" name="edit" value="'.$formID.'">
            <input type="image" id="edit" src="edit.png" alt="edit" align="center">
          </form></td>
          </tr>';
        }
        return;
      }
    ?>
</body>
</html>
