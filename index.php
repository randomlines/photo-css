<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Unsplash Tour</title>
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
<?php

// All the setup

spl_autoload_register(function ($class) {
    include(__DIR__ . "/classes/" . str_replace("\\", "/", $class) . ".php");
});
require_once('classes/colorFunctions.php');
require_once('classes/functions.php');
use ColorThief\ColorThief;

// Real fun starts here

// Getting the palette

$sourceImage = realURL("http://source.unsplash.com/360x360"); // from unsplash
// $sourceImage = random_pic(); // locally
// $sourceImage = "images/img-21.jpg";
$palette = ColorThief::getPalette($sourceImage, 5, 2);
$palette = makePalette($palette);

?>

<div class="primary" data-image="<?php echo $sourceImage; ?>" style="background-color:<?php echo $palette[0]['css']; ?>;"></div>
<ul class="palette">

<?php

// Display stuff

$labels = ['Primary', 'Text', 'Extra', 'Grey', 'White'];
foreach ($palette as $key => $value)
{
  echo '<li style="background-color:'.print_r($value['css'], true).'; color:'.($value['hsl'][2] > 60 ? $palette[1]['css'] : end($palette)['css']).'">'.$labels[$key].'<br>'.$value['hex'].'<br><br>'.$value['val'].'</li>';
}

?>

</ul>
<style>body { background-color: <?php echo end($palette)['css']; ?>; }</style>
<div class="sample-image"><img src="<?php echo $sourceImage; ?>"></div>

<?php

// End getting palette

?>
<footer><small>© 2016 Ashim D’Silva for The Random Lines, powered by <a href="http://www.kevinsubileau.fr/">Kevin Subileau</a>’s <a href="https://github.com/ksubileau/color-thief-php">port</a> of <a href="https://github.com/lokesh/color-thief">Lokesh Dhakar’s Color Thief</a> library.</small></footer>
</body>
</html>
