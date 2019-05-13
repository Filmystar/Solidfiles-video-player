<?php
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
$id = $_GET['url'];
$original_id = my_simple_crypt($id, 'd');
$title  =  basename ($original_id);
$content = file_get_contents($original_id);
$first_step = explode( '"downloadUrl":"' , $content );
$second_step = explode('","ticket"', $first_step[1] );
$text1 = $second_step[0];
$text2 = str_replace('"', " ", $text1);
$file = '[{"type": "video/mp4", "label": "HD", "file": "'.$text2.'"}]';
$first_img = explode( '"splashUrl":"' , $content );
$second_img = explode('","streamUrl"', $first_img[1] );
$img1 = $second_img[0];
$img2 = str_replace('"', " ", $img1);
$first_title = explode( '<title>' , $content );
$second_title = explode('</title>', $first_title[1] );
$title1 = $second_title[0];
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?php echo $title1?></title>
</head>
<body>
  <div id="myElement"></div>
	<script src="bin/jwplayer-7.3.6/jwplayer.js"></script>	
	<link href="bin/skins/thin.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">jwplayer.key="Ywok59g9j93GtuSU7+axNzjIp/TBfiK4s0vvYg==";</script>
    <style type="text/css">*{margin:0;padding:0}#container{position:absolute;width:100%!important;height:100%!important}</style>
    <div id='container'></div>
    <script>var playerInstance = jwplayer('container');
      playerInstance.setup({
        sources: <?php echo $file?>,								
        image: '<?php echo $img2?>',
	allowfullscreen: true,
        width: '100%',
        aspectratio: '16:9',
        skin: {
	      name: "thin"
      },
      });
    </script>
</body>
</html>
