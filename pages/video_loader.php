<?php
  if (isset($_POST["start"])) {
    $_SESSION["feedback"] = array();
    array_push($_SESSION["feedback"], [intval($_POST["start"]), -1]);

    $conn->query("INSERT INTO feedback (setting_id, value, logging) VALUES ('".$_GET["setting"]."','".json_encode($_SESSION["feedback"])."','')");
    $_SESSION["feedback_id"] = $conn->insert_id;
  }

  if (isset($_POST["time"])) {
    $diff = intval($_POST["time"]) - intval($_SESSION["feedback"][0][0]);
    array_push($_SESSION["feedback"], [$diff, intval($_POST["status"])]);

    $conn->query("UPDATE feedback SET value='".json_encode($_SESSION["feedback"])."' WHERE id='".$_SESSION["feedback_id"]."'");
  }

  if (isset($_POST["seen"]))
    $conn->query("UPDATE settings SET seen='1' WHERE id='".$_GET["setting"]."'");

  if (isset($_POST["logs"]))
    $conn->query("UPDATE feedback SET logging='".$_POST["logs"]."' WHERE id='".$_SESSION["feedback_id"]."'");

  $result = $conn->query("SELECT video, subtitles, topic, configuration, layout, seen FROM settings INNER JOIN videos ON settings.video = videos.name WHERE settings.id='".$_GET["setting"]."'");

  if ($row = $result->fetch_assoc()) {
    $_SESSION["video"] = $row["video"];
    $_SESSION["topic"] = $row["topic"];
    $_SESSION["subtitles"] = $row["subtitles"];
    $_SESSION["configuration"] = $row["configuration"];
    $_SESSION["layout"] = $row["layout"];
    $_SESSION["seen"] = $row["seen"];
  } else exit();
?>

<h1><?php
  // javorsky/bojar/machacek/all
  if ($_SESSION["anotator_id"] <= 0)
    echo "Experiment num. ".$_GET["setting"]." - ".$_SESSION["topic"];
  else
    echo $_SESSION["topic"];
?></h1>
<div class="small-center <?php echo $_SESSION["seen"] == "1" ? "hidden" : ""; ?>">
<div style="position: relative; width: 24cm; margin: 0px auto;">
    <div style="height: 2cm;"></div>
    <div class="audio-overlay"></div>
    <p id="loading">The audio is loading. It can take a while...</p>
    <button id="run-video" type="button"><i class="far fa-play-circle"></i></button>
    <audio id="source" class="center">
    </audio>
</div>
<div id="subtitles" class="<?php
  if ($_SESSION["layout"] == "left")
    echo "right";
  if ($_SESSION["layout"] == "right")
    echo "left";
  if ($_SESSION["layout"] == "overlay")
    echo "overlay";
  if ($_SESSION["layout"] == "scrollable")
    echo "scrollable";
?>"></div>
<div class="clear"></div>
<?php if ($_SESSION["layout"] != "scrollable") { ?>
<div class="clicker">
  <input id="click-1" class="darkred" type="button" value="1" disabled>
  <input id="click-2" class="red" type="button" value="2" disabled>
  <input id="click-3" class="orange" type="button" value="3" disabled>
  <input id="click-4" class="green" type="button" value="4" disabled>
  <input id="click-0" class="green" type="button" value="SPACE (lost attention)" disabled>
</div>
<div class="warning"><i class="fas fa-exclamation-triangle"></i><span>Your Internet connection was not stable. Please, download the data <input type="button" id="dwn-btn" value="here"/> and send them to us by email.</span></div>
<?php } else { ?>
  <input type="button" id="dwn-subtitles" value="Stiahnuť titulky v textovom súbore"/>
<?php } ?>
</div>
<div class="finished" style="<?php echo $_SESSION["seen"] == "1" ? "display: block;" : ""; ?>">You have successfully finished watching this video/audio! The distribution of your rating is added to the table.</div>
<!-- <?php
  if ($_SESSION["seen"] == "1") {
    $result = $conn->query("SELECT id, seen FROM settings WHERE id='".$_GET["setting"]."'");

    if ($row = $result->fetch_assoc()) {
      echo "<div class=\"feedback\">";
      showFeedback($conn, $row);
      echo "</div>";
    }
  }
?> -->
<script src="scripts/subtitler.js"></script>
<script>
  cf = <?php echo $_SESSION["configuration"]; ?>;

  // VIDEO LOADING

