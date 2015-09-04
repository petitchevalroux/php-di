<?php

namespace Di;

class Di extends \Singleton\Singleton
{
    protected function __construct()
    {
    }

    /**
     * Directory containing dependencies.
     *
     * @var string
     */
    private $dependencyDirectory;

    /**
     * Set directory containing dependencies.
     *
     * @param string $directory
     */
    public function setDependenciesDirectory($directory)
    {
        $this->dependencyDirectory = rtrim($directory, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }

    /**
     * Return directory containing dependencies.
     *
     * @return string
     *
     * @throws Exception
     */
    private function getDependenciesDirectory()
    {
        if (!isset($this->dependencyDirectory)) {
            throw new Exception('dependencyDirectory not setted');
        }

        return $this->dependencyDirectory;
    }

    /**
     * Return dependency path.
     *
     * @param string $name
     *
     * @return string
     */
    private function getDependencyPath($name)
    {
        return $this->getDependenciesDirectory().str_replace(['.', DIRECTORY_SEPARATOR], '', $name).'.php';
    }

    /**
     * Magic getter which set dependency if not already set.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (!isset($this->{$name})) {
            $this->{$name} = require $this->getDependencyPath($name);
        }

        return $this->{$name};
    }
}
