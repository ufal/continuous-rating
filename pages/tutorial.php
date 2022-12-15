<div class="small-center">
  <h1>IWSLT 2022 Evaluation</h1>

  <p class="underlined">This page describes the process of manual evaluation of simultaneous speech translation used for ranking systems  submitted to IWSLT 2022. The evaluation consists in playing the source sound with live text captions to speakers fluent in the source English and native in the target German, and collecting <strong>continuous rating</strong>.</p>
  <h3>Table of contents</h3>
  <ul>
    <li><a href="#documents"><strong>Documents</strong></a></li>
    <li><a href="#before-you-start"><strong>Before You Start</strong></a></li>
    <li><a href="#evaluation-process"><strong>Evaluation Process</strong></a></li>
    <li><a href="#after-you-finish"><strong>After You Finish</strong></a></li>
    <li><a href="#faq"><strong>Frequently Asked Questions</strong></a></li>
  </ul>
</div>
<div class="small-center bordered">
  <h1 id="documents">Documents</h1>

  <ul>
    <li>The overview of documents can be found in <strong>Documents</strong> tab of the menu. Please, find a more detailed description there.</li>
  </ul>
</div>
<div class="small-center bordered">
  <h1 id="before-you-start">Before You Start</h1>
    <ul>
      <li>Make sure that both <strong>the subtitles</strong> and <strong>buttons of continuous rating</strong> fits your screen viewport, as you can see in a demo at the <a href="#examplary-video">bottom of this page</a>.</li>
      <li>Be careful and <strong>do not try to pause</strong> the audio, or to manipulate the recording by any way. Do not worry you cannot see the audio controls, they are disabled.</li>
      <li>Be careful to watch the audio <strong>just once</strong> without any interruptions and <strong>avoid reloading</strong> the website.</li>
    </ul>
</div>
<div class="small-center bordered">
  <h1 id="evaluation-process">Evaluation Process</h1>
  <ul class="key-examples" style="margin-bottom: 20px;">
    <li>You can evaluate documents in <strong>an arbitrary order</strong>.</li>
    <li>The simulation of the subtitled presentation starts after <strong>clicking on the play button</strong>.</li>
    <li>The subtitles will be displayed inside a gray rectangle.</li>
    <li>We ask you to provide your assessment using so-called <strong>continuous rating</strong>, which continuously indicates <strong style="color: black;">the quality of the text output given the input utterance you hear</strong> in the range from <strong>1 (the worst) to 4 (the best)</strong>. There are two possibilities how to give the feedback:
      <ol>
        <li>by pressing one out of four keyboard keys <span>1</span><span>2</span><span>3</span><span>4</span> on the <strong>English keyboard layout</strong> (these keys are in the second row on the keyboard, below F keys and above letters qwer - please, do not use the numerical part of the keyboard, it will not work); note that the buttons react on these keys for an arbitrary keyboard layout, you do not have to use the English keyboard, <em>(strongly preferred)</em>
        <li>by clicking with the mouse on one out of the buttons <span>1</span><span>2</span><span>3</span><span>4</span> below subtitles, <em>(not recommended unless you are using a touch screen on a tablet)</em>.</li>
      </ol>
      <li>There is also a special button/key <span>space</span> which means "I have lost the attention for a short period of time". Use this button whenever you are not sure whether you know how to rate the quality of the text given the input sound.</li>
      <li>The rate of clicking/pressing depends on you. However, we suggest clicking <strong style="color: black;">each 5-10 seconds</strong> or when your assessment has changed. We encourage you to provide feedback <strong>as often as possible</strong> even if your assessment has <strong>not changed</strong>.</li>
  </ul>
  <h3></h3>
  <ul>
    <li>An examplary audio presentation is provided to you at the <a href="#examplary-video">bottom of this page</a>. You can try to watch the audio and find your preferred way of providing the feedback.</li>
    <li>You should notice <strong>highlighted border</strong> of the corresponding button after each click/press.</li>
  </ul>

</div>
<div class="small-center  bordered">
  <h1 id="after-you-finish">After You Finish</h1>
  <ul>
    <li>After the audio ends, there is room for subtitles to vanish, and for you to complete the rating. Please, <strong>do not leave the page</strong> before this message shows up:</li>
    <div class="finished visible" style="<?php echo $_SESSION["seen"] == "1" ? "display: block;" : ""; ?>">You have successfully finished watching this audio! The distribution of your rating is added to the table.</div>
  </ul>
  <h3></h3>
  <ul>
    <li>If <strong>Internet connection issues</strong> occur and the data are not received by a server, a message appears below the buttons of continuous rating:</li>
    <div class="warning visible"><i class="fas fa-exclamation-triangle"></i><span>Your Internet connection was not stable. Please, download the data <input type="button" class="dwn-button" value="here"/> and send them to us by email.</span></div>
    <li>Note that it does not influence the simulation of the subtitled presentation; the subtitles and the audio <strong>are preloaded</strong>.</li>
    <li>Be careful to download the data <strong>before</strong> you leave the page. Otherwise, the data will be lost.</li>
  </ul>
