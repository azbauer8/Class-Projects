<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>About</title>
		<style>
			* {
			box-sizing: border-box;
			}

			body {margin:0;
				font-family: "Lato", sans-serif;
				background-color: #1D1D1D;
			}

			/*  SECTIONS  */
			.section {
				clear: both;
				padding: 5px;
				padding-left: 1px;
				margin: 0px;
				margin-top: 4%;
				margin-left: 15%;
				margin-right: 15%;
				border-width:5px;  
    			border-style:ridge;				
				background-color: #161D2E;
			}

			/*  COLUMN SETUP  */
			.col {
				display: block;
				float:left;
				margin-top: 1%;
				margin-bottom: 1%;
				margin-right: 0px;
				margin-left: 0px;
				color: #ececec;
				text-align: center;
			}

			/*  GROUPING  */
			.group:before,
			.group:after { content:""; display:table; }
			.group:after { clear:both;}
			.group { zoom:1; /* For IE 6/7 */ }
			/*  GRID OF FOUR  */
			.span_4_of_4 {
				width: 100%;
			}
			.span_3_of_4 {
				width: 74.6%;
			}
			.span_2_of_4 {
				width: 49.2%;
			}
			.span_1_of_4 {
				width: 23.8%;
			}

			/*  GO FULL WIDTH BELOW 480 PIXELS */
			@media only screen and (max-width: 480px) {
				.col {  margin: 1% 0 1% 0%; }
				.span_1_of_4, .span_2_of_4, .span_3_of_4, .span_4_of_4 { width: 100%; }
			}
			
			.ignore-css{
				all:unset;
			}

		</style>
	</head>
	<?php include("header.php"); ?>
	<body>
	 	<div class="section group">';
			 <div class="col span_4_of_4"> 
			 	<h1>About</h1>
			 	<h4>This is a mock mechanical keyboard webstore created as a final project for INFSCI 1059 Web Programming.</h4>
				<h3>This project was written with the following languages:</h3>
				<ul class="ignore-css">
					<li class="ignore-css"><p>HTML - obviously</p></li>
					<li class="ignore-css"><p>CSS - basic styling</p></li>
					<li class="ignore-css"><p>PHP - for most scripting and database interaction</p></li>
					<li class="ignore-css"><p>SQL - hosts data for products and users</p></li>
					<li class="ignore-css"><p>Javascript - used for a couple dynamic features, like the sign in/up pop-up windows</p></l4>
				</ul>
				<h3>And contains the following features:</h3>
				<ul class="ignore-css">
					<li class="ignore-css"><p>Easy sign in and sign up functionality that remembers the user until logged out and welcomes them by name</p></li>
					<li class="ignore-css"><p>Dynamic sign in modals that check for required fields before processing against database</p></li>
					<li class="ignore-css"><p>Shop page lists products pulled from database, along with sorting and filtering options</p></li>
					<li class="ignore-css"><p>Each product links to a single product page that automatically fills in content based on the product ID sent to it</p></li>
					<li class="ignore-css"><p>Products can be added to the user's cart (if signed in), where they're displayed on a separate page alongside the total cost</p></li>
					<li class="ignore-css"><p>Cart page is accessible by the navigation bar (if signed in), or by adding an item to the cart</p></li>
				</ul>
			 </div>
		</div>

	<?php include("footer.php"); ?>
	</body>
</html>
