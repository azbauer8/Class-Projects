<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Board Emporium Homepage</title>
    <style>

      body {font-family: "Lato", sans-serif; margin: 0;}


      .full-image {
        width: 100%;
        height: 100vh;
        background-color: #333;
        background-image: url("landing image.jpg");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: top;
        justify-content: center;
      }

      .display-4 {
        color: white;
        text-align: center;
        text-transform: uppercase;
        text-shadow: 3px 3px grey;
        padding-top: 10rem;
        font-weight: 1500;
        font-size: 60px;
        position: fixed;
        left: 29%;
        top: 1%;
      }

      .button {
        background-color: #1474AD;
        border: none;
        color: white;
        padding: 15px 32px;
        left: 45%;
        bottom: 18%;
        text-align: center;
        text-decoration: none;
        display: block;
        font-size: 25px;
        cursor: pointer;
        font-family: sans-serif;
      }

      .button2 {
        background-color: #1474AD;
        position: fixed;
        }


    </style>
  </head>
  <?php include("header.php"); ?>
  <body>
    <div class="full-image">
      <div class="container">
        <h1 class="display-4">The Board Emporium</h1>
        <button class="button button2" onclick="window.location.href = 'shop.php';">Shop Now!</button>
      </div>
    </div>
  </body>
  <?php include("footer.php"); ?>
</html>
