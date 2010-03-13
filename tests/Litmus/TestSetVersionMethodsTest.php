<?php
/**
 *
 *
 * @author Guillaume <guillaume@geelweb.org>
 * @copyright Copyright (c) 2010, Guillaume Luchet
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * Include test helper
 */
require_once dirname(__FILE__) . '/../TestHelper.php';

/**
 * Unit test class for Litmus Test Set Version Methods
 *
 */
class Litmus_TestSetVersionTest extends PHPUnit_Framework_TestCase
{
    public function testVersionsMethod()
    {
        $test = Litmus::getTests(1); 
        $versions = $test->getVersions();
        $this->assertTrue(is_array($versions));
        $this->assertTrue($versions[0] instanceof Litmus_Version);
    }

    public function testVersionsShowMethod()
    {
    }

    public function testVersionsCreateMethod()
    {
    }

    public function testVersionsPollMethod()
    {
    }
}
 