</div>

<div class="small-center bordered">
  <h1 id="faq">Frequently Asked Questions</h1>
  <p style="font-weight: bold;">Does the quality rating scale (1 (the worst) to 4 (the best)) only refer to the quality of the context of the translation (i.e., is the meaning preserved or lost) or does it also include the quality of proper spelling, grammar, and punctuation of the translated text?</p>
  The quality scale should reflect primarily the meaning preservation (i.e. evaluating primarily the "content" or very approximately the "adequacy") and the grammaticality and things like punctuation (i.e. the "form" or extremely roughly the "fluency") should be the secondary criterion.
  <h3></h3>
  <p style="font-weight: bold;">As the subtitles appear desynchronized with a few seconds, I can only rate for audio that was heard some seconds before I read the subtitles. So, I am unsure if this is a bug, in case I would be rating for past audio and from memory, or if that is a reason for bad rating itself, as I am reading something that does not correspond the what I am hearing in that exact moment.</p>
  The longer or smaller delay should not affect your scores. You should simply evaluate the content and how much of it was preserved.
</div>

<div class="small-center">
  <h1 id="examplary-video">Demo</h1>
  <p>This section is devoted to a simple one-minute long audio example that simulates simultaneous speech subtitling. Using this example may help you verify that you fully understand each part of the instructions, as described in the previous sections. Note that this audio is for demonstrative purposes only; <strong>continuous rating</strong> is not recorded so you can run the audio multiple times and choose a convenient way to provide your feedback (the rate, clicking the mouse button or pressing keys). <span style="color: red;">
  <p>You can also zoom in or zoom out the page by using Ctrl+ and Ctrl-, respectively. However, ensure that you can see the whole audio and the buttons of continuous rating.</p><p></p>

  <div style="position: relative; width: 24cm; margin: 0px auto;">
  <!-- <div class="video-overlay"></div>
    <button id="run-video" type="button"><i class="far fa-play-circle"></i></button>
    <video id="video" class="center">
      <source src="./videos/video_demo.mp4" type="video/mp4">
    </video>
    <div id="subtitles" class="overlay" style="left: 140px; top: 400px;"></div> -->

  <div style="height: 2cm;"></div>
  <div class="audio-overlay"></div>
  <button id="run-video" type="button" style="top: -20px; display: block;"><i class="far fa-play-circle"></i></button>
  <audio id="audio" class="center">
    <source src="./videos/video_demo.wav" type="audio/wav">
  </audio>
  <div id="subtitles" class="middle" style="left: 140px; top: 400px;"></div>

  </div>
  <div class="clear"></div>
  <div class="clicker">
    <input id="click-1" class="darkred" type="button" value="1" disabled>
    <input id="click-2" class="red" type="button" value="2" disabled>
    <input id="click-3" class="orange" type="button" value="3" disabled>
    <input id="click-4" class="green" type="button" value="4" disabled>
    <input id="click-0" class="green" type="button" value="SPACE (lost attention)" disabled>
  </div>
  <div class="warning"><i class="fas fa-exclamation-triangle"></i><span>Your Internet connection was not stable. Please, download the data <input type="button" id="dwn-btn" value="here"/> and send them to us by email.</span></div>
  <div style="height: 20px;"></div>
  <script src="scripts/subtitler.js"></script>
  <script>
  cf = {
    readingSpeed: 20,
    minReadingSpeed: 10,
    maxReadingSpeed: 25,
    completedRatio: 1.0,
    width: 163,
    lineCount: 2,
    fontSize: 4.8,
    wordPaddingTB: 1.2,
    wordPaddingLR: 1.0,
    slideUp: true,
    slideUpTime: 300,
    debug: false,
    logTime: false,
    logSpeed: false
  };
  document.getElementById("run-video").style.display = "none";

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
    $filename = "subtitles/video_demo_de-subtitles.txt";
    $file = fopen($filename, "r") or die("Unable to open file!");
    echo json_encode(explode("\n", fread($file, filesize($filename))));
    fclose($file);
  ?>;

  var feedback = [[-1, -1]];

  Subtitler.start();
  document.getElementById("run-video").addEventListener("click", function() {
    document.getElementById("audio").play();
    this.style.display = "none";
    enableButtons();
    process(subs);
    document.getElementById("subtitles").style.outline = "none";
  });

  document.getElementById("audio").onloadeddata = function() {
    document.getElementById("run-video").style.display = "block";
  };


  document.getElementById("dwn-btn").addEventListener("click", function(){
    var text = JSON.stringify(feedback);
    var filename = "a-s0-t" + feedback[0][0] + ".txt";
    
    download(filename, text);
  }, false);
</script>
</div>