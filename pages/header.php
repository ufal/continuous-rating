<div id="header">
  <ul id="navigation">
    <li><strong>IWSLT 2022</strong> Evaluation</li>
    <li id="tutorial"><a href="?page=tutorial">Instructions</a></li>
    <li id="documents"><a href="?page=documents">Documents</a></li>
    <?php if ($_GET["page"] == "video") { ?> 
    <li class="gray"><i class="fas fa-chevron-right"></i></li>
    <li id="video" class="gray">
    <?php } ?>
    <?php
      if ($_GET["page"] == "video") {
        $result = $conn->query("SELECT topic FROM settings INNER JOIN videos on videos.name = settings.video WHERE settings.id='".$_GET["setting"]."'");
        if ($row = $result->fetch_assoc()) {
          echo $row["topic"];
        }
      }
    ?>
    </li>
  </ul>
  <ul>
    <li><?php echo $_SESSION["email"]; ?><a href="?page=logout"></li>
    <li>Sign out <i class="fas fa-sign-out-alt"></i></a></li>
  </ul>
</div>
<aside>
  <!--<?php include("side_menu.php"); ?>-->
</aside>