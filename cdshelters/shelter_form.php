<!-- Shelter form for user to create a shelter or edit an existing shelter -->
<!-- ======================================================================= -->
<?php session_start(); include('header.php'); include('footer.php'); ?>

<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="/cdshelters/header.css">
  <link rel="stylesheet" type="text/css" href="/cdshelters/form_style.css">
  <!-- link to APIs -->
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js">
  </script>
  <script type="text/javascript" src="http://gothere.sg/jsapi?sensor=false">
  </script>
</head>

<!-- start page header -->
<!--<div class="header">
  <div id="header-left">
    <img src="/cdshelters/CMSlogo.png" alt="CMS" id="CMSlogo" align="left">
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
  <!-- check whether user is creating or editing a shelter -->
  <?php
    if (isset($_POST['edit'])) {
      $shelter_code = $_POST['shelter_code'];
      $address = $_POST['address'];
      $details = $_POST['details'];
      $dateEstablished = $_POST['$dateEstablished'];
      $button = 'Update';
      $prevPostcode = $_POST['shelter_code'];
    } else {
      $shelter_code = '';
      $address = '';
      $details ='';
      $dateEstablished = date('Y-m-d');
      $button = 'Submit';
      $prevPostcode = '000000';
    }
  ?>
  <!-- start form -->
  <section id="overlay">
    <form action="/cdshelters/Shelter_Mgr.php" name="shelter_id"
       onsubmit="return formValidation()" method="POST">
      <h2>Civil Defence Shelter</h2>
      <section class="details">
        <!-- ask for the details needed  -->
        <input type="hidden" name="prevPostcode" value="<?= $prevPostcode?>">
        <h4><label for "postcode">Postal Code</label></h4>
        <input type="text" id="postcode" name="postcode"
          minlength="6" maxlength="6" value="<?php echo $shelter_code ?>" required
          onkeypress= "return !(event.charCode > 31 && (event.charCode < 48 ||
          event.charCode > 57))">
        <input type="button" id ="find" value="Find" onclick="findAddress()">
        <br/>
        <h4><label for "address">Address</label></h4>
        <?php
          echo "<textarea id='address' name='address'
            cols='80' rows='2' readonly>".$address."</textarea>";
        ?>

          <!-- Search for address from postal code -->
          <!-- API source code taken from
            https://docs.onemap.sg/#authentication-service-post -->
          <script language="javascript" type="text/javascript">
          function findAddress() {
            var address = $("#postcode").val();
            $.ajax({
              url:            'http://gothere.sg/maps/geo',
              dataType:       'jsonp',
              data:           {
                 'output'        : 'json',
                 'q'             : address,
                 'client'        : '',
                 'sensor'        : false
              },
              type:   'GET',
              success: function(data) {
                var field = $("#address");
                var myString = "";
                var status = data.Status;
                if (status.code == 200) {
                  for (var i = 0; i < data.Placemark.length; i++) {
                     var placemark = data.Placemark[i];
                    var status = data.Status[i];
                    myString += placemark.AddressDetails.Country.
                      Thoroughfare.ThoroughfareName + "\n";
                  }
                   field.val(myString);
                } else if (status.code == 603) {
                  field.val("No Record Found");
                }
              },
              statusCode: {
                 404: function() {
                   alert('Page not found');
                }
              },
            });
            return false;
          };
          </script>

        <br/>
        <h4><label for "details">Description</label></h4>
        <?php
          echo "<textarea id='extra' name='extra'
            cols='54' rows='12'>".$details."</textarea>";
        ?>
        <h4><label for "$dateEstablished">Date of Establishment</label></h4>
        <?php
          echo '<input type="date" id="dateEstablished" name="dateEstablished"
            value="'.$dateEstablished.'" required >';
        ?>
        <br/>
        <!-- buttons to submit or go back -->
        <input type="submit" id="submit" value="<?= $button ?>">
        <input type="button" id="back" value="Back" onclick="history.go(-1);"/>
      </section>
      <!-- end form -->
    </form>

    <!-- check if address is valid -->
    <script language="javascript" type="text/javascript">
      function formValidation() {
        var address = document.forms["shelter_id"]["address"];
        if (address.value == "No Record Found") {
          window.alert('Please enter a valid postal code');
          return false;
        }
      }
    </script>
  </section>
</body>
</html>
