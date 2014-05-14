<?php
include 'Badge.php';
$elements = array();
echo "Welcome to Dynamic Badge Creation\n";
echo "Enter badge width and height separated by a space:";
list($badgeWidth,$badgeheight) = explode(" ",fgets(STDIN));
$newBadge = new Badge((int)$badgeWidth,(int)$badgeheight);
getmode:
echo "\n Enter 1 to supply a logo, 2 to supply a  static text element and 3 to supply a dynamic text element from a database column: ";
$mode = (int) fgets(STDIN);
echo ($mode);
if($mode===1){
  echo "\nEnter full path to the logo file:";
  $logoPath = (string) fgets(STDIN);
  $logoPath = trim(str_replace("\n","",$logoPath));
  echo "\n Enter logo properties(logowidth, logoheight, x, y) separated by space For e.g. (200 100 10 20): ";
  list($logoWidth,$logoHeight,$x,$y) = explode(" ",fgets(STDIN));
  $logoWidth = (float)$logoWidth; 
  $logoHeight = (float)$logoHeight;
  $x = (float)$x; 
  $y = (float)$y;
  $newBadge->insertLogo($logoPath, $logoWidth, $logoHeight, $x, $y);
  goto getmode;
}
if($mode===2){
  echo "\n Enter the static text you want to enter: ";
  $staticText = (string) fgets(STDIN);
  $staticText = trim(str_replace("\n","",$staticText));
  echo "\n Enter text insertion properties(x coordinate, y coordinate ) separated by space For e.g. (20 10): ";
  list($x,$y) = explode(" ",fgets(STDIN));
  $x = (float)$x; 
  $y = (float)$y;
  $newBadge->insertStaticText($staticText, $x, $y);
  goto getmode;
}
else if ($mode===3){
    echo "\n Enter the database host:";
    $host = (string) fgets(STDIN);
    $host = trim(str_replace("\n","",$host));
    echo "\n Enter the database user:";
    $user = (string) fgets(STDIN);
    $user = trim(str_replace("\n","",$user));
    echo "\n Enter the password for the user:";
    $passwd = (string) fgets(STDIN);
    $passwd = trim(str_replace("\n","",$passwd));
    echo "\n Enter the database name:";
    $database = (string) fgets(STDIN);
    $database = trim(str_replace("\n","",$database));
    echo "\n Enter the database table:";
    $table = (string) fgets(STDIN);
    $table = trim(str_replace("\n","",$table));
    echo "\n Enter the database columnname:";
    $columnname = (string) fgets(STDIN);
    $columnname = trim(str_replace("\n","",$columnname));
//    $dbConnection = trim(str_replace("\n","",$staticText));
//    echo $host,$user,$passwd,$database,$table,$columnname;exit;
    echo "\n Enter text insertion properties(x coordinate, y coordinate ) separated by space For e.g. (20 10):";
    list($x,$y) = explode(" ",fgets(STDIN));
    $x = (float)$x; 
    $y = (float)$y;
    $newBadge->inserDatabaseFields($host,$user,$passwd,$database, $table,$columnname, $x, $y);
    goto getmode;
}
else{
    $newBadge->outputBadges();
}