<?php
//if not authorized, redirect to login and exit
?>

<html>

<head>
  <title>Game Client</title>
  <link rel="stylesheet" type="text/css" href="media/css/client.css" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  <script type="text/javascript" src="media/js/client.js"></script> 
</head>

<body>

<div id="chatwindow">
  <div id="messagewindow">
  <!-- messages -->
  </div>
  <div id="inputcontainer">
    <input type="text" name="message" id="message"></input>
    <input type="submit" value="Send" id="send"></input>
  </div>
</div>

</body>

</html>
