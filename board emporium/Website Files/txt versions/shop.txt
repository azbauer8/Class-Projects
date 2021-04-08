<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Shop</title>
		<style>
			* {
			box-sizing: border-box;
			}

			body {margin:0;
				font-family: "Lato", sans-serif;
				background-color: #1D1D1D;
			}

			.sidenav {
			width: 13%;
			height: 85%;
			position: fixed;
			z-index: 1;
			top: 7%;
			left: 20px;
			background: #eee;
			overflow-x: hidden;
			overflow-y: scroll;
			padding: 8px;
			}

			.sidenav p {
			padding: 5px 5px 5px 5x;
			text-decoration: none;
			font-size: 16px;
			color: black;
			display: block;
			}

			.main {
			margin-left: 140px; /* Same width as the sidebar + left position in px */
			font-size: 28px; /* Increased text to enable scrolling */
			padding: 0px 10px;
			}

			@media screen and (max-height: 450px) {
			.sidenav {padding-top: 15px;}
			.sidenav p {font-size: 18px;}
			}

			/*  SECTIONS  */
			.section {
				clear: both;
				padding: 5px;
				padding-left: 1px;
				margin: 0px;
				margin-top: 4%;
				margin-left: 15%;
				margin-right: 2%;
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

			.productImage{
				height: 100px;
				width: 250px;
				max-height: 125px;
				max-width: 300px;
			}
			#SortForm {
				float: top; top: 10px;
			}

			#ProductTitle {
				color: inherit;
				text-decoration: none;
			}

		</style>
	</head>
	<?php include("header.php"); ?>
	<body>
		<div class="sidenav">
		<form action="shop.php" method="GET" id="ProductUpdateForm">
				<select name="sort" onchange="ProductUpdateForm.submit()">
					<option value="" disabled selected>Sort By:</option>
					<option value="LowPrice">Price Low to High</option>
					<option value="HighPrice">Price High to Low</option>
				</select>
				<?php
					$colorsQ = "SELECT DISTINCT color FROM BoardProducts";
					$categoriesQ = "SELECT DISTINCT category FROM BoardProducts";
					$sizesQ = "SELECT DISTINCT size FROM BoardProducts";
					$switchesQ = "SELECT DISTINCT switch FROM BoardProducts";
					$backlightQ = "SELECT DISTINCT backlight FROM BoardProducts";
					$colors = mysqli_query($connect, $colorsQ);
					$categories = mysqli_query($connect, $categoriesQ);
					$sizes = mysqli_query($connect, $sizesQ);
					$switches = mysqli_query($connect, $switchesQ);
					$backlights = mysqli_query($connect, $backlightQ);
					echo '<h3>Filter:</h3>';
					echo '<label><b>Categories:</b></label>';
					while($catName = mysqli_fetch_array($categories)) {
						$catChecked = '';
						if ($_GET[filterCategory] == $catName[0]) $catChecked = 'checked="checked"';
						if(!$catName[0] == ""){
							echo '<p><input type=radio name="filterCategory" value="' . $catName[0] . '" ' . $catChecked . '>' . $catName[0] . '</p>';
						}
					}
					echo '<label><b>Sizes:</b></label>';
					while($sizeName = mysqli_fetch_array($sizes)) {
						$sizeChecked = '';
						if ($_GET[filterSize] == $sizeName[0]) $sizeChecked = 'checked="checked"';
						if(!$sizeName[0] == ""){
							echo '<p><input type=radio name="filterSize" value="' . $sizeName[0] . '" ' . $sizeChecked . '>' . $sizeName[0] . '</p>';
						}
					}
					echo '<label><b>Switches:</b></label>';
					while($switchName = mysqli_fetch_array($switches)) {
						$switchChecked = '';
						if ($_GET[filterSwitch] == $switchName[0]) $switchChecked = 'checked="checked"';
						if(!$switchName[0] == ""){
							echo '<p><input type=radio name="filterSwitch" value="' . $switchName[0] . '" ' . $switchChecked . '>' . $switchName[0] . '</p>';
						}
					}
					echo '<label><b>Colors:</b></label>';
					while($colorName = mysqli_fetch_array($colors)) {
						$colorChecked = '';
						if ($_GET[filterColor] == $colorName[0]) $colorChecked = 'checked="checked"';
						if(!$colorName[0] == ""){
							echo '<p><input type=radio name="filterColor" value="' . $colorName[0] . '" ' . $colorChecked . '>' . $colorName[0] . '</p>';
						}
					}
					echo '<label><b>Backlight:</b></label>';
					while($lightName = mysqli_fetch_array($backlights)) {
						$lightChecked = '';
						if ($_GET[filterBacklight] == $lightName[0]) $lightChecked = 'checked="checked"';
						if(!$lightName[0] == ""){
							echo '<p><input type=radio name="filterBacklight" value="' . $lightName[0] . '" ' . $lightChecked . '>' . $lightName[0] . '</p>';
						}
					}
				?>
				<input type=submit value="Update" style="font-size: 16px;">
			</form>
			<br>
			<form action="clearfilters.php">
				<input type=submit value="Clear" style="font-size: 16px;">
			</form>
		</div>
			<?php
				// checks if filter box was submitted
				$query = "SELECT * FROM BoardProducts";
				$firstFilter = true;
				if (!empty($_GET)) {
					if(isset($_GET[filterColor])){
						$currentColor = $_GET[filterColor];
						if($firstFilter){
							$query .= " WHERE color='".$currentColor."'";

						}
						else{
							$query .= " AND color='".$currentColor."'";
						}
						$firstFilter = false;
					}
					if(isset($_GET[filterCategory])){
						$currentCat = $_GET[filterCategory];
						if($firstFilter){
							$query .= " WHERE category='".$currentCat."'";

						}
						else{
							$query .= " AND category='".$currentCat."'";
						}
						$firstFilter = false;
					}
					if(isset($_GET[filterSize])){
						$currentSize = $_GET[filterSize];
						if($firstFilter){
							$query .= " WHERE size='".$currentSize."'";

						}
						else{
							$query .= " AND size='".$currentSize."'";
						}
						$firstFilter = false;
					}
					if(isset($_GET[filterSwitch])){
						$currentSwitch = $_GET[filterSwitch];
						if($firstFilter){
							$query .= " WHERE switch='".$currentSwitch."'";

						}
						else{
							$query .= " AND switch='".$currentSwitch."'";
						}
						$firstFilter = false;
					}
					if(isset($_GET[filterBacklight])){
						$currentBacklight = $_GET[filterBacklight];
						if($firstFilter){
							$query .= " WHERE backlight='".$currentBacklight."'";

						}
						else{
							$query .= " AND backlight='".$currentBacklight."'";
						}
						$firstFilter = false;
					}
					if(isset($_GET[sort])){
						$sortBy = $_GET[sort];
						if($sortBy == "LowPrice"){
							$query .= " ORDER BY price ASC";
						}
						else if($sortBy == "HighPrice"){
							$query .= " ORDER BY price DESC";
						}
					}
				}
				$productQuery = mysqli_query($connect, $query);
				echo '<div class="section group">';
				while ($row = mysqli_fetch_array($productQuery)){
					if($row["category"] == "Full Board"){
						echo '<div class="col span_1_of_4"> <h2><a id="ProductTitle" href="product.php?productID=' . $row["productID"] . '">' . $row['name'] . '</a></h2> <p> <img class="productImage" src="' . $row['imageURL'] . '"></p> <p>' . $row['size'] . ' - ' . $row['switch'] . '</p> <p>$' . $row['price'] . '</p></div>';
					}
					else if($row["category"] == "Keycap Set"){
						echo '<div class="col span_1_of_4"> <h2><a id="ProductTitle" href="product.php?productID=' . $row["productID"] . '">' . $row['name'] . '</a></h2> <p> <img class="productImage" src="' . $row['imageURL'] . '"></p> <p>' . $row['category'] . ' - Color: ' . $row['color'] . '</p> <p>$' . $row['price'] . '</p></div>';
					}
					else if($row["category"] == "Board Kit"){
						echo '<div class="col span_1_of_4"> <h2><a id="ProductTitle" href="product.php?productID=' . $row["productID"] . '">' . $row['name'] . '</a></h2> <p> <img class="productImage" src="' . $row['imageURL'] . '"></p> <p>' . $row['category'] . ' - ' . $row['switch'] . '</p> <p>$' . $row['price'] . '</p></div>';
					}
				}
				echo "</div>";
			?>

		<?php include("footer.php"); ?>
	</body>
</html>
