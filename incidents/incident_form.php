<!-- Incident form for the user to fill to create an incident -->
<!-- ======================================================================= -->
<?php session_start(); include('header.php'); include('footer.php'); ?>


<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="/incidents/header.css">
  <link rel="stylesheet" type="text/css" href="/incidents/form_style.css">
  <!-- link to APIs -->
 
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js">
  </script>
  <script type="text/javascript" src="http://gothere.sg/jsapi?sensor=false">
  </script>
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
  <!-- start form -->
  <section id="overlay">
    <form name="incident_id" id="incident_id" onsubmit="return formValidation()"
      action="/incidents/confirm.php"  method="POST">
      <h2>Incident Reporting Form</h2>
      <section class="details">
        <!-- ask for the details needed  -->
        <h4><label for "assistance">Type of assistance</label></h4>
        <select id="assistance" name="assistance" required>
          <option disabled selected value>-Select-</option>
          <option value="Emergency Ambulance">Emergency Ambulance</option>
          <option value="Rescue and Evacuation">Rescue and Evacuation</option>
          <option value="Fire-Fighting">Fire-Fighting</option>
          <option value="Gas Leak Control">Gas Leak Control</option>
        </select>
        <br/>
        <h4><label for "firstname">First name</label></h4>
        <!-- only allow letter for name inputs -->
        <input type="text" id="firstname" name="firstname" required
          onkeypress= "return ((event.charCode >= 65 && event.charCode <= 90) ||
            (event.charCode >= 97 && event.charCode <= 122) ||
            (event.charCode == 32)|| 
            event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 9
            || event.keyCode == 37 || event.keyCode == 39)">
        <br/>
        <h4><label for "lastname">Last name</label></h4>
        <input type="text" id="lastname" name="lastname" required
          onkeypress= "return ((event.charCode >= 65 && event.charCode <= 90) ||
            (event.charCode >= 97 && event.charCode <= 122) ||
            (event.charCode == 32)event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 9
            || event.keyCode == 37 || event.keyCode == 39)">
        <br/>
        <!-- only allow digit inputs, '+' symbol, and min of 8 for mobile -->
        <h4><label for "mobile">Mobile</label></h4>
        <input type="text" id="mobile" name="mobile" minlength="8"  required
          onkeypress= "return !(event.charCode > 31 && (event.charCode < 48 ||
          event.charCode > 57)) || event.charCode == 43">
        <br/>
      </section>
      <section class="location">
        <h3>Location</h3>
        <!-- 6 numbers only for postal code -->
        <h4><label for "postcode">Postal code</label></h4>
        <input type="text" id="postcode" name="postcode"
          minlength="6" maxlength="6" required
          onkeypress= "return !(event.charCode > 31 && (event.charCode < 48 ||
          event.charCode > 57))">
        <!-- Find button for postal code search -->
        <input type="button" id= "find"value="Find" onclick="findAddress()">
        <br/>
        <h4><label for "address">Address</label></h4>
        <textarea id="address" name="address" rows="2" cols="80" readonly>
        </textarea>

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

        <h4><label for "unitnum">Unit number</label></h4>
        <input type="text" id="unitnum" name="unitnum">
        <br/>
      </section>
      <section class="extra">
        <label for "extra"><h3>Details of incident</h3></label>
        <br/>
        <textarea id="extra" name="extra" rows="18" cols="60"></textarea>
      </section>
      <!-- buttons to submit form or to go back -->
      <input type="submit" id="submit" name="submit">
      <input type="button" id="back" value="Back" onclick="history.go(-1);"/>
      <!-- end form -->
    </form>

    <!-- check if address is valid -->
    <script language="javascript" type="text/javascript">
      function formValidation() {
        var address = document.forms["incident_id"]["address"];
        if (address.value == "No Record Found") {
          window.alert('Please enter a valid postal code');
          return false;
        }
      }
    </script>
  </section>
</body>
</html>
