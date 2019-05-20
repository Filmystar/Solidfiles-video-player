<?php
define("base_url", "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

function my_simple_crypt( $string, $action = 'e' ) {
  $secret_key = 'html5';
  $secret_iv = 'video';
  $output = false;
  $encrypt_method = "AES-256-CBC";
  $key = hash( 'sha256', $secret_key );
  $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
  if( $action == 'e' ) {
    $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
  }else if( $action == 'd' ){
    $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
  }
  return $output;
}

error_reporting(0);
if($_POST['submit'] != ""){
	$url = $_POST['url'];
	$iframeid = my_simple_crypt($url);
	$content = file_get_contents($url);
	$first_step = explode( '"downloadUrl":"' , $content );
	$second_step = explode('","ticket"', $first_step[1] );
	$text1 = $second_step[0];
	$text2 = str_replace('"', " ", $text1);
	$file = '[{"type": "video/mp4", "label": "HD", "file": "'.$text2.'"}]';
  $first_img = explode( '"splashUrl":"' , $content );
	$second_img = explode('","streamUrl"', $first_img[1] );
  $img1 = $second_img[0];
	$img2 = str_replace('"', " ", $img1);

}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="googlebot" content="noindex">
    <meta name="robots" content="noindex">
    <meta name="referrer" content="never">
	<title>Solidfile video player</title>
</head>
<body>

  <!-- Docs styles -->
    <link rel="stylesheet" href="css/AdminLTE.min.css">
    <link rel="stylesheet" href="css/stylesheet.css">
    <style>
		.container {
		  max-width: 800px;
		  margin: 0 auto;
		}
	</style>
    
    <h2>Solidfile video player</h2>
    
    
    <div id="text"><a href="index.php">Home</a> | <a href="#about">About</a> | <a href="#contact">Contact Us</a> | <a href="#contact">Website Support</a></div>

    <div class="container">
		<br />
		<center>
		<form action="" method="POST">
			<input type="text" size="70" name="url" value="http://www.solidfiles.com/v/53eYPNLrv2wQ8"/>
			<input type="submit" value="GET" name="submit" />
			</form>
		</center>
		<br/>

		<div id="myElement"><center>Paste the url and click the get button.</center></div>

		<br>
		<div><?php if($iframeid){echo "Embed"; echo '<textarea style="margin:10px;width: 97%;height: 80px;">&lt;iframe src="'.base_url.'embed.php?url='.$iframeid.'" width="640" height="360" frameborder="0" scrolling="no" allowfullscreen&gt;&lt;/iframe&gt;</textarea>';}?></div>
    
    </div>
    <!-- Player Script -->
    <script src="bin/jwplayer-7.3.6/jwplayer.js"></script>	
    <link href="bin/skins/thin.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">jwplayer.key="Ywok59g9j93GtuSU7+axNzjIp/TBfiK4s0vvYg==";</script>
    <script type="text/javascript">
		jwplayer("myElement").setup({
			playlist: [{
        "image": "<?php echo $img2?>", 
				"sources":<?php echo $file?>
			}],
			allowfullscreen: true,
			width: '100%',
			aspectratio: '16:9',
			skin: {
			name: "thin"
			},
		});
	</script>
	
<!--footer-->	  
    <div class="footer">
    <center>Copyright &#169; <script type='text/javascript'>var creditsyear = new Date();document.write(creditsyear.getFullYear());</script> <a href='index.php'>Embed Video Player Generator</a>. All rights reserved.
        <p>Posted by: <a href="https:<yourdomain.com>"><yourdomain.com></a></p></center>
    </div>
    
</body>
</html>
