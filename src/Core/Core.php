<?php

namespace Leedch\Codemonkey\Core;

use Exception;

/**
 * Basics used for Project classes
 *
 * @author leed
 */
class Core {
    
    public function __construct() {
        $this->checkIfConfigsAreSet();
    }
    
    protected function checkIfConfigsAreSet() {
        $arrConstants = [
            'codemonkey_constants',
            'codemonkey_constants', 
            'codemonkey_pathRoot',
            'codemonkey_pathTempDir',
            'codemonkey_pathTemplateDir',
        ];
        foreach ($arrConstants as $config) {
            if (!defined($config)) {
                throw new Exception('Required Codemonkey configs missing, get an example @ vendor/leedch/configs/constants.php');
            }
        }
    }
}
