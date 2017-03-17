<?php

$imageFilePath = 'original.jpg';
$outputDir = '';

// 正規化原始圖檔，包含尺寸與格式，並且置中依最大長寬剪裁為正方形
$rectSize = 900;  // 正方形 Size
$inputFilePath = $imageFilePath;
$outputFilePath = $outputDir . '/resized.png';
$cmd = "convert -thumbnail '${rectSize}x${rectSize}^' -gravity center -extent ${rectSize}x${rectSize} -fill '#FFFFFF00' -opaque none  $inputFilePath $outputFilePath";
exec($cmd);

// 圖片加上圓角，圓角修剪設定為為透背
$roundSize = 30;  // 圓角 Size
$inputFilePath = $outputFilePath;
$outputFilePath = $outputDir . '/resized-round.png';
$cmd = "convert -size ${rectSize}x${rectSize} xc:none -fill white -draw 'roundRectangle 0,0 $rectSize,$rectSize $roundSize,$roundSize' $inputFilePath -compose SrcIn -composite $outputFilePath";
exec($cmd);

// 正規化浮水印，將既有的 PNG 透背圖調整為固定尺寸
$waterHeight = 'x120';  // 浮水印固定高度 x120, 固定寬度 120x
$waterMarkInputFilePath = $mainPath . '/water-mark.png';
$waterMarkFilePath = $mainPath . '/water-mark-resize.png';
exec("convert -thumbnail '${waterHeight}' $waterMarkInputFilePath  $waterMarkFilePath");

// 圖片右下角加上浮水印
$pedding = 15;  // 右下角與邊緣的距離
$inputFilePath = $outputFilePath;
$outputFilePath = $outputDir . '/resized-round-wm.png';
$cmd = "composite -dissolve 100% -gravity SouthEast -geometry +${pedding}+${pedding} $waterMarkFilePath $inputFilePath $outputFilePath";
exec($cmd);

// 移除圓角的透背效果，背景填上白色，轉成 JPEG 格式
$inputFilePath = $outputFilePath;
$outputFilePath = $outputDir . '/result.jpg';
$cmd = "convert $inputFilePath -background white -flatten -alpha off $outputFilePath";
exec($cmd);
