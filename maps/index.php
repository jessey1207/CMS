<html>
	<head>
		  <meta charset="utf-8">
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
		   integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
		   crossorigin="">
		  </script>
		  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

		  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">	 
		  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
		   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
		   crossorigin=""/>
		   <link rel="stylesheet" type="text/css" href="css/home_style.css">
	</head>

	<body>
		<nav class="navbar navbar-inverse">
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <a class="brand" href="index.php" id="logo">
		      	<img src="img/CMSlogo.png" id="logo" title="Home">
		      </a>
		    </div>
		    <ul class="nav navbar-nav">
		      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Emergency Management <span class="caret"></span></a>
		        <ul class="dropdown-menu">
		          <li id="attack"><a href="#">Terror Attack</a></li>
		          <li id="fire"><a href="#">Fire</a></li>
		          <li id="flood"><a href="#">Flooding</a></li>
		          <li id="gas"><a href="#">Gas Leak</a></li>
		          <li id="lift"><a href="#">Lift Breakdown</a></li>
		        </ul>
		      </li>
		      <!--<li><a href="#">Page 2</a></li>-->
		    </ul>
		    <ul id="contact" class="nav navbar-nav navbar-right">
		      <li><a href="#"><span class="glyphicon glyphicon-earphone"></span> Report Incident: 6543 2198</a></li>
		    </ul>
		  </div>
		</nav>

		<!-- The Modal -->
		<div id="myModal" class="modal">
		  <span class="close">&times;</span>
		  <img class="modal-content" id="img01">
		  <div id="caption"></div>
		</div>
		<div class="container" id="container">
			<form action="" class="form-input" onclick="closeForm()" onclick="closeForm1()">
				<label class="radio-inline">
			      <input type="radio" name="choice" id="haze" onclick="checkInput()"> Haze
			    </label>
			    <label class="radio-inline">
			      <input type="radio" name="choice" id="weather" onclick="checkInput()"> Weather
			    </label>
			    <label class="radio-inline">
			      <input type="radio" name="choice" id="dengue" onclick="checkInput()"> Dengue
			    </label>

			    <label class="radio-inline">
			      <input type="radio" name="choice" id="incident" onclick="checkInput()"> Incident Report
			    </label>
			    <label class="radio-inline">
			      <input type="radio" name="choice" id="cds" onclick="checkInput()"> Civil Defence Shelter
			    </label>
			</form>
			<div class="form-popup" id="myForm">
				  <form action="" method="POST" class="form-container">
				    <h1>CMS Subscription</h1>

				    <label for="phone"><b>Phone Number</b></label>
				    <input type="text" placeholder="Enter Phone Number" onkeypress="return checkNum(event)" id="phone" name="phone" required>
				    <p id="error" style="color: red;"></p>
				    <button type="submit" id="submitBtn" class="btn" onclick="return checkPhone()">Submit</button>
				    <button type="submit" class="btn cancel" onclick="closeForm()">Close</button>
				  </form>
			</div>
			<div class="form-popup1" method="POST" id="myForm1">
				  <form action="" method="POST" class="form-container1">
				    <h1>CMS Subscription</h1>

				    <label for="email"><b>Email</b></label>
				    <input type="text" placeholder="Enter Email" name="email" id="email" required>
				    <p id="error1" style="color: red;"></p>
				    <button type="submit" class="btn" onclick=" return checkEmail()">Submit</button>
				    <button type="submit" class="btn cancel" onclick="closeForm1()">Close</button>
				  </form>
			</div>
			
			<!-- The Modal for Successful subscription for Phone -->
			<div id="phoneModal" class="modal1">

			  <!-- Modal content -->
			  <div class="modal-content1">
			    <span class="close1">&times;</span>
			    <p id="modalText">Successful subscription!</p>
			  </div>

			</div>
			<div id="result"></div>
			<!-- Map Display -->
			<div id="mapid" onclick="closeForm()" onclick="closeForm1()"></div>
			<!-- begin latest posts -->
		    <div class="box" onclick="closeForm()" onclick="closeForm1()">
		      <div class="buffer">
		        <h2>Latest Updates</h2>
		        <ul id="ul">
		        <li>No Incidents</li>
		        </ul>
		      </div>
		    </div>
		    <!-- end latest posts -->
		    <!--div id="result"></div-->
			</div>
			<div class="footer" id="footer">
			  <!--p>Subscribe Us</p-->
			  <div class="footer-copyright text-center py-12">
			    <a id="facebook" href="#" onclick="closeForm()" onclick="closeForm1()">
	              <img src="img/facebook.png" width="30" height="30">
	            </a>
	            <!-- Instagram -->
	            <a id="ig" href="#" onclick="closeForm()" onclick="closeForm1()">
	              <img src="img/instagram.png" width="30" height="30">
	            </a>
	            <!-- SMS -->
	            <a id="sms" href="#" onclick="closeForm1()">
	              <img src="img/whatsapp.png" width="30" height="30" onclick="openForm()">
	            </a>
	            <!--Email -->
	            <a id="email" href="#" onclick="closeForm()">
	              <img src="img/email.png" width="30" height="30" onclick="openForm1()">
	            </a>
			  </div>
			</div>

		
		<script>
			var data;
			
			var mymap = L.map('mapid').setView([1.3521, 103.8198], 11.4);
			L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
				    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			    maxZoom: 18,
			    id: 'mapbox.streets',
			    accessToken: 'pk.eyJ1Ijoib25nY2oxMyIsImEiOiJjanN3bmhsODIwNnQwM3lwZjhwdDEzNWxpIn0.BhI-adPBfs0ldHbKedHqeQ'
			}).addTo(mymap);

			var markers = L.layerGroup().addTo(mymap);

			if(typeof(EventSource) !== "undefined") 
			{
				var source = new EventSource("Map_Mgr.php");

				source.addEventListener('message', function(e) {

					//document.getElementById("result").innerHTML += "New Data <br>";
				  	data = JSON.parse(event.data);
				  	checkInput();
			    	//document.getElementById("result").innerHTML += data[4].length;

				}, false);

				source.addEventListener('open', function(e) {
				  document.getElementById("result").innerHTML += "connection was open <br>";
				}, false);

				source.addEventListener('error', function(e) {
				  if (e.readyState == EventSource.CLOSED) {
				  document.getElementById("result").innerHTML += "connection was close <br>";
				  }
				}, false);

				/*
			  	source.onmessage = function(event) 
			  	{

			    	data = JSON.parse(event.data);
			    	document.getElementById("result").innerHTML += data[4].length;
			  	};
			  	*/

			} 
			else 
			{
		  		document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";
			}

			/*if(typeof(EventSource) !== "undefined") 
				{
					var source = new EventSource("Map_Mgr.php");
				  	source.onmessage = function(event) 
				  	{
				  		alert("HELLO");
				  		document.getElementById("result").innerHTML += "done"
				    	data = JSON.parse(event.data);
				    	alert(data);
				    	checkInput();
						var ul = document.getElementById("ul");
						// 3 is to pull the incident part of the data
						if(data[3].length == 0){
							var li = document.createElement("li");
							li.appendChild(document.createTextNode("No incidents"));
							ul.appendChild(li);
						}
						else if(data[3].length < 5){
							ul.innerHTML = "";
							for(var i = 0; i<(data[3].length); i++)
							{
								var li = document.createElement("li");
								li.appendChild(document.createTextNode(data[3][i][3]));
								ul.appendChild(li);
							}
						} else if(data[3].length >= 5){
							ul.innerHTML = "";
							for(var i = 0; i<5; i++)
							{
								var li = document.createElement("li");
								li.appendChild(document.createTextNode(data[3][i][3]));
								ul.appendChild(li);
							}
						}
				    	document.getElementById("result").innerHTML += "done";
				  	};

				} 
				else 
				{
			  		document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";
				}*/

		function processData(){

			for(var i = 0; i<data.length; i++)
				data[i] = json_decode(data[i],true);

			checkInput();
			var ul = document.getElementById("ul");
			// 3 is to pull the incident part of the data
			if(data[3].length == 0){
				var li = document.createElement("li");
				li.appendChild(document.createTextNode("No incidents"));
				ul.appendChild(li);
			}
			else if(data[3].length < 5){
				ul.innerHTML = "";
				for(var i = 0; i<(data[3].length); i++)
				{
					var li = document.createElement("li");
					li.appendChild(document.createTextNode(data[3][i][3]));
					ul.appendChild(li);
				}
			} else if(data[3].length >= 5){
				ul.innerHTML = "";
				for(var i = 0; i<5; i++)
				{
					var li = document.createElement("li");
					li.appendChild(document.createTextNode(data[3][i][3]));
					ul.appendChild(li);
				}
			}
		}

		function checkInput(){
		    if(document.getElementById('haze').checked && !document.getElementById('dengue').checked && !document.getElementById('weather').checked &&!document.getElementById('incident').checked && !document.getElementById('cds').checked)
		    {
		    	markers.clearLayers();
				for(var i = 0; i<data[0].length; i++)
				{

					var maker = L.marker([data[0][i][1], data[0][i][2]]).addTo(markers);
					maker.bindPopup(data[0][i][3]);
				}
			} 
			else if(!document.getElementById('haze').checked && document.getElementById('dengue').checked && !document.getElementById('weather').checked &&!document.getElementById('incident').checked && !document.getElementById('cds').checked)
			{
				markers.clearLayers();
				
				// temp holds an array for Leaflet to build a polygon with multiple lat & long. Each polygon has its long & lat repeated for the first and last so the last is removed as not needed by Leaftlet. Once a new polygon, ie = 2, long & lat coord is found it is added to the map and temp is reset to null.

				var temp =  Array();
				for(var i = 0; i<(data[2].length-1); i++)
				{

					if(data[2][i][4]!=data[2][i+1][4])
					{
						var polygon = L.polygon(temp, {color: 'red'}).addTo(markers).bindPopup(data[2][i][3]);
						temp.length = [];
					}
					else
					{
						temp.push([ data[2][i][2], data[2][i][1] ]);
					}
					
				}
			} 


			else if(!document.getElementById('haze').checked && !document.getElementById('dengue').checked && !document.getElementById('weather').checked && document.getElementById('incident').checked && !document.getElementById('cds').checked)
			{
				markers.clearLayers();
				// temp holds an array for Leaflet to build a polygon with multiple lat & long. Each polygon has its long & lat repeated for the first and last so the last is removed as not needed by Leaftlet. Once a new polygon, ie = 2, long & lat coord is found it is added to the map and temp is reset to null.

				for(var i = 0; i<(data[3].length); i++)
				{
					var marker = L.marker([	data[3][i][1], data[3][i][2]	]).addTo(markers).bindPopup(data[3][i][3]);
				}
			} 

			else if(!document.getElementById('haze').checked && !document.getElementById('dengue').checked && !document.getElementById('weather').checked && !document.getElementById('incident').checked && document.getElementById('cds').checked)
			{
				markers.clearLayers();
				// temp holds an array for Leaflet to build a polygon with multiple lat & long. Each polygon has its long & lat repeated for the first and last so the last is removed as not needed by Leaftlet. Once a new polygon, ie = 2, long & lat coord is found it is added to the map and temp is reset to null.

				for(var i = 0; i<(data[4].length); i++)
				{
					var marker = L.marker([	data[4][i][1], data[4][i][2]	]).addTo(markers).bindPopup(data[4][i][3]);
				}
			} 



			else 
			{
				markers.clearLayers();
				for(var i = 0; i<data[1].length; i++)
				{
					var weatherString = data[1][i][3];
					weatherString = weatherString.replace(/ /g, "");
					weatherString = weatherString.toLowerCase();
					var firstIndex = weatherString.indexOf(">") + 1;
					weatherString = weatherString.substr(firstIndex);

					var newWeatherString = weatherString.concat('.png');
					var img = 'img/';
					var dir = img.concat(newWeatherString);
					//alert(dir);
					var myIcon = L.icon({
				    iconUrl: dir,
				    iconSize: [40, 40],
				    iconAnchor: [40, 40],
				    popupAnchor: [-20, -30],
				    shadowUrl: null,
				    shadowSize: null,
				    shadowAnchor: null
					});
					L.marker([data[1][i][1], data[1][i][2]], {icon: myIcon}).addTo(markers).bindPopup(data[1][i][3]);
				}
			}
		}
		</script>
		<script type="text/javascript">
			// Get the modal
			var modal = document.getElementById('myModal');

			// Get the image and insert it inside the modal - use its "alt" text as a caption
			var fire = document.getElementById('fire');
			var attack = document.getElementById('attack');
			var flood = document.getElementById('flood');
			var gas = document.getElementById('gas');
			var lift = document.getElementById('lift');
			var modalImg = document.getElementById("img01");
			var captionText = document.getElementById("caption");

			// On click of Fire will produce the Fire Modal
			fire.onclick = function(){
			  modal.style.display = "block";
			  modalImg.src = "img/haze.jpg";
			  captionText.innerHTML = "<h3>In Case of Fire</h3>";
			}

			// On click of Terror Attack, will produce the Terror Attack Modal
			attack.onclick = function(){
				modal.style.display = "block";
				modalImg.src = "img/bomb.png";
				captionText.innerHTML = "<h3>In Case of Terror Attack</h3>";
			}

			// On click of Flooding, will produce the Flood Modal
			flood.onclick = function(){
				modal.style.display = "block";
				modalImg.src = "img/flood.png";
				captionText.innerHTML = "<h3>In Case of Flooding</h3>";
			}

			// On click of Gas Leak, will produce the Gas Leak Modal
			gas.onclick = function(){
				modal.style.display = "block";
				modalImg.src = "img/gas.jpg";
				captionText.innerHTML = "<h3>In Case of Gas Leak</h3>";
			}

			lift.onclick = function(){
				modal.style.display = "block";
				modalImg.src = "img/lift.png";
				captionText.innerHTML = "<h3>In Case of Lift Breakdown</h3>";
			}

			// Get the <span> element that closes the modal
			var span = document.getElementsByClassName("close")[0];

			// When the user clicks on <span> (x), close the modal
			span.onclick = function() { 
			  modal.style.display = "none";
			}
			function openForm() {
			  	document.getElementById("myForm").style.display = "block";				
			}

			function closeForm() {
			  document.getElementById("myForm").style.display = "none";
			}

			function openForm1() {
			  document.getElementById("myForm1").style.display = "block";
			}

			function closeForm1() {
			  document.getElementById("myForm1").style.display = "none";
			}

			function checkNum(evt){
				var charCode = (evt.which) ? evt.which : event.keyCode
			    if (charCode > 31 && (charCode < 48 || charCode > 57))
			        return false;
			    return true;
			}

			function checkPhone(){
				var phone = document.getElementById("phone").value;
				var span = document.getElementsByClassName("close")[0];
				var modal = document.getElementById("phoneModal");
				span.onclick = function(){
					modal.style.display = "none";
				}

				if(phone.toString().length < 8){
					document.getElementById("error").innerHTML = "Requires 8 digits for phone number!";
					return false;
				} else{
					return true;
				}
				
			}

			function checkEmail(){
				var email = document.getElementById("email").value;
				var span = document.getElementsByClassName("close1")[0];
				var modal = document.getElementById("phoneModal");
				span.onclick = function(){
					modal.style.display = "none";
				}

				var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    			if(!re.test(String(email).toLowerCase())){
    				document.getElementById("error1").innerHTML = "Requires a valid email!";
    				return false;
				} else {
					return true;
				}
			}
		</script>
	</body>
</html>

<?php 

require_once'connect_db.php';
	if(isset($_POST['phone'])) {
		$int = $_POST['phone'];
		$phone = (int) filter_var($int, FILTER_SANITIZE_NUMBER_INT);
		$sql = 'phone_number='.$phone;
		sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','insert_data', Null,'subscription_list', $sql);
		echo "<script type='text/javascript'>
				var modal = document.getElementById('phoneModal');
				var span = document.getElementsByClassName('close1')[0];
				modal.style.display = 'block';
				span.onclick = function(){
					modal.style.display = 'none';
				}
				</script>";
	}
	if(isset($_POST['email'])) {
		$email = $_POST['email'];
		$sql = 'email='.$email;
		sender('Dasebase-#6675','172.21.146.197/db/database_mgr.php','insert_data', Null,'email_subscription', $sql);
		echo "<script type='text/javascript'>
				var modal = document.getElementById('phoneModal');
				var span = document.getElementsByClassName('close1')[0];
				modal.style.display = 'block';
				span.onclick = function(){
					modal.style.display = 'none';
				}
				</script>";
		//$array = json_decode($result["message"],true);
		//echo "<script type='text/javascript'>alert('".$array."');</script>";
	}
?>