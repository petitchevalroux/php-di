<?php

use Faker\Factory;
use Di\Di;

class SingletonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Factory
     */
    private $faker;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Factory::create();
    }

    public function testDependencyException()
    {
        $this->setExpectedException('\Di\Exception');
        $di = Di::getInstance();
        $di->foo;
    }

    public function testDependency()
    {
        $expectedDependency = $this->faker->text();
        $di = Di::getInstance();
        $dependencyDirectory = sys_get_temp_dir();
        $di->setDependenciesDirectory($dependencyDirectory);
        $tempname = tempnam($dependencyDirectory, '');
        $dependency = basename($tempname);
        $phpFile = $tempname.'.php';
        if (is_file($phpFile)) {
            unlink($phpFile);
        }
        file_put_contents($phpFile, "<?php\nreturn '"
                .addslashes($expectedDependency)
                ."';\n");
        $this->assertEquals($expectedDependency, $di->{$dependency});
        unlink($phpFile);
        unlink($tempname);
    }
}
