<?php

function makePalette($palette)
{
  $greySat = 5;
  $whiteLight = 92;
  $greyLight = 80;
  $textLight = 15;

  // Convert to the usable HSL format
  // Find averages of all values

  $vAvg = $hAvg = $sAvg = $lAvg = 0;
  $y_part = $x_part = 0;

  foreach ($palette as $key => $value)
  {
    $palette[$key]['hsl'] = rgbToHsl($palette[$key]);
    $palette[$key]['hsl'][1] = min($palette[$key]['hsl'][1] + 10, 100);
    $palette[$key]['rgb'] = hslToRgb($palette[$key]['hsl']);
    $palette[$key]['str'] = $palette[$key]['hsl'][1] + $palette[$key]['hsl'][2];
    $palette[$key]['hex'] = rgbToHex($palette[$key]['rgb']);
    $palette[$key]['css'] = 'rgb('.implode(',',$palette[$key]['rgb']).')';

    $x_part += cos(deg2rad($palette[$key]['hsl'][0]));
		$y_part += sin(deg2rad($palette[$key]['hsl'][0]));

    $vAvg += $palette[$key]['str'];
    $sAvg += $palette[$key]['hsl'][1];
    $lAvg += $palette[$key]['hsl'][2];
  }

  $x_part /= count($palette);
	$y_part /= count($palette);

	$hAvg = atan2($y_part, $x_part);

  $vAvg = round($vAvg/count($palette));
  $sAvg = round($sAvg/count($palette));
  $lAvg = round($lAvg/(count($palette)*1.4));

  usort($palette, "lightSort");

  $v = $vDist = $priKey = 0;
  foreach ($palette as $key => $value)
  {
    if (end($palette) == $value) // make into background color
    {
      $palette[$key]['hsl'][1] = $greySat;
      $palette[$key]['hsl'][2] = $whiteLight;
      $palette[$key]['rgb'] = hslToRgb($palette[$key]['hsl']);
      $palette[$key]['hex'] = rgbToHex($palette[$key]['rgb']);
      $palette[$key]['str'] = $palette[$key]['hsl'][1] * $palette[$key]['hsl'][2];
      $palette[$key]['css'] = 'rgb('.implode(',',$palette[$key]['rgb']).')';
    }
    else
    {
      $x = deg2rad($value['hsl'][0]);
      $v = abs(atan2(sin($x-$hAvg), cos($x-$hAvg))) * $value['str'];
      $palette[$key]['val'] = round($v * 100);

      if ($v > $vDist)
      {
        $vDist = $v;
        $priKey = $key;
      }
    }
  }

  $primary = array_splice($palette, $priKey, 1);
  $palette = array_merge($primary, $palette);

  $key = 1;
  $palette[$key]['hsl'][1] = $greySat;
  $palette[$key]['hsl'][2] = $textLight;
  $palette[$key]['rgb'] = hslToRgb($palette[$key]['hsl']);
  $palette[$key]['hex'] = rgbToHex($palette[$key]['rgb']);
  $palette[$key]['str'] = $palette[$key]['hsl'][1] * $palette[$key]['hsl'][2];
  $palette[$key]['css'] = 'rgb('.implode(',',$palette[$key]['rgb']).')';

  $key = count($palette)-2;
  $palette[$key]['hsl'][1] = $greySat;
  $palette[$key]['hsl'][2] = $greyLight;
  $palette[$key]['rgb'] = hslToRgb($palette[$key]['hsl']);
  $palette[$key]['hex'] = rgbToHex($palette[$key]['rgb']);
  $palette[$key]['str'] = $palette[$key]['hsl'][1] * $palette[$key]['hsl'][2];
  $palette[$key]['css'] = 'rgb('.implode(',',$palette[$key]['rgb']).')';

  return $palette;
}

function meanAngle ($angles)
{
	$y_part = $x_part = 0;
	$size = count($angles);

	for ($i = 0; $i < $size; $i++)
  {
		$x_part += cos(deg2rad($angles[$i]));
		$y_part += sin(deg2rad($angles[$i]));
	}

	$x_part /= $size;
	$y_part /= $size;

	return rad2deg(atan2($y_part, $x_part));
}

function realURL($url)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Must be set to true so that PHP follows any "Location:" header
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $a = curl_exec($ch); // $a will contain all headers

  return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
}

function random_pic($dir = 'images')
{
    $files = glob($dir . '/*.*');
    $file = array_rand($files);
    return $files[$file];
}

function lightSort($a, $b)
{
  return $a['hsl'][2] - $b['hsl'][2];
}

?>
