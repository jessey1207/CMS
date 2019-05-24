<!-- Displays all data just put into the incident form -->
<!-- Asks user for confirmation -->
<!-- Modal box notification upon confirmation -->
<!-- ======================================================================= -->
<?php
session_start();
	/* get all for inputs and set as variables */
	$_SESSION['form'] = $_POST;
	$assistance = $_SESSION['form']['assistance'];
	$firstname = $_SESSION['form']['firstname'];
	$lastname = $_SESSION['form']['lastname'];
	$mobile = $_SESSION['form']['mobile'];
	$postcode = $_SESSION['form']['postcode'];
	$address = $_SESSION['form']['address'];
	$unitnum = $_SESSION['form']['unitnum'].' ';
	$extra = $_SESSION['form']['extra'];
	/* time of creation is now */
	$createtime = date('Y-m-d H:i:s');
	/* combine unit no. and address for full adress */
	$address = $unitnum.$address;
	/* by default, an incident just created is unresolved */
	$status = "Unresolved";
?>

<html>
<head>
  <link rel="stylesheet" type="text/css" href="/incidents/confirm_style.css">
  <link rel="stylesheet" type="text/css" href="/incidents/request_sent.css">
  	<?php
	echo '<h1>Please verify that the following details
		have been keyed in correctly:<br/></h1>';
	?>
</head>
<body>
  <!-- start table -->
  <!-- display all form inputs in table format -->
  <table>
	<tr>
	  <td width="40%"><?php echo 'Type of assistance:'?></td>
	  <td width="60%"><?php echo '<b>'.$assistance.'</b><br/>';?></td>
	</tr>
	<tr>
	  <td><?php echo 'First name:'?></td>
	  <td><?php echo '<b>'.$firstname.'</b><br/>';?></td>
	</tr>
	<tr>
	  <td><?php echo 'Last name:'?></td>
	  <td><?php echo '<b>'.$lastname.'</b><br/>';?></td>
	</tr>
	<tr>
	  <td><?php echo 'Mobile number:'?></td>
	  <td><?php echo '<b>'.$mobile.'</b><br/><br/>';?></td>
	</tr>
	<tr>
	  <td colspan="2"><u>Location</u>
	  </td>
	</tr>
	<tr>
	  <td><?php echo 'Postal code:'?></td>
	  <td><?php echo '<b>'.$postcode.'</b><br/>';?></td>
	</tr>
	<tr>
	  <td><?php echo 'Address:'?></td>
	  <td><?php echo '<b>'.$address.'</b><br/>';?></td>
	</tr>
	<tr>
	  <td><?php echo 'Unit number:'?></td>
	  <td><?php echo '<b>'.$unitnum.'</b><br/><br/>';?></td>
	</tr>
	<tr>
	  <td colspan="2">
		<?php echo '<u>Details of incident</u><br/>';
		echo '<b>'.$extra.'</b><br/>';?>
	  </td>
	</tr>
	<tr>
		<!-- buttons to amend or confirm details -->
	  <td>
		<input type="button" id="amend" value="Amend" onclick="history.go(-1);"/>
	  </td>
	  <td>
	  	<input type="submit" id="confirm" value="Confirm"/>
	  </td>
	</tr>
  <!-- end table -->
  </table>
</body>

<!-- modal box notification for request being sent -->
<div id="sent" class="modal">
  <div class="modal-content">
  	<!-- determine which agency the request should be sent to -->
    <p>Request and SMS sent to
    	<?php if ($assistance == 'Gas Leak Control') {
				echo 'Singapore Power.<br/>';
			} else {
				echo 'Singapore Civil Defence Force (SCDF).<br/>';
			}
			?>
    Information updated on map!</p>
    <br/>
    <!-- OK button that redirects to table of incident reports -->
    <form action="/incidents/incident_reports.php" method="POST">
    	<input type="submit" id="ok" name="ok" value="OK">
		</form>
  </div>
</div>

<!-- functions to open and close the modal box -->
<script language="javascript" type="text/javascript">
	var modal = document.getElementById('sent');
	var btn = document.getElementById("confirm");
	btn.onclick = function() {
		/* upon confirmation, write form inputs to database */
		<?php
		  include_once 'connect_db.php';
		  $sql='first_name='.$firstname.',last_name='.$lastname.',
				phone_number='.$mobile.',postal_code='.$postcode.',address='.$address.'
				,assistance='.$assistance.',description='.$extra.',
				create_date_time='.$createtime.',status='.$status.',aid=1';
		  sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php',
				'insert_data',Null,'incident_report',$sql);
		?>
		/* notify user that request has been sent */
	  modal.style.display = "block";
	}
</script>
</html>
