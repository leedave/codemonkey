<?php

namespace Leedch\Codemonkey\Core;

use Leedch\Codemonkey\Core\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase {

    public function testSetFilePath() 
    {
        $m = new File();
        $filePath = "testdir/testdir/file.json";
        $m->setFilePath($filePath);
        $this->assertAttributeEquals($filePath, 'filePath', $m);
    }

    public function testAddTemplate() 
    {
        $m = new File();
        $templatePath = "folder1/folder2/template1.txt";
        $m->addTemplate($templatePath);
        $this->assertAttributeEquals([$templatePath], 'arrTemplates', $m);
        $templatePath2 = "folder1/folder2/template2.txt";
        $m->addTemplate($templatePath2);
        $this->assertAttributeEquals([$templatePath, $templatePath2], 'arrTemplates', $m);
    }

    public function testSetPlaceholderStart() 
    {
        $m = new File();
        $string = "[[$";
        $m->setPlaceholderStart($string);
        $this->assertAttributeEquals($string, 'placeHolderStart', $m);
    }

    public function testSetPlaceholderEnd() 
    {
        $m = new File();
        $string = "]]";
        $m->setPlaceholderEnd($string);
        $this->assertAttributeEquals($string, 'placeHolderEnd', $m);
    }

    public function testAddAttributes() 
    {
        $m = new File();
        $arrAttributes = [
            "first1" => "First Field",
            "second1" => "Second Field"
        ];
        $m->addAttributes($arrAttributes);
        $this->assertAttributeEquals($arrAttributes, 'attributes', $m);
    }

    public function testGenerateCode() 
    {
        $m = new File();
        $m->addTemplate(__DIR__.'/../../templates/testcase.php.txt');
        $arrAttributes = [
            "testClassName" => "testcase",
            "namespace" => "namespace Testclasses\\Testcases;",
            "testClassAttributes" => 'protected $home = "home";'."\n".'protected $isActive = true;'."\n",
            "testClassMethods" => "public function __construct() \n{\n}\n"
        ];
        $m->addAttributes($arrAttributes);
        $response = $m->generateCode();
        $expected = '<?php'
                    ."\n\n"
                    .'namespace Testclasses\Testcases;'
                    ."\n\n"
                    .'class testcase {'."\n"
                    .'protected $home = "home";'."\n"
                    .'protected $isActive = true;'."\n"
                    ."\n"
                    .'public function __construct() '."\n"
                    ."{\n"
                    ."}\n"
                    ."\n"
                    ."}\n"
                    ."\n";
        $this->assertEquals($expected, $response);
    }

    public function testGenerate() 
    {
        $m = new File();
        $filename = 'test/testcase.php';
        $m->setFilePath($filename);
        $m->addTemplate(__DIR__.'/../../templates/testcase.php.txt');
        $arrAttributes = [
            "testClassName" => "testcase",
            "namespace" => "namespace Testclasses\\Testcases;",
            "testClassAttributes" => 'protected $home = "home";'."\n".'protected $isActive = true;'."\n",
            "testClassMethods" => "public function __construct() \n{\n}\n"
        ];
        $m->addAttributes($arrAttributes);
        $m->generate();
        $this->assertFileExists(codemonkey_pathTempDir . $filename);
        $this->assertGreaterThan(0, filesize(codemonkey_pathTempDir . $filename));
    }

}
