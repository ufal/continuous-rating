<div class="small-center">
  <?php if ($_SESSION["anotator_id"] != 2) { ?>

  <h1>Overview of documents</h1>

  <p>The table captures some useful information about documents (videos/audios) and your progress in evaluation. For each document you have the following information:</p>
    <ul>
      <li><strong>Title</strong> - the document title.</li>
      <li><strong>Length</strong> - the document length.</li>
      <li><strong>Rating</strong> - the distribution of provided continous rating.</li>
      <li><strong>Seen</strong> - whether the document has already been evaluated. (<i class="far fa-check-square checked"></i> = already evaluated, <i class="far fa-square unchecked"></i> = not evaluated yet)</li>
    </ul>
  <p>
    To open a document for the evaluation click on its title. You can evaluate the documents in an arbitrary order. The audios are sorted by length.
  </p>

  <?php } ?>

  <?php if ($_SESSION["anotator_id"] == 2) { ?>
  <table>
    <tr>
      <th></th>
      <th>Email</th>
      <th>Seen audios</th>
      <th>Length(%)</th>
    </tr>
    <?php
      $result = $conn->query("SELECT anotators.id, anotators.email, GROUP_CONCAT(videos.length) as total_time, sum(settings.seen) as total_seen from anotators inner join settings ON settings.anotator = anotators.id inner join videos on videos.name = settings.video where settings.seen = 1 and anotators.id <> 2 group by anotators.id");

      $i = 1;
      while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $i . ".</td>";
        echo "<td>" . $row["email"] . "</td>";
        $total_time = 0;
        foreach (explode(",", $row["total_time"]) as $key => $value) {
          $parts = explode(":", $value);
          $total_time = $total_time + intval($parts[0]) * 60 + intval($parts[1]);
        }

        echo "<td>" . $row["total_seen"] . "/60</td>";
        # echo "<td>" . number_format(floatval($total_time / 3600), 2, '.', '') . " hours</td>";
        echo "<td>" . ceil($total_time / 241.2) . "%</td>";

        echo "</tr>";

        $i = $i + 1;
      }
    ?>
  </table>

  <table>
    <tr>
      <th></th>
      <th>Email</th>
      <th>Seen audios</th>
      <th>Length(%)</th>
    </tr>
    <?php
      $result = $conn->query("SELECT anotators.id, anotators.email, anotators.password, GROUP_CONCAT(videos.length) as total_time, sum(settings.seen) as total_seen from anotators inner join settings ON settings.anotator = anotators.id inner join videos on videos.name = settings.video and anotators.id <> 2 group by anotators.id having total_seen = 0");

      $i = 1;
      while($row = $result->fetch_assoc()) {
        $style = $row["password"] == "freezed" ? "background-color: #eee;" : "";
        $message = $row["password"] == "freezed" ? " (Task change)" : "";
        echo "<tr style=\"". $style ."\">";
        echo "<td>" . $i . ".</td>";
        echo "<td>" . $row["email"] . $message . "</td>";
        $total_time = 0;
        foreach (explode(",", $row["total_time"]) as $key => $value) {
          $parts = explode(":", $value);
          $total_time = $total_time + intval($parts[0]) * 60 + intval($parts[1]);
        }

        echo "<td>" . $row["total_seen"] . "/60</td>";
        # echo "<td>0 hours</td>";
        echo "<td>0%</td>";

        echo "</tr>";

        $i = $i + 1;
      }
    ?>
  </table>

  <?php } else { ?>

  <table>
    <tr>
      <th></th>
      <th>Title</th>
      <th>Length</th>
      <th>Rating</th>
      <th>Seen</th>
    </tr>
    <?php
      $result = $conn->query("SELECT settings.id, settings.video, settings.subtitles, settings.seen, videos.topic, videos.length FROM settings INNER JOIN videos ON settings.video = videos.name WHERE anotator='".$_SESSION["anotator_id"]."'");

      $i = 1;
      while($row = $result->fetch_assoc()) {
        $status = $row["seen"] ? "far fa-check-square checked" : "far fa-square unchecked";
        $status = $row["video"] == "" || !file_exists("./videos/".$row["video"]) || !file_exists("./subtitles/".$row["subtitles"]) ? "fas fa-exclamation-circle not-ready" : $status;
        $style = !file_exists("./subtitles/".$row["subtitles"]) ? "background-color: #ffcece;" : "";
        echo "<tr style=\"" . $style . "\">";
        echo "<td>" . $i . ".</td>";
        echo "<td><a href=\"?page=video&setting=" . $row["id"] . "\">" . $row["topic"] . "</td>";

        echo "<td>" . $row["length"] . "</td>";

        // feedback record
        echo "<td>";
        showFeedback($conn, $row);
        echo "</td>"; 

        echo "<td><i class=\"$status\"></i></td>";
        echo "</tr>";
        $i = $i + 1;
      }
    ?>
  </table>
  
  <?php } ?>

  <p></p>
</div>
