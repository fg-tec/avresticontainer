<?php

namespace Avresticontainer;

use Avresticontainer\Exceptions\BindingResolutionException;
use Avresticontainer\Exceptions\NotFoundException;
use Avresticontainer\Factories\Factory;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

/**
 * Class Container
 * @package Avresticontainer
 */
class Container implements Interfaces\ContainerInterface
{
    /**
     * The container instance (singleton).
     *
     * @var Container|null
     */
    protected static ?Container $instance = null;

    /**
     * The array of bindings.
     *
     * @var array
     */
    public $bindings = [];

    /**
     * The array of singleton instances.
     *
     * @var array
     */
    public $instances = [];

    /**
     * The array of aliases.
     *
     * @var array
     */
    public $aliases = [];

    /**
     * Get the singleton instance of the container.
     *
     * @return Container
     */
    public static function getInstance(): Container
    {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * Prevent creating multiple instances.
     */
    private function __construct() {}

    /**
     * Prevent cloning the singleton instance.
     */
    private function __clone() {}

    /**
     * Bind an abstract type to a concrete type.
     *
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     */
    public function bind(string $abstract, $concrete = null): void
    {
        $this->bindings[$abstract] = new Factory($concrete ?? $abstract);
    }

    /**
     * Register a singleton in the container.
     *
     * @param string $abstract
     * @param mixed $concrete
     * @return void
     */
    public function singleton(string $abstract, $concrete = null): void
    {
        $this->instances[$abstract] = $this->resolve($abstract, new Factory($concrete ?? $abstract));
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @return mixed
     * @throws NotFoundException
     */
    public function make(string $abstract): mixed
    {
        // Check and resolve aliases
        if (isset($this->aliases[$abstract])) {
            $abstract = $this->aliases[$abstract];
        }

        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (!isset($this->bindings[$abstract])) {
            throw new NotFoundException("No binding found for {$abstract}");
        }

        return $this->resolve($abstract, $this->bindings[$abstract]);
    }

    /**
     * Resolve the given type with parameters.
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     * @throws NotFoundException
     */
    public function makeWith(string $abstract, array $parameters): mixed
    {
        // Check and resolve aliases
        if (isset($this->aliases[$abstract])) {
            $abstract = $this->aliases[$abstract];
        }

        if (!isset($this->bindings[$abstract])) {
            throw new NotFoundException("No binding found for {$abstract}");
        }

        return $this->resolve($abstract, $this->bindings[$abstract], $parameters);
    }

    /**
     * Register an alias for an abstract type.
     *
     * @param string $abstract
     * @param string $alias
     * @return void
     */
    public function alias(string $abstract, string $alias): void
    {
        $this->aliases[$alias] = $abstract;
    }

    /**
     * Resolve the given type using the factory.
     *
     * @param string $abstract
     * @param Factory $factory
     * @param array $parameters
     * @return mixed
     */
    protected function resolve(string $abstract, Factory $factory, array $parameters = []): mixed
    {
        return $factory->create($this, $parameters);
    }

    /**
     * Build an instance of the given type.
     *
     * @param string $concrete
     * @param array $parameters
     * @return mixed
     * @throws BindingResolutionException|NotFoundException
     */
    public function build(string $concrete, array $parameters = []): mixed
    {
        try {
            $reflector = new ReflectionClass($concrete);

            if (!$reflector->isInstantiable()) {
                throw new BindingResolutionException("Class {$concrete} is not instantiable.");
            }

            $constructor = $reflector->getConstructor();

            if (is_null($constructor)) {
                return new $concrete;
            }

            $dependencies = $this->resolveDependencies($constructor->getParameters(), $parameters);

            return $reflector->newInstanceArgs($dependencies);
        } catch (ReflectionException $e) {
            throw new BindingResolutionException("Class {$concrete} does not exist.");
        }
    }

    /**
     * Resolve dependencies for a given set of parameters.
     *
     * @param array $parameters
     * @param array $customParameters
     * @return array
     * @throws BindingResolutionException
     * @throws NotFoundException
     */
    protected function resolveDependencies(array $parameters, array $customParameters = []): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (isset($customParameters[$parameter->name])) {
                $dependencies[] = $customParameters[$parameter->name];
            } elseif ($type === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new BindingResolutionException("Cannot resolve class dependency {$parameter->name}");
                }
            } elseif ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = $this->make($type->getName());
            } else {
                throw new BindingResolutionException("Cannot resolve class dependency {$parameter->name}");
            }
        }

        return $dependencies;
    }

    /**
     * Add multiple definitions to the container.
     *
     * @param array $definitions
     * @return void
     */
    public function addDefinitions(array $definitions): void
    {
        foreach ($definitions as $key => $value) {
            if (is_callable($value)) {
                $this->bind($key, $value);
            } elseif (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $this->bind("{$key}.{$subKey}", $subValue);
                }
            } else {
                $this->bind($key, fn() => $value);
            }
        }
    }

    /**
     * Dynamically access services from the container.
     *
     * @param string $name
     * @return mixed
     * @throws NotFoundException
     */
    public function __get(string $name)
    {
        return $this->make($name);
    }
}