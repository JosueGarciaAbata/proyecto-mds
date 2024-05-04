<?php
/*
 * 
 *Generar nombre unico para la imagen 
 * 
 */
function generateUniqueName($extension)
{
  $nameEncrypted = bin2hex(random_bytes(8));
  return $nameEncrypted . '.' . $extension;
}



/*
 *
 *
 *Guardar la imagen
 *
 *
 */
function uploadImage($folderDestination, $imageName, $clear)
{
  if (isset($_FILES[$imageName]) && $_FILES[$imageName]['error'] === UPLOAD_ERR_OK) {
    $imageFileType = exif_imagetype($_FILES[$imageName]['tmp_name']);
    if ($imageFileType !== false) {
      $allowedTypes = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
      if (in_array($imageFileType, $allowedTypes)) {
        $tempDir = $folderDestination;
        if (!is_dir($tempDir)) {
          mkdir($tempDir, 0755, true);
        }
        if ($clear) {
          $files = glob($tempDir . '/*');
          foreach ($files as $file) {
            if (is_file($file)) {
              unlink($file);
            }
          }
        }
        $extension = pathinfo($_FILES[$imageName]['name'], PATHINFO_EXTENSION);
        $nameFile = generateUniqueName($extension);
        $targetFile = $tempDir . $nameFile;
        if (move_uploaded_file($_FILES[$imageName]['tmp_name'], $targetFile)) {
          // Redimensionar la imagen a 300x168 píxeles
          //resizeImage($targetFile, $targetFile, 300, 168);
          return preg_replace('/^\.\.\//', '', $targetFile, 1);
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  } else {
    return false;
  }
}

/*
 *
 *
 *Redimensionar la imagen
 *
 *
 */
function resizeImage($sourceFile, $targetFile, $newWidth, $newHeight)
{
  list($srcWidth, $srcHeight, $type) = getimagesize($sourceFile);
  $sourceImage = null;
  switch ($type) {
    case IMAGETYPE_JPEG:
      $sourceImage = imagecreatefromjpeg($sourceFile);
      break;
    case IMAGETYPE_PNG:
      $sourceImage = imagecreatefrompng($sourceFile);
      break;
    case IMAGETYPE_GIF:
      $sourceImage = imagecreatefromgif($sourceFile);
      break;
    default:
      return false;
  }
  if (!$sourceImage) {
    return false;
  }
  $resizedImage = imagescale($sourceImage, $newWidth, $newHeight);
  if (!$resizedImage) {
    return false;
  }
  switch ($type) {
    case IMAGETYPE_JPEG:
      imagejpeg($resizedImage, $targetFile);
      break;
    case IMAGETYPE_PNG:
      imagepng($resizedImage, $targetFile);
      break;
    case IMAGETYPE_GIF:
      imagegif($resizedImage, $targetFile);
      break;
    default:
      return false;
  }
  imagedestroy($sourceImage);
  imagedestroy($resizedImage);
  return true;
}

?>