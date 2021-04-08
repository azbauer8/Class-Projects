<head>
<meta charset="UTF-8">
        <style>
            body {
                margin:0;
                margin-bottom:50px;
                font-family: "Lato", sans-serif;
            }

            ul {
            list-style-type: none;
            margin: 0;
            padding: 0px;
            overflow: hidden;
            background-color: #333;
            position: fixed;
            top: 0;
            width: 100%;
            }

            li {
            float: left;
            }

            li a {
            display: block;
            color: white;
            text-align: center;
            padding: 10px 10px;
            text-decoration: none;
            }

            li a:hover:not(.active) {
            background-color: #111;
            }

            .active {
            background-color: #1575AE;
            }

            #sign-in {
            background-color: #1575AE;
            }

            #sign-out {
            background-color: #1575AE;
            }

            #sign-up{
            background-color: #B71C24;
            }

            #cart{
            margin-right: 5px;
            }

            * {box-sizing: border-box;}

            /* The popup form - hidden by default */
            .form-popup {
            display: none;
            position: fixed;
            top: 50px;
            right: 10px;
            border: 3px solid #f1f1f1;
            z-index: 9;
            }

            /* Add styles to the form container */
            .form-container {
            max-width: 300px;
            padding: 10px;
            background-color: white;
            margin-top: 0;
            }

            /* Full-width input fields */
            .form-container input[type=text], .form-container input[type=password] {
            width: 100%;
            padding: 15px;
            margin: 5px 0 22px 0;
            border: none;
            background: #f1f1f1;
            }

            /* When the inputs get focus, do something */
            .form-container input[type=text]:focus, .form-container input[type=password]:focus {
            background-color: #ddd;
            outline: none;
            }

            /* Set a style for the submit/login button */
            .form-container .btn {
            background-color: #4CAF50;
            color: white;
            padding: 16px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-bottom:10px;
            opacity: 0.8;
            }

            /* Add a red background color to the cancel button */
            .form-container .cancel {
            background-color: red;
            }

            /* Add some hover effects to buttons */
            .form-container .btn:hover, .open-button:hover {
            opacity: 1;
            }

            #SuccessPopup {
                -moz-animation: cssAnimation 0s ease-in 5s forwards;
                /* Firefox */
                -webkit-animation: cssAnimation 0s ease-in 5s forwards;
                /* Safari and Chrome */
                -o-animation: cssAnimation 0s ease-in 5s forwards;
                /* Opera */
                animation: cssAnimation 0s ease-in 5s forwards;
                -webkit-animation-fill-mode: forwards;
                animation-fill-mode: forwards;
                position: fixed;
                top: 10px;
                right: 225px;
                color: #1575AE;
                font-family: sans-serif;
                margin: 0;
                font-weight: bold;
            }
            #FailPopup {
                -moz-animation: cssAnimation 0s ease-in 5s forwards;
                /* Firefox */
                -webkit-animation: cssAnimation 0s ease-in 5s forwards;
                /* Safari and Chrome */
                -o-animation: cssAnimation 0s ease-in 5s forwards;
                /* Opera */
                animation: cssAnimation 0s ease-in 5s forwards;
                -webkit-animation-fill-mode: forwards;
                animation-fill-mode: forwards;
                position: fixed;
                top: 10px;
                right: 175px;
                color: #B81B24;
                font-family: sans-serif;
                margin: 0;
                font-weight: bold;
            }
            @keyframes cssAnimation {
                to {
                    width:0;
                    height:0;
                    overflow:hidden;
                }
            }
            @-webkit-keyframes cssAnimation {
                to {
                    width:0;
                    height:0;
                    visibility:hidden;
                }
            }
        </style>
