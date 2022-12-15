<?php
  session_start();
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "iwslt2022";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

  if (isset($_POST["email"]) && isset($_POST["password"])) {
    $result = $conn->query("SELECT id, email, password FROM anotators WHERE email='".$_POST['email']."'");
    
    if ($row = $result->fetch_assoc()) {
      if ($row["password"] == $_POST["password"]) {
        $_SESSION["anotator_id"] = $row["id"];
        $_SESSION["email"] = $row["email"];
        $_SESSION["loggedin"] = true;

        header("Location: index.php?page=tutorial");
      } else {
        header("Location: index.php?page=login&invalid");
      }
    } else {
      header("Location: index.php?page=login&invalid");
    }
  }
  
  if (!isset($_GET["page"])) header("Location: index.php?page=login");
  
  
  if (isset($_GET["page"]) && $_GET["page"] == "logout") {
    session_unset();
    session_destroy();
    $_SESSION["loggedin"] = false;
    $conn->close();
    header("Location: index.php?page=login");
  }
  
  if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    if (isset($_GET["page"]) && $_GET["page"] == "login")
      header("Location: index.php?page=tutorial");
  } else if (isset($_GET["page"]) && $_GET["page"] != "login")
    header("Location: index.php?page=login");

  if (isset($_GET["page"]) && $_GET["page"] == "video" && !isset($_GET["setting"])) header("Location: index.php?page=tutorial");

  if (isset($_GET["page"]) && $_GET["page"] == "video" && isset($_GET["setting"])) {
    $result = $conn->query("SELECT anotator FROM settings WHERE id='".$_GET["setting"]."'");
    if ($row = $result->fetch_assoc()) {
      if ($row["anotator"] != $_SESSION["anotator_id"])
        header("Location: index.php?page=error");
    } else
      header("Location: index.php?page=error");
  }

  function showFeedback($conn, $row) {
    try {
      $feedback = $conn->query("SELECT id, value FROM feedback WHERE setting_id='".$row["id"]."' ORDER BY id DESC")->fetch_assoc()["value"];
      $feedback = json_decode($feedback);

      if ($feedback == null) {
        throw new Exception();
      }

      $feedback = array_slice($feedback, 1);

      if (count($feedback) <= 0) {
        throw new Exception();
      }

      $lastTimestamp = $feedback[0][0];
      $lastValue = $feedback[0][1];
      $distribution = [0, 0, 0, 0, 0]; // ith item - total number of miliseconds of i-th feedback 
      foreach (array_slice($feedback, 1) as $item) {
        $currentTimestamp = $item[0];
        $currentValue = $item[1];
        $distribution[$lastValue] += $currentTimestamp - $lastTimestamp;
        
        $lastTimestamp = $currentTimestamp;
        $lastValue = $currentValue;
      }
      
      $sum = array_sum($distribution);
      
      for ($i=0; $i < 5; $i++) {
        echo "<span style=\"display: inline-block; width: " . round($distribution[$i] / $sum * 100) . "%;\" class=\"feedback-".$i."\"></span>";
      }

    } catch(Exception $e) {
      echo "<span style=\"width: 100%;\"></span>";
    }
  }
?> 

<!DOCTYPE html>
<html lang="sk">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="styles/subtitler.css">
  <link rel="stylesheet" type="text/css" href="styles/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <title>IWSLT 2022 - Evaluation demo</title>
</head>
<body>
  <?php
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)
      include("pages/header.php");
  ?>
  
  <aside class="content">
    <?php
      if ($_GET["page"] == "video") include("pages/video_loader.php");
      else if ($_GET["page"] == "tutorial") include("pages/tutorial.php");
      else if (($_GET["page"] == "documents")) include("pages/documents.php");
      else if ($_GET["page"] == "login") include("pages/login.php");
      else if ($_GET["page"] == "contact") include("pages/contact.php");
      else if ($_GET["page"] == "error") include("pages/error.php");
      else include("pages/error.php");
    ?>
  </aside>
</body>
<script src="scripts/script.js"></script>
</html>
