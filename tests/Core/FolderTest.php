<?php

namespace Leedch\Codemonkey\Core;

use Leedch\Codemonkey\Core\Folder;
use PHPUnit\Framework\TestCase;

class FolderTest extends TestCase {

    public function testCreateFolderIfNotExists() 
    {
        $m = new Folder();
        $folderPath = "folder1/folder2/folder3";
        $m->createFolderIfNotExists($folderPath);
        $this->assertFileExists(codemonkey_pathTempDir . $folderPath);
        $this->assertTrue(is_dir(codemonkey_pathTempDir . $folderPath));
    }


}
