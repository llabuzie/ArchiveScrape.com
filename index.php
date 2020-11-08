<?php
  if(count($_GET)>0){
    $collection = $_GET["collection"];
  }else{
    $collection = "HomeMovies";
  }
  #$csv = array_map('str_getcsv',  $url);
  $collectionsValue = array("HomeMovies", "Ephemera", "ClassicTVCommercials", "Prelinger", "SilentFilms", "MoodMusic", "FeatureFilms");
  $collectionsShown = array("Home Movies", "Ephemera", "Classic TV Commercials", "Prelinger", "Silent Films", "Mood Music", "Feature Films");

  $selectOptions = "<option value=\"".$collection."\">".$collection."</option>";

  for($j = 0; $j<count($collectionsValue);$j++ ){
    if($collectionsValue[$j] != $collection){
      $selectOptions = $selectOptions."<option value=\"".$collectionsValue[$j]."\">".$collectionsShown[$j]."</option>";
    }

  }


  $url = $collection.".csv";
 ?>

 <?php
 #This fills out the area to create the playlist
 if(file_exists($url)){
   $csv = array_map('str_getcsv', file($url));
   $end = sizeof($csv);
   $string = " ";
   shuffle($csv);

   for($i = 0; $i<$end; $i++){
     //echo "<!-- randNum: ".$randomNumber."-->";
     if(!strpos($csv[$i][0],"\"")){
       $source = "{sources:[{file:\"".$csv[$i][0]."\"".", type: \"video/mp4\"}]}";
       $string = $string.$source;
       if($i != $end-1){
         $string = $string.",";
       }
     }
   }
  }
?>


<!DOCTYPE html>
<html>



<head>
  <title>ArchiveScrape.com</title>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="stylesheet" href="archiveStyles.css" type="text/css">
</head>

<body>

  <?php include("navigation.php"); ?>
  <div class = "drop_down_container">
    <form method="get">
      <select id = "collection" name = "collection" onchange="this.form.submit()">
        <?=$selectOptions?>
      </select>
    </form>
  </div>
  <br>
    <div class = "video">
      <script src="https://archive.org/jw/8/jwplayer.js"></script>
      <div id="player"> </div>
      <script>
        jwplayer('player').setup({
        "playlist": [

                <?=$string?>
              ],
        "startparam": "start",
        "repeat": "list",
        "width": 640,
        "height": 480,
        "autostart": true,
        "mute": true,
        "playlistNext": true
        });



        jwplayer('player').on('ready', function() {
          jwplayer('player').addButton(
           '<svg xmlns="http://www.w3.org/2000/svg" class="jw-svg-icon jw-svg-icon-next" viewBox="0 0 240 240"><path d="M165,60v53.3L59.2,42.8C56.9,41.3,55,42.3,55,45v150c0,2.7,1.9,3.8,4.2,2.2L165,126.6v53.3h20v-120L165,60L165,60z"></path></svg>',
           'Next',
           function() {
               if (jwplayer('player').getPlaylistIndex() === jwplayer('player').getPlaylist().length) {
                   jwplayer('player').playlistItem(0);
               }
               else {
                   jwplayer('player').playlistItem( Math.max(0, jwplayer('player').getPlaylistIndex() + 1) );
               }
           },
           'next',
           'jw-icon-next'
          );

        });
       jwplayer('player').on('ready', function() {
        jwplayer('player').addButton(
           '<svg xmlns="http://www.w3.org/2000/svg" class="jw-svg-icon jw-svg-icon-prev" viewBox="0 0 240 240"><path transform="translate(240, 0) scale(-1, 1) " d="M165,60v53.3L59.2,42.8C56.9,41.3,55,42.3,55,45v150c0,2.7,1.9,3.8,4.2,2.2L165,126.6v53.3h20v-120L165,60L165,60z"></path></svg>',
           'Previous',
           function() {
               if (jwplayer('player').getPlaylistIndex() === 0) {
                   jwplayer('player').playlistItem( Math.max(0, jwplayer('player').getPlaylist().length - 1) );
               }
               else {
                   jwplayer('player').playlistItem( Math.max(0, jwplayer('player').getPlaylistIndex() - 1) );
               }
           },
           'previous',
           'jw-icon-prev'
         );
        });

        function refreshURL() {
          console.log(jwplayer('player').getPlaylist()[jwplayer('player').getPlaylistIndex()].sources[0].file);
          document.getElementById('playlistIndex').innerHTML = jwplayer('player').getPlaylist()[jwplayer('player').getPlaylistIndex()].sources[0].file
          setTimeout(refreshURL, 500);
        }
        setTimeout(refreshURL, 500);


      </script>
      <p id = "playlistIndex"></p>

      <button style = "background-color: #1E1F20; color: white; border-color: white;margin: 5px;padding: 5px;" onclick="copyURL()">Copy video URL</button>

      <script>
        function copyURL() {
          /* Get the text field */
          var copyText = document.getElementById("playlistIndex").textContent;

          //copyText = copyText.outerHTML;
          /* Select the text field */
   /*For mobile devices*/
          copyTextToClipboard(copyText);
          console.log(copyText);
          /* Copy the text inside the text field */
          //document.execCommand("copy");


        }
        function fallbackCopyTextToClipboard(text) {
          var textArea = document.createElement("textarea");
          textArea.value = text;

          // Avoid scrolling to bottom
          textArea.style.top = "0";
          textArea.style.left = "0";
          textArea.style.position = "fixed";

          document.body.appendChild(textArea);
          textArea.focus();
          textArea.select();

          try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Fallback: Copying text command was ' + msg);
          } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
          }

          document.body.removeChild(textArea);
        }
        function copyTextToClipboard(text) {
          if (!navigator.clipboard) {
            fallbackCopyTextToClipboard(text);
            return;
          }
          navigator.clipboard.writeText(text).then(function() {
            console.log('Async: Copying to clipboard was successful!');
          }, function(err) {
            console.error('Async: Could not copy text: ', err);
          });
      }
      </script>

  </div>


  </br>



  <div class = "playlist">
      <iframe src="https://open.spotify.com/embed/playlist/1Zcl7KI77Xf8dtfZlGI65P" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media" class = "frame"> </iframe>
      </br>
      </br>
      <iframe src="https://open.spotify.com/embed/playlist/65hFZ8LEHHOSRNwkktF1GP" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media" class = "frame"></iframe>
      </br>
      </br>
      <iframe src="https://open.spotify.com/embed/playlist/0CNN1pbounEKtBZf2G5hAk" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media" class = "frame"></iframe>
      </br>
      </br>
      <iframe src="https://open.spotify.com/embed/playlist/1oAp4qqePcEF0stFoQuvbD" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media" class = "frame"></iframe>
      </br>
      </br>
      <iframe src="https://open.spotify.com/embed/playlist/5i6JIsPr0s0Da41KCaw8xC" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media" class = "frame"></iframe>
  </div>
  </br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
</body>
</html>
