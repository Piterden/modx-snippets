<?php
$allFormFields = $hook->getValues();
$user_id = $modx->user->get('id');
$angle = $allFormFields['angle'];
$res_id = $allFormFields['res_id']; // Work id
$basepath = $modx->config['base_path']; // Site root
//$modx->log(xPDO::LOG_LEVEL_ERROR,print_r($allFormFields, true));


$work = $modx->getObject('modResource', $res_id);
if ($tv = $modx->getObject('modTemplateVar', array('name'=>'photoItem'))) {
    $photo = $tv->getValue($res_id);
    $photoPath = $basepath . $photo;
    header('Content-type: image/jpeg');
    // Original file
    $original   =   imagecreatefromjpeg($photoPath);
    // Rotate
    $rotated    =   imagerotate($original, $angle, 0);
    if (imagejpeg($rotated, $photoPath)) {
        if (!$modx->loadClass('modPhpThumb',$modx->getOption('core_path').'model/phpthumb/',true,true)) {
            $modx->log(modX::LOG_LEVEL_ERROR,'[phpThumbOf] Could not load modPhpThumb class in plugin.');
            return;
        }
        $assetsPath = $modx->getOption('phpthumbof.assets_path',$scriptProperties,$modx->getOption('assets_path').'components/phpthumbof/');
        $phpThumb = new modPhpThumb($modx);
        $cacheDir = $assetsPath.'cache/';

        /* clear local cache */
        if (!empty($cacheDir)) {
            /** @var DirectoryIterator $file */
            foreach (new DirectoryIterator($cacheDir) as $file) {
                if (!$file->isFile()) continue;
                @unlink($file->getPathname());
            }
            $modx->getCacheManager()->clearCache();
        }
    }

    imagedestroy($original);
    imagedestroy($rotated);
};
return true;
