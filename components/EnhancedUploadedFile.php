<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

//use \Yii\web\UploadedFile;
use Yii;
use yii\web\UploadedFile;

class EnhancedUploadedFile extends UploadedFile
{

    /**
     * @var resource a temporary uploaded stream resource used within PUT and PATCH request.
     */
    private $_tempResource;
    private static $_files;
    
    
    public function saveAs($file, $deleteTempFile = true)
    {
        if ($this->hasError) {
            return false;
        }

        $targetFile = Yii::getAlias($file);
        if (is_resource($this->_tempResource)) {
            $result = $this->copyTempFile($targetFile);
            return $deleteTempFile ? @fclose($this->_tempResource) : (bool) $result;
        }
        try {
            if ($deleteTempFile) {
                move_uploaded_file($this->tempName, $targetFile);
                // had to change this part of the code because, strangely enough, it didn't work the way it was written
            }
            else {
                copy($this->tempName, $targetFile);
            }
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }
}
