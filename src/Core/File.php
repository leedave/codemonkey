<?php

namespace Leedch\Codemonkey\Core;

use Exception;
use Leedch\Codemonkey\Core\Core;
use Leedch\Codemonkey\Core\Folder;

/**
 * So this Class generates a File out of your templates so you can download 
 * it later, instead of writing it from scratch.... yeah we like being lazy
 *
 * @author leed
 */
class File extends Core{
    
    protected $filePath = "";
    protected $arrTemplates = [];
    protected $attributes = [];
    protected $placeHolderStart = '{{$';
    protected $placeHolderEnd = '}}';

    /**
     * Normal Constructor
     * 
     * @param string $filePath The filename 
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function setFilePath($filePath) {
        $this->filePath = $filePath;
    }
    
    /**
     * Adds a template to the file. Upon generation all Templates are added as 
     * Text to the file using $this->attributes to replace the placeholders
     * 
     * @param string $templatePath  Path to the text file used as template
     */
    public function addTemplate($templatePath) {
        $this->arrTemplates[] = $templatePath;
    }
    
    /**
     * Define how to mark the beginning of a placeholder in a template
     * Default is "{{$"
     * 
     * @param string $string
     */
    public function setPlaceholderStart($string) {
        $this->placeHolderStart = $string;
    }
    
    /**
     * Define how to mark the end of a placeholder in a template
     * Default is "}}"
     * 
     * @param string $string
     */
    public function setPlaceholderEnd($string) {
        $this->placeHolderEnd = $string;
    }
    
    /**
     * Adds the supplied array of attributes 
     * 
     * @param array $arrAttributes Use key => val
     */
    public function addAttributes($arrAttributes) {
        foreach ($arrAttributes as $key => $val) {
            $this->attributes[$key] = $val;
        }
    }
    
    /**
     * Generates the File content
     * 
     * @return string
     */
    public function generateCode() {
        $content = "";
        foreach ($this->arrTemplates as $template) {
            if (!file_exists($template)) {
                //Template not found
                continue;
            }
            $content .= file_get_contents($template);
        }
        
        foreach ($this->attributes as $key => $val) {
            $content = str_replace($this->placeHolderStart.$key.$this->placeHolderEnd, $val, $content);
        }
        return $content;
    }
    
    /**
     * Creates the file in the temp folder
     */
    public function generate() {
        if (!$this->filePath) {
            throw new Exception('Trying to save file without path');
        }
        
        $tempDir = codemonkey_pathTempDir;
        
        $content = $this->generateCode();
        
        $this->checkIfTempFolderExists();
        $this->checkIfFoldersExist();
        
        try {
            $fp = fopen($tempDir.$this->filePath, 'w');
            fputs($fp, $content);
            fclose($fp);
            chmod($tempDir.$this->filePath, 0775);
        } catch (Exception $e) {
            echo "Could not generate file ".$this->filePath."<br />\n"
                    . $e->getMessage()."<br />\n";
        }
    }
    
    /**
     * Create folders if not existing
     */
    protected function checkIfFoldersExist() {
        $arrFilePath = explode(DIRECTORY_SEPARATOR, $this->filePath);
        array_pop($arrFilePath); //Remove filename
        if (count($arrFilePath) > 0) {
            $folderPath = implode(DIRECTORY_SEPARATOR, $arrFilePath);
            $folder = new Folder();
            $folder->createFolderIfNotExists($folderPath);
        }
    }
    
    /**
     * Creates the Temp Dir if not exists
     * @return void
     */
    protected function checkIfTempFolderExists() {
        $dir = codemonkey_pathTempDir;
        if (file_exists($dir) && is_dir($dir)) {
            return;
        }
        mkdir($dir);
    }
    
}
