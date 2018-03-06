<?php
if (empty($_POST)) {
  die("You didn't fill out the form.");
}

$title = $_POST['title'];
$uploader = $_POST['uploader'];
$information = $_POST['information'];
$email = $_POST['email'];


$errors = array();

if (empty($uploader)) $errors[] = "You didn't input Uploader";
if (empty($title)) $errors[] = "You didn't input Title";

if (!empty($errors)) {
  $output  = '<ul><li>' . implode('</li><li>',$errors) . '</li><ul>';
  }

else {
    $output = 'Image uploaded!';

  $xml = simplexml_load_file('data/dataupl.xml');
  $newUpload = $xml->addChild('upload');
  $newUpload->addChild('uploader', $uploader);
  $newUpload->addChild('title', $title);
  if (!empty($information)) $newUpload->addChild('information', $information);
  if (!empty($email)) $newUpload->addChild('email', $email);

  // Muotoilu ja tallennus
  $dom = new DOMDocument("1.0");
  $dom->preserveWhiteSpace = false;
  $dom->formatOutput = true;
  $dom->loadXML($xml->asXML());
  $dom->save('data/dataupl.xml');

  }


$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 50000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType != "JPG" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["title"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
/*
header('refresh:2;url=upload.html');
echo $output; */
