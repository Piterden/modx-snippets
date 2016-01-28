<?php
$ext_array = explode(',', mb_strtolower($extensions));

// Create path
$basepath = $modx->config['base_path']; // Site root
$target_path = $basepath . $path; // root /assets/upload

// Create MODX translit generator
$generator = $modx->newObject('modResource');

// Get Filename and make sure its good.
$filename = basename( $_FILES['nomination_file']['name'] );
$ext = mb_strtolower(pathinfo($filename, PATHINFO_EXTENSION));
$filename = substr($generator->cleanAlias($filename), 0, -strlen($ext));
$filename = $filename.$ext;

//return;

if($filename != '')
{
    // Make filename a good unique filename.
    // Make lowercase
    $filename = mb_strtolower($filename);
    // Replace spaces with _
    $filename = str_replace('..', '.', $filename);
    $filename = str_replace(' ', '_', $filename);
    // Add timestamp
    $filename = date("Ymdgi") . $filename;

    // Set final path
    $target_path = $target_path . $filename;

    if(in_array($ext, $ext_array))
    {
        $imagesize = getimagesize($_FILES['nomination_file']['tmp_name']);
        //$modx->log(xPDO::LOG_LEVEL_ERROR,print_r($imagesize, true));
        if ($imagesize[0] < 400 || $imagesize[1] < 300) {
            $errorMsg = 'Минимальные размеры изображения 400x300 пикселей';
            $hook->addError('nomination_file',$errorMsg);
            return false;
        }
        if ($imagesize[0] > 4000 || $imagesize[1] > 3000) {
            $errorMsg = 'Максимальные размеры изображения 4000x3000 пикселей';
            $hook->addError('nomination_file',$errorMsg);
            return false;
        }

        if(move_uploaded_file($_FILES['nomination_file']['tmp_name'], $target_path))
        {
            // Upload successful
            //$hook->setValue('nomination_file',$_FILES['nomination_file']['name']);
            $hook->setValue('nomination_file',$filename);
            return true;
        }
        else
        {
            // File not uploaded
            $errorMsg = 'Файл не загружен.';
            $hook->addError('nomination_file',$errorMsg);
            return false;
        }
    }
    else
    {
        // File type not allowed
        $errorMsg = 'Неправильный тип файла. Загрузите файл '.$extensions.'.';
        $hook->addError('nomination_file',$errorMsg);
        return false;
    }
}
else
{
    //$modx->log(xPDO::LOG_LEVEL_ERROR,print_r('2', true));
    $errorMsg = 'Добавьте файл!';
    $hook->addError('nomination_file',$errorMsg);
    $hook->setValue('nomination_file','');
    return false;
}