function setFeedback(number) {
  let button = $("#click-" + number);
  button.click(function() {
    sendFeedback(number);

    $(this).animate({borderColor: "#888888"}, 200)
           .animate({borderColor: "#dadce0"}, 200);
  });
}

async function highlight(element) {
  element.style.borderColor = "black";
  
  await new Promise(f => setTimeout(
    g => element.style.borderColor = "white", 500));
}

document.addEventListener("keydown", function(event) {
  parseAndSendFeedback(event.code)}, false);

function parseAndSendFeedback(code) {
  if (code == "Digit1") {
    if ($("#click-1").prop("disabled") == true) return;
    sendFeedback(1);
    $("#click-1").animate({borderColor: "#888888"}, 200)
                 .animate({borderColor: "#dadce0"}, 200);
  }
  if (code == "Digit2") {
    if ($("#click-2").prop("disabled") == true) return;
    sendFeedback(2);
    $("#click-2").animate({borderColor: "#888888"}, 200)
                 .animate({borderColor: "#dadce0"}, 200);
  }
  if (code == "Digit3") {
    if ($("#click-3").prop("disabled") == true) return;
    sendFeedback(3);
    $("#click-3").animate({borderColor: "#888888"}, 200)
                 .animate({borderColor: "#dadce0"}, 200);
  }
  if (code == "Digit4") {
    if ($("#click-4").prop("disabled") == true) return;
    sendFeedback(4);
    $("#click-4").animate({borderColor: "#888888"}, 200)
                 .animate({borderColor: "#dadce0"}, 200);
  }
  if (code == "Space") {
    if ($("#click-0").prop("disabled") == true) return;
    sendFeedback(0);
    $("#click-0").animate({borderColor: "#888888"}, 200)
                 .animate({borderColor: "#dadce0"}, 200);
  }
}

$("#run-video").click(function() {
  let curTime = new Date().getTime();
  feedback[0] = [curTime, -1];

  $.ajax({
    url: "",
    type: "POST",
    data: {start: curTime},
    error: function error(xhr, status, error) {},
    success: function success(result,status,xhr) {}
  });
});

setFeedback(0);
setFeedback(1);
setFeedback(2);
setFeedback(3);
setFeedback(4);

function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);

  element.style.display = 'none';
  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);
}

let feedbackError = false;

async function process(messages) {
  let delay = 0, lastTimestamp = 0;
  let startTime = new Date().getTime();

  for (let message of messages) {
    let [currentTimestamp, rest] = splitWithRest(message, " ", 1);
    currentTimestamp = parseFloat(currentTimestamp);

    if (lastTimestamp > currentTimestamp) {
      delay = 0;
    } else {
      delay = currentTimestamp - (new Date().getTime() - startTime);
      //console.log(delay);
      //console.log("Given timestamp:    " + currentTimestamp);
      //console.log("Computed timestamp: " + (new Date().getTime() - startTime));
      //console.log("Diff:               " + (currentTimestamp - new Date().getTime() + startTime));
      lastTimestamp = currentTimestamp;
    }
    await new Promise(res => setTimeout(res, delay));
    
    try {
      data = JSON.parse(rest);
    } catch {
      data = rest;
    }

    Subtitler.update(data);
  }

  if (feedbackError) $(".warning").css("display", "block");

  setSeen();
  setLogs(logRecord);

  await new Promise(res => setTimeout(res, 20000));

  setSeen();
  setLogs(logRecord);
  showMessage();
  disableButtons();
}

function showMessage() {
  $(".finished").slideDown();
}

function sendFeedback(number) {
  let curTime = new Date().getTime();
  feedback.push([curTime - feedback[0][0], number]);

  $.ajax({
    url: "",
    type: "POST",
    data: {time: curTime, status: number},
    error: function error(xhr, status, error) {
      feedbackError = true;
    },
    success: function success(result,status,xhr) {}
  });
}

function setSeen() {
  $.ajax({
    url: "",
    type: "POST",
    data: {seen: 1},
    error: function error(xhr, status, error) {},
    success: function success(result, status, xhr) {}
  });
}

function setLogs(logging) {
  $.ajax({
    url: "",
    type: "POST",
    data: {logs: logging},
    error: function error(xhr, status, error) {},
    success: function success(result, status, xhr) {}
  });
}

function enableButtons() {
  $(".clicker").children().prop("disabled", false);
  $(".clicker").children().addClass("enabled");
}

function disableButtons() {
  $(".clicker").children().prop("disabled", true);
  $(".clicker").children().removeClass("enabled");
}

