<?php
function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>
<html>
    <head>
        <title>View</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta charset='utf-8'>
        	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

            <link rel="stylesheet" href="css/index.css">
            <link rel="stylesheet" href="css/style.css">

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    </head>
    <body>

<div id="data">
 <!-- if (isset($_GET['v'])) {echo ($_GET['v']);} -->
 <div class="header-action">
			<a href="/" class="btn-floating waves-effect waves-black btn-flat" id="close_button"><i class="material-icons">close</i></a>
		</div>
		<div class="modal-content" hidden>
		</div>
		<div id="data-preload">
			<div class="preloader-wrapper small active">
				<div class="spinner-layer spinner-gray-only">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div>
					<div class="gap-patch">
						<div class="circle"></div>
					</div>
					<div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div>
		</div>
</div>
<div class="shim"></div>
 <script src="js/index.js"></script>
 <script>
$.ajax({
data: {
  view:'<?php if (isset($_GET['v'])) {echo ($_GET['v']);}?>',
  ipv:'<?php echo getIp(); ?>'
},
type: "post",
url: "message.php",
success: function(data) {
  $("#data .modal-content").removeAttr("hidden");
  $("#data #data-preload").hide();
  $("#data .modal-content").html(data);
  $(".tooltipped").tooltip();
}
});
 </script>
    </body>
</html>
