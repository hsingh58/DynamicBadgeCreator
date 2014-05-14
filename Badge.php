<?php

class Badge {
  private $badgeWidth=200;
  private $badgeHeight=200;
  private $canvas;
  private $canvasInstance = array();
  public function __construct($badgeWidth,$badgeHeight){
    $this->badgeWidth = $badgeWidth;
    $this->badgeHeight= $badgeHeight;
    echo "Proceeding to initialize your badge.\n";
    //Create a blank image
    $this->canvas = imagecreatetruecolor($this->badgeWidth, $this->badgeHeight);
    //create color
    $white = imagecolorallocate($this->canvas, 255, 255, 255); //this sets background to white
    //apply color to the background
    imagefill($this->canvas, 0, 0, $white);
    // now initialize the first instance of the canvas instance
    $width = imagesx($this->canvas);
    $height = imagesy($this->canvas);
    $this->canvasInstance[0] = imagecreatetruecolor($width, $height);
    @imagecopyresampled($this->canvasInstance[0], $this->canvas, 0, 0, 0, 0, $width, $height, $width, $height);
    echo "Badge initialized successfully!\n";
   }
  public function insertLogo($logoPath, $logoWidth, $logoHeight, $x, $y){
    $logoFileEntension = substr($logoPath, strpos($logoPath, ".")+1);
    echo "$logoFileEntension";
    if(trim($logoFileEntension)==="png"){
      $img = imagecreatefrompng($logoPath);
    }
    else if($logoFileEntension==="jpeg"){
      $img = imagecreatefromjpeg ($logoPath);
    }
    else{
      echo "\nThis is not an acceptable image file type for this application. Terminating execution.";
      exit;
    }
    list($width, $height) = getimagesize($logoPath);
    @imagecopyresampled($this->canvas, $img, $x, $y, 0, 0, $logoWidth, $logoHeight, $width, $height);
    //sync the initial master copy with the canvas schema ($canvas)
    for($i=0; $i < count($this->canvasInstance); $i++){
       @imagecopyresampled($this->canvasInstance[$i], $img, $x, $y, 0, 0, $logoWidth, $logoHeight, $width, $height);
     }
    echo "\nThe requested logo image has been inserted into your canvas conforming to the size of width=$logoWidth and height=$logoHeight at x=$x and y=$y";
   }
  public function insertStaticText($staticText,$x,$y){
     $black = imagecolorallocate($this->canvas, 0, 0, 0);
     $font ='arial.ttf';
     imagettftext($this->canvas, 20, 0, $x, $y, $black, $font, $staticText);
     //sync the canvas instances with the canvas schema ($canvas)
     for($i=0; $i < count($this->canvasInstance); $i++){
       $black = imagecolorallocate($this->canvasInstance[$i], 0, 0, 0);
       $font ='arial.ttf';
       imagettftext($this->canvasInstance[$i], 20, 0, $x, $y, $black, $font, $staticText);
     }
   }
  public function inserDatabaseFields($host,$user,$passwd,$database,$table,$columnname,$x,$y){
       $db = new PDO("mysql:host=$host;dbname=$database", "$user", "$passwd");
       $stmt = $db->prepare("select $columnname from $table");
       $stmt->execute();
       $count = 0;
       while($row=$stmt->fetch()){
           echo "\n$row[$columnname]";
           if (!isset($this->canvasInstance[$count])){
             //sync the new instance to the current existing canvas schema
             $width = imagesx($this->canvas);
             $height = imagesy($this->canvas);
             $this->canvasInstance[$count] = imagecreatetruecolor($width, $height);
             @imagecopyresampled($this->canvasInstance[$count], $this->canvas, 0, 0, 0, 0, $width, $height, $width, $height);
           }
           $black = imagecolorallocate($this->canvasInstance[$count], 0, 0, 0);
           $font ='arial.ttf';
           imagettftext($this->canvasInstance[$count++], 20, 0, $x, $y, $black, $font, $row[$columnname]);
       }
       $stmt->closeCursor();
}
  public function outputBadges() {
       for($i=0;$i < count($this->canvasInstance); $i++){
       //outputimage as badge file
       imagejpeg($this->canvasInstance[$i],"badge$i.jpg");
     }
}
}
