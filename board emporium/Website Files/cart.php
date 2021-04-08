<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Cart</title>
		<style>
			* {
			box-sizing: border-box;
			}

			body {margin:0;
				font-family: "Lato", sans-serif;
				background-color: #1D1D1D;
			}

			.sidenav {
			width: 22%;
			position: fixed;
			z-index: 1;
			top: 80px;
			right: 20px;
			background: #eee;
			overflow-x: hidden;
			padding: 8px;
			}

			.sidenav p {
			padding: 5px 5px 5px 5x;
			text-decoration: none;
			font-size: 16px;
			color: black;
			display: block;
			}

			@media screen and (max-height: 450px) {
			.sidenav {padding-top: 15px;}
			.sidenav p {font-size: 18px;}
			}

			/*  SECTIONS  */
			.section {
				clear: both;
				padding: 3px;
				padding-left: 1px;
				margin: 0px;
				margin-top: 4%;
				margin-left: 2%;
				margin-right: 25%;
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
				padding: 20px;
			}
			/* .col:first-child { margin-left: 0; } */

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
				width: 50%;
			}
			.span_1_of_4 {
				width: 23.8%;
			}

			/*  GO FULL WIDTH BELOW 480 PIXELS */
			@media only screen and (max-width: 480px) {
				.col {  margin: 1% 0 1% 0%; }
				.span_1_of_4, .span_2_of_4, .span_3_of_4, .span_4_of_4 { width: 100%; }
			}

			.productImage{
				height: 100px;
				width: 250px;
				max-height: 125px;
				max-width: 300px;
			}

			#ProductTitle {
				color: inherit;
				text-decoration: none;
			}

		</style>
	</head>
	<?php include("header.php"); ?>
	<body>
			<?php
				// checks if user is signed in
				if(isset($_SESSION['uname'])){
					// gets userID from uname
					$userIDQuery = "SELECT userID FROM BoardUsers WHERE username='" . $_SESSION['uname'] . "'";
					$userIDResult = mysqli_query($connect, $userIDQuery);
					while ($row = mysqli_fetch_array($userIDResult)){
						if(!empty($row['userID'])){
							$userID = $row['userID'];
						}
					}
					// checks if user clicked remove item from cart
					if(isset($_GET['orderID'])){
						// removes product from cart and unsets get variable
						$removeItemQuery = mysqli_query($connect, "DELETE FROM BoardCart WHERE orderID='" . $_GET['orderID'] . "'");
						unset($_GET['orderID']);
					}
					// finds products in cart with userID
					$cartQuery = "SELECT * FROM BoardCart WHERE userID='" . $userID . "'";
					$cartProducts = mysqli_query($connect, $cartQuery);
					echo '<div class="section group">';
					$numRows = 0;
					while ($row = mysqli_fetch_array($cartProducts)){
						// queries products table using productID to get data for each product
						$productCartQuery = "SELECT * FROM BoardProducts WHERE productID='" . $row['productID'] . "'";
						$productsInCart = mysqli_query($connect, $productCartQuery);
						while ($rowV2 = mysqli_fetch_array($productsInCart)){
						echo '<div class="col span_2_of_4"> <h2><a id="ProductTitle" href="product.php?productID=' . $rowV2["productID"] . '">' . $rowV2['name'] . '</a></h2> <p> <img class="productImage" src="' . $rowV2['imageURL'] . '"></p> <p>' . $rowV2['size'] . ' - ' . $rowV2['switch'] . '</p><p>$' . $rowV2['price'] . '</p>';
						echo '<input type="submit" value="Remove Item"  onclick="window.location.href=\'cart.php?orderID=' . $row['orderID'] . '\'"></div>';
						}
						$numRows++;
					}
					if($numRows == 0){
						echo '<h2 style="color: #ececec; margin-left: 10px; letter-spacing: 0.7px;">Your cart is currently empty</h2>';
					}
				echo "</div>";
				
				echo "<div class='sidenav'>";
				echo '<h2>Cart:</h2>';
				$cartQuery = "SELECT * FROM BoardCart WHERE userID='" . $userID . "'";
				$cartProducts = mysqli_query($connect, $cartQuery);
				$totalCost = 0;
				while ($row = mysqli_fetch_array($cartProducts)){
					$productCartQuery = "SELECT * FROM BoardProducts WHERE productID='" . $row['productID'] . "'";
					$productsInCart = mysqli_query($connect, $productCartQuery);
					while ($rowV2 = mysqli_fetch_array($productsInCart)){
						echo '<p>' . $rowV2['name'] . ': $' . $rowV2['price'] . '</p>';
						$totalCost += $rowV2['price'];
					}
				}
				echo '<h3>Total Cost: $' . $totalCost . '</h3>';
				echo "</div>";
				}
				else{
					echo '<br><br><br><h2 style="color: #CD594A; margin-left: 20px;">You must be signed in to access the cart.</h2>';
				}
			?>
		<?php include("footer.php"); ?>
	</body>
</html>