</head>
<body>
        <ul>
        <?php
            // changes active colored tab based on the current file the header is included in
            $filename = basename($_SERVER[PHP_SELF]);
            if($filename == "home.php"){
                echo '<li><a class="active" href="home.php">Home</a></li><li><a href="shop.php">Shop</a></li></li><li><a href="about.php">About</a></li>';
            }
            else if($filename == "shop.php"){
                echo '<li><a href="home.php">Home</a></li><li><a class="active" href="shop.php">Shop</a></li></li><li><a href="about.php">About</a></li>';
            }
            else if($filename == "about.php"){
                echo '<li><a href="home.php">Home</a></li><li><a href="shop.php">Shop</a></li></li><li><a class="active" href="about.php">About</a></li>';
            }
            else{
                echo '<li><a href="home.php">Home</a></li><li><a href="shop.php">Shop</a></li></li><li><a href="about.php">About</a></li>';
            }

            include("database.php");
            // checks if user had previously logged in. Checks session variables
            if(isset($_SESSION['uname'])){
                echo '<li style="float:right"><a href="logout.php" id="sign-out">Sign out</a></li>';
            }

            // checks if user attempted to login
            else if(isset($_POST['uname']) && $_POST['uname'] != "" && isset($_POST['psw']) && $_POST['psw'] != "") {
                // checks if user exists in database
                $query = 'SELECT * FROM BoardUsers';
                $result = mysqli_query($connect, $query);
                // for all rows returned, check against username and password
                while ($row = mysqli_fetch_array($result)){
                    if($row["psw"] == $_POST["psw"] and $row["username"] == $_POST["uname"]){
                        // sign in successful
                        $_SESSION['uname'] = $_POST['uname'];
                        $_SESSION['fname'] = $row['firstName'];
                        echo '<li style="float:right"><a href="logout.php" id="sign-out">Sign out</a></li>';
                    }
                }
            }
            else{
                echo "<li style=\"float:right\"><a class=\"open-button\" onclick=\"openForm('signInForm')\" id=\"sign-in\">Sign in</a></li>";
            }
            // checks if the signup form was filled out and completely
            if(isset($_POST['signup-fname']) && $_POST['signup-fname'] != "" && isset($_POST['signup-lname']) && $_POST['signup-lname'] != "" && isset($_POST['signup-uname']) && $_POST['signup-uname'] != "" && isset($_POST['signup-psw']) && $_POST['signup-psw'] != "" && isset($_POST['signup-confirmpsw']) && $_POST['signup-confirmpsw'] != "" && $_POST['signup-psw'] == $_POST['signup-confirmpsw']){
                // searches if user already exists
                $duplicateFound = false;
                $query = 'SELECT * FROM BoardUsers';
                $result = mysqli_query($connect, $query);
                while ($row = mysqli_fetch_array($result)){
                    if($row["username"] == $_POST["signup-uname"]){
                        // duplicate found
                        echo '<li style="float:right"><a class="open-button" onclick="openForm(\'signUpForm\')" id="sign-up">Sign up</a></li>';
                        $duplicateFound = true;
                    }
                }
                // if no duplicates found, insert form data into users db table and display success message
                if(!$duplicateFound){
                    echo '<li style="float:right"><a class="open-button" onclick="openForm(\'signUpForm\')" id="sign-up">Sign up</a></li>';
                    $fname = $_POST['signup-fname'];
					$lname = $_POST['signup-lname'];
					$uname = $_POST['signup-uname'];
					$psw = $_POST['signup-psw'];
                    mysqli_query($connect, "INSERT INTO BoardUsers(firstName, lastName, username, psw) VALUES('$fname', '$lname', '$uname', '$psw')") or die(mysqli_error());
                }
            }
            else{
                $duplicateFound = true;
                echo '<li style="float:right"><a class="open-button" onclick="openForm(\'signUpForm\')" id="sign-up">Sign up</a></li>';
            }
            // login success popup
            if(isset($_SESSION['uname'])){
                echo '<li style="float:right"><a href="cart.php" id="cart">Cart</a></li>';
                echo '<div id="SuccessPopup">Welcome, ' . $_SESSION['fname'] . '!</div>';
            }
            // signup success popup
            if(!$duplicateFound){
                echo '<div id="SuccessPopup">Signup Successful!</div>';
            }
        ?>

            <div class="form-popup" id="signUpForm">
            <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']);?>" method="POST" class="form-container">
                <h2>Sign Up</h2>

                <label for="signup-fname"><b>First Name</b></label>
                <input type="text" placeholder="Enter First Name" value="<?php if(isset($_POST['signup-fname'])){echo $_POST['signup-fname'];} ?>" name="signup-fname" required>

                <label for="signup-lname"><b>Last Name</b></label>
                <input type="text" placeholder="Enter Last Name" value="<?php if(isset($_POST['signup-lname'])){echo $_POST['signup-lname'];} ?>" name="signup-lname" required>

                <label for="signup-uname"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" value="<?php if(isset($_POST['signup-uname'])){echo $_POST['signup-uname'];} ?>" name="signup-uname" required>

                <label for="signup-psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" value="<?php if(isset($_POST['signup-psw'])){echo $_POST['signup-psw'];} ?>" name="signup-psw" id="signup-psw" required>

                <label for="signup-confirmpsw"><b>Confirm Password</b></label>
                <input type="password" placeholder="Re-Enter Password" name="signup-confirmpsw" id="signup-confirmpsw" required>

                <button type="submit" class="btn">Sign Up</button>
                <button type="button" class="btn cancel" onclick="closeForm('signUpForm')">Close</button>
                
                <script>
                    // js code checks for confirm password match on signup
                    var password = document.getElementById("signup-psw"), confirm_password = document.getElementById("signup-confirmpsw");

                    function validatePassword(){
                    if(password.value != confirm_password.value) {
                        confirm_password.setCustomValidity("Passwords Don't Match");
                    } else {
                        confirm_password.setCustomValidity('');
                    }
                    }

                    password.onchange = validatePassword;
                    confirm_password.onkeyup = validatePassword;
                </script>
            </form>
            </div>

            <div class="form-popup" id="signInForm">
            <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']);?>" method="POST" class="form-container">
                <h2>Sign In</h2>

                <label for="uname"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="uname" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="psw" id = "psw" required>
                <button type="submit" class="btn">Login</button>
                <button type="button" class="btn cancel" onclick="closeForm('signInForm')">Close</button>
            </form>
            </div>
            
            <script>
            function openForm(formName) {
                // hides sign in/up forms if open before showing new form
                if(document.getElementById("signInForm").style.display == "block"){
                    document.getElementById("signInForm").style.display = "none";
                }
                if(document.getElementById("signUpForm").style.display == "block"){
                    document.getElementById("signUpForm").style.display = "none";
                }
                document.getElementById(formName).style.display = "block";

            }

            function closeForm(formName) {
                // hides sign in/up form
                document.getElementById(formName).style.display = "none";
            }
            </script>

        </ul>
    </body>