window.addEventListener('keydown', function(e) {
  if(e.keyCode == 32) {
    e.preventDefault();
  }
});

  var subs = <?php
    if ($_SESSION["layout"] == "unflicker") {
      $unflicker_file = "subtitles/" . explode(".", $_SESSION["video"])[0] . "_subtitles.unflicker.txt";
      $file = fopen($unflicker_file, "r") or die("Unable to open file!");
      echo json_encode(explode("\n", fread($file, filesize($unflicker_file))));
      fclose($file);
    } else {
      // $json_file = "subtitles/" . explode(".", $_SESSION["video"])[0] . "_subtitles.json";
      // $txt_file = "subtitles/" . explode(".", $_SESSION["video"])[0] . "_subtitles.txt";
      // $filename = file_exists($json_file) ? $json_file : $txt_file;
      $txt_file = "subtitles/" . $_SESSION["subtitles"];
      $file = fopen($txt_file, "r") or die("Unable to open file!");
      echo json_encode(explode("\n", fread($file, filesize($txt_file))));
      fclose($file);
    }
  ?>;

  var scrollableSubs = <?php
    if ($_SESSION["layout"] == "scrollable") {
      $filename = "subtitles/" . explode(".", $_SESSION["video"])[0] . "_scrollable.txt";
      $file = fopen($filename, "r") or die("Unable to open file!");
      echo json_encode(explode("\n", fread($file, filesize($filename))));
      fclose($file);
    } else {
      echo "null";
    }
  ?>;

  var feedback = [[-1, -1]];

  <?php if ($_SESSION["layout"] != "scrollable") { ?>
  Subtitler.start();
  <?php } ?>
  document.getElementById("run-video").addEventListener("click", function() {
    document.getElementById("source").play();
    this.style.display = "none";
    enableButtons();
    <?php if ($_SESSION["layout"] != "scrollable") { ?>
    process(subs);
    <?php } else { ?>
    setSeen();
    document.getElementById("subtitles").textContent = scrollableSubs;
    var videoOverlay = document.getElementsByClassName("video-overlay")[0];
    var audioOverlay = document.getElementsByClassName("audio-overlay")[0];
    if (videoOverlay != undefined)
      document.getElementsByClassName("video-overlay")[0].style.display = "none";

    if (audioOverlay != undefined)
      document.getElementsByClassName("audio-overlay")[0].style.display = "none";
    <?php } ?>
    document.getElementById("subtitles").style.outline = "none";
  });

  (function preload() {
    var req = new XMLHttpRequest();
    req.open('GET', '<?php echo "videos/".$_SESSION["video"]; ?>', true);
    req.responseType = 'blob';

    req.onload = function() {
      // Onload is triggered even on 404
      // so we need to check the status code
      if (this.status === 200) {
          var videoBlob = this.response;
          var vid = URL.createObjectURL(videoBlob); // IE10+
          // Video is now downloaded
          // and we can set it as source on the video element
          document.getElementById("source").src = vid;
      }
    }
    req.onerror = function() {
      // Error
    }

    req.send();
  })();

  document.getElementById("source").onloadeddata = function() {
    //console.log(document.getElementById("source"));
    document.getElementById("run-video").style.display = "block";
    document.getElementById("loading").style.display = "none";
  };

  if (document.getElementsByTagName("audio")[0] != undefined) {
    document.getElementById("run-video").style.top = "-20px";
  }

  var downloadButton = document.getElementById("dwn-btn");
  if (downloadButton != undefined) {
    downloadButton.addEventListener("click", function(){
      var text = JSON.stringify(feedback);
      var filename = "<?php echo "a".$_SESSION["anotator_id"]."-s".$_GET["setting"]."-t"; ?>" + feedback[0][0] + ".txt";

      download(filename, text);
    }, false);
  }

  // document.getElementById("dwn-subtitles").addEventListener("click", function(){
  //   var text = `<?php
  //     $filename = "subtitles/" . explode(".", $_SESSION["video"])[0] . "_subtitles_lines.txt";
  //     if (file_exists($filename)) {
  //       $file = fopen($filename, "r") or die("Unable to open file!");
  //       $text = fread($file, filesize($filename));
  //       echo $text;
  //       fclose($file);
  //     } else {
  //       echo "";
  //     }
  //   ?>`;

  //   var filename = "<?php echo "exp-".$_GET["setting"]; ?>" + ".txt";

  //   download(filename, text);
  // }, false);
</script>
