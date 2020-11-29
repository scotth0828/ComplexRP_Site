<?php

if (isset($_POST['submitAvatar'])) {

	$id = 0;

	if (isset($_POST['id'])) $id = $_POST['id'];

	$file = $_FILES['fileToUpload'];

	$fileName = $file['name'];
	$fileTmpName = $file['tmp_name'];
	$fileSize = $file['size'];
	$fileError = $file['error'];
	$fileType = $file['type'];

	$fileExt = explode('.', $fileName);
	$fileActualExt = strtolower(end($fileExt));

	$allowed = array('jpg', 'jpeg');

	if (in_array($fileActualExt, $allowed)) {
		if ($fileError === 0) {
			if ($fileSize < 50000) {
				$fileNameNew = $id.".".$fileActualExt;
				$fileDestination = 'avatars/'.$fileNameNew;
				move_uploaded_file($fileTmpName, $fileDestination);
				header('Location: editprofile.php');
			} else {
				echo 'File is too big!';
			}
		} else {
			echo 'Error uploading file!';
		}
	} else {
		echo 'Wrong file type!';
	}
}