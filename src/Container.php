<?php

declare(strict_types=1);

namespace App;

use Closure;
use ReflectionClass;

class Container
{
    protected $services = [];
    protected $aliases = [];

    public function addService(
        string $name,
        Closure $closure,
        ?string $alias = null
    ): void {

        if ($alias) $this->addAlias($alias, $name);

        $this->services[$name] = $closure;
    }

    public function hasService(string $name): bool
    {
        return isset($this->services[$name]);
    }

    public function getService(string $name)
    {
        if (!$this->hasService($name)) return null;

        if ($this->services[$name] instanceof Closure)
            $this->services[$name] = $this->services[$name]();

        return $this->services[$name];
    }

    public function getServices(): array
    {
        return [
            "Services" => $this->services,
            "Aliases" => $this->aliases
        ];
    }

    public function addAlias(string $alias, string $service): void
    {
        $this->aliases[$alias] = $service;
    }

    public function hasAlias(string $alias): bool
    {
        return isset($this->aliases[$alias]);
    }

    public function getAlias(string $alias)
    {
        if (!$this->hasAlias($alias)) return null;

        return $this->getService($this->aliases[$alias]);
    }

    public function loadServices(string $namespace): void
    {
        $basePath = __DIR__ . DIRECTORY_SEPARATOR;
        $namespace = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
        $path = $basePath .
            substr($namespace, strpos($namespace, DIRECTORY_SEPARATOR) + 1) .
            DIRECTORY_SEPARATOR;

        $files = array_filter(scandir($path), function ($file) use ($path) {
            return is_file($path . $file);
        });

        foreach ($files as $file) {
            $class = new ReflectionClass($namespace . '\\' . basename($file, '.php'));
            $serviceName = $class->getName();
            $serviceArgs = $class->getConstructor()->getParameters();

            $dependencies = [];

            foreach ($serviceArgs as $argument) {
                $type = (string) $argument->getType();

                if ($this->hasService($type) || $this->hasAlias($type))
                    $dependencies[] = $this->getService($type) ?? $this->getAlias($type);
                else
                    $dependencies[] = function () use ($type) {
                        return $this->getService($type) ?? $this->getAlias($type);
                    };
            }

            $this->addService($serviceName, function () use ($serviceName, $dependencies) {
                foreach ($dependencies as &$dependency) {
                    if ($dependency instanceof Closure)
                        $dependency = $dependency();
                }

                return new $serviceName(...$dependencies);
            });
        }
    }
}
