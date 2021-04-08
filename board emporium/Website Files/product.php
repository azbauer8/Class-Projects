<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Product Page</title>
    <style>

      body {
        font-family: "Lato", sans-serif; 
        margin: 0;
        background-color: #1D1D1D;
      }

      /*  SECTIONS  */
			.section {
				clear: both;
				padding: 5px;
				padding-left: 1px;
				margin-top: 10%;
				margin-left: 15%;
				margin-right: 15%;
        		margin-bottom: 5%;
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

			.productImage{
				height: 400px;
				width: 1000px;
				max-height: 250px;
				max-width: 500px;
			}

			#ProductTitle {
				color: inherit;
				text-decoration: none;
			}
      
      #description {
        margin-top: 15%;
        margin-left: 5%;
        margin-right: 5%;
        line-height: 1.6;
      }

    </style>
  </head>
  <?php include("header.php"); ?>
  <body>
    <?php
      $productID = $_GET["productID"];
      $query = "SELECT * FROM BoardProducts WHERE productID = " . $productID;
      $productQuery = mysqli_query($connect, $query);
      echo '<div class="section group">';
      while ($row = mysqli_fetch_array($productQuery)){
          echo '<div class="col span_2_of_4"> <h2><a id="ProductTitle">' . $row['name'] . '</h2> <p> <img class="productImage" src="' . $row['imageURL'] . '"></p> <p>' . $row['size'] . ' - ' . $row['switch'] . '</p></div>';
		  echo '<div class="col span_2_of_4"><p id="description">$' . $row['price']. '<br>' . $row['description'] . '</p>';
		  if(isset($_POST['AddToCart'])) {
			// unsets addtocart variable so it never accidentally adds to cart twice
			unset($_POST['AddToCart']);
			// checks that user is signed in first
			if(isset($_SESSION['uname'])){
				// if signed in, query for userID with uname
				$userIDQuery = "SELECT userID FROM BoardUsers WHERE username='" . $_SESSION['uname'] . "'";
				$userIDResult = mysqli_query($connect, $userIDQuery);
				while ($row = mysqli_fetch_array($userIDResult)){
					if(!empty($row['userID'])){
						$userID = $row['userID'];
					}
				}
				// adds product to cart with userID and redirects to cart page
				$addToCartQuery = "INSERT INTO BoardCart(userID, productID) VALUES('$userID', '$productID')";
				mysqli_query($connect, $addToCartQuery);
				echo '<script>window.location.href = "cart.php";</script>';
			}
			else{
				echo '<form method="post" action=""><input type="submit" name="AddToCart" value="Add To Cart" style="font-size: 16px;"></form><p style="font-size: 16px; color: #CD594A;">You must be logged in to add items to cart.</p></div>';
			}
		  }
		  else{
			echo '<form method="post" action=""><input type="submit" name="AddToCart" value="Add To Cart" style="font-size: 16px;"></form></div>';
		  }
	  }
      echo "</div>";
    ?>
  </body>
  <?php include("footer.php"); ?>
</html>
