<?php

namespace Leedch\Codemonkey\Core;

use Exception;

/**
 * Class that manages folders
 *
 * @author leed
 */
class Folder {
    
    /**
     * Creates a full folder path
     * 
     * @param string $folderPath
     * @return void
     */
    public function createFolderIfNotExists($folderPath) {
        if (file_exists($folderPath) && is_dir($folderPath)) {
            return;
        }
        
        $arrFolder = explode(DIRECTORY_SEPARATOR, $folderPath);
        
        $fullPath = "";
        $first = true;
        foreach ($arrFolder as $folder) {
            if (!$folder) {
                continue; //Happens if the first char is a slash
            }
            if (!$first) {
                $fullPath .= DIRECTORY_SEPARATOR;
            } else {
                $first = false;
            }
            
            $fullPath .= $folder;
            
            try {
                $this->createSingleFolderIfNotExists($fullPath);
            } catch (Exception $e) {
                $msg = $e->getMessage();
                echo "Could not generate folder '".$fullPath."' <br />\n"
                        . $msg
                        . "<br />";
            }
        }        
    }
    
    /**
     * Creates a single folder
     * 
     * @param string $fullPath
     * @return void
     */   
    protected function createSingleFolderIfNotExists($fullPath) {
        $tempDir = pathCodemonkeyTempDir;
        if (file_exists($tempDir.$fullPath) && is_dir($tempDir.$fullPath)) {
            return;
        }
        mkdir($tempDir.$fullPath);
        chmod($tempDir.$fullPath, 0775);
    }
}
