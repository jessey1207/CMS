<!-- Shows all data of the chosen incident to edit -->
<!-- Allows user to resolve the chosen incident -->
<!-- ======================================================================= -->
	<?php

		include 'Incident_Mgr.php';
		$IncidentMgr = new IncidentMgr();
		$form_ID=$_POST['edit'];
		/* get data of chosen incident from database */
		$data = $IncidentMgr->getIncid($form_ID);
		$report = json_decode($data['message'],TRUE);
		/* set variables from the incident that requires editing */
		$rid = $report[0]["rid"];
		$assistance = $report[0]["assistance"];
		$name = $report[0]["first_name"].' '.$report[0]["last_name"];
		$mobile = $report[0]["phone_number"];
		$postcode = $report[0]["postal_code"];
		$address = $report[0]["address"];
		$extra = $report[0]["description"];
		$status = $report[0]["status"];
	?>

<html>
<head>
  <link rel="stylesheet" type="text/css" href="/incidents/edit_style.css">
	<!-- start header -->
	<!-- header shows form ID and status of incident -->
  <div id="title1">
  <?php
	echo '<h1>Form ID: '.$form_ID.'<br/></h1>';
	?>
  </div>
  <div id="title2">
  	<?php
	echo '<h1>Status: '.$status.'<br/></h1>';
	?>
  </div>
	<!-- end header -->
</head>
<body>
  <!-- start table -->
  <!-- display incident details of input in table format -->
  <table id ="table-con">
	<tr>
	  <td width="40%"><?php echo 'Type of assistance:'?></td>
	  <td width="60%"><?php echo '<b>'.$assistance.'</b><br/>';?></td>
	</tr>
	<tr>
	  <td><?php echo 'Name:'?></td>
	  <td id ="name"><?php echo '<b>'.$name.'</b><br/>';?></td>
	</tr>
	<tr>
	  <td><?php echo 'Mobile number:'?></td>
	  <td id ="mobile"><?php echo '<b>'.$mobile.'</b><br/>';?></td>
	</tr>
	<tr></tr>
	<tr>
	  <td colspan="2"><u>Location</u></td>
	</tr>
	<tr>
	  <td><?php echo 'Postal code:'?></td>
	  <td id ="postal"><?php echo '<b>'.$postcode.'</b><br/>';?></td>
	</tr>
	<tr>
	  <td><?php echo 'Address:'?></td>
	  <td id ="address"><?php echo '<b>'.$address.'</b><br/>';?></td>
	</tr>
	<tr></tr>
	<tr>
	  <td colspan="2" id ="details">
		<u><?php echo 'Details of incident:<br/>'?></u>
		<?php echo '<b>'.$extra.'</b><br/>'?>
	  </td>
	</tr>
	<tr>
	  <td>
		<input type="button" id="back" value="Back" onclick="history.go(-1);"/>
	  </td>
	  <td>
			<!-- button to set the incident in database to 'resolved' -->
	  	<form action="/incidents/Incident_Mgr.php" method="POST">
	  		<input type="hidden" name="formID" value="<?= $form_ID ?>">
	  		<input type="button" id="resolve" value="Resolve"
					onclick="this.form.submit()">
	  	</form>
	  </td>
	</tr>
    <!-- end table -->
  </table>
</body>
</html>
