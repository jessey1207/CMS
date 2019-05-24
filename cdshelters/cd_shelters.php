<!-- Shows a table of all Civil Defence Shelters -->
<!-- User can create a new shelter or edit other shelters -->
<!-- ======================================================================= -->
<?php session_start(); include('header.php'); include('footer.php'); ?>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="/cdshelters/shelters_style.css">
</head>


<body>
  <div id ="top">

  <h2 id="heading">Set Civil Defence Shelter</h2>
  <form action="/cdshelters/shelter_form.php" method="POST">
    <button type="submit" class="btn btn-default btn-lg" id ="form" >Add New Shelter</button> 
  </form>
</div>
<br>

   
  <!-- start headline -->
  <!---<h2>Set Civil Defence Shelter Location</h2>
  <table>
    <tr>
      <td id="new">Add New Shelter</td>-->
      <!-- button to create new shelter -->
     <!-- <td id="image_data">
        <form action="/cdshelters/shelter_form.php" method="POST">
        <input type="image" id="add" src="add.png" alt="add" align="center" />
        </form>
      </td>
    </tr>-->
  <!-- empty table preview -->
  <div id="new_shelter">
    <?php displayHeader();?>
    <tr class="blank_row">
      <!-- blank row with cell gaps -->
      <td ></td><td ></td><td ></td><td ></td><td ></td>
    </tr>
  </div>
  <!-- end headline -->
  </br>

  <?php
    include_once'connect_db.php';
    /* function call to display table header */
    displayHeader();
    /* function call to display table data */
    displayData();

    /* function to control format of the table header */
    function displayHeader() {
      echo
      '<table class = "table table-bordered" id="table" >
        <tr>
          <thead>
            <th scope="col" width="15%"> Postal Code</th>
            <th scope="col" width="25%"> Address</th>
            <th scope="col" width="30%"> Details</th>
            <th scope="col" width="20%"> Date of Establishment</th>
            <th scope="col" width="10%"> Edit</th>
          </thead>
        </tr>';

    }

    /* function to control format of the table data */
    function displayData() {
      /* insert shelter data into table rows from database */
      $data = sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php',
        'fetch_data',
        '*','CD_shelter',Null);
      $shelters = json_decode($data['message'],TRUE);
      $count=count($shelters);
      for ($i = $count-1; $i >= 0; $i--) {
        $shelter_code = $shelters[$i]["postal_code"];
        $address = $shelters[$i]["address"];
        $details = $shelters[$i]["description"];
        $dateEstablished = $shelters[$i]["establish_date"];
        echo
        '<tr>';
          echo '<td>'.$shelter_code.'</td>';
          echo '<td>'.$address.'</td>';
          echo '<td>'.$details.'</td>';
          echo '<td>'.$dateEstablished.'</td>';
          echo '<td>
          <form id="toEdit" action="/cdshelters/shelter_form.php" method="POST">
            <input type="hidden" name="shelter_code" value="'.$shelter_code.'">
            <input type="hidden" name="address" value="'.$address.'">
            <input type="hidden" name="details" value="'.$details.'">
            <input type="hidden" name="$dateEstablished"
              value="'.$dateEstablished.'">
            <input type="hidden" name="edit" value="">
            <input type="image" id="edit" src="edit.png" alt="edit" align="center">
          </form></td>
        </tr>';
      }
      return;
    }
  ?>
  <h3>List of Shelters</h3>
</body>
</html>
