<?php

namespace spec;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait HelperTrait
{
    /** @var ContainerInterface */
    protected static $_serviceContainer;

    protected $prophet;

    /**
     * @var ObjectProphecy[]
     */
    protected $collaborators = [];

    protected function getInstance(
        string $fqdn,
        array $data = []
    ) {
        $reflection = new \ReflectionClass($fqdn);

        $instance =  $reflection
            ->newInstanceWithoutConstructor();

        return $this->updateInstance(
            $instance,
            $data
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getService(string $fqdn)
    {
        self::loadServiceContainer();
        return self::$_serviceContainer->get($fqdn);
    }

    protected function setService(
        string $fqdn,
        $service
    ) {
        self::loadServiceContainer();
        self::$_serviceContainer->set(
            $fqdn,
            $service
        );
    }

    private static function loadServiceContainer()
    {
        if (!self::$_serviceContainer) {
            require_once(__DIR__ . '/testapp/AppKernel.php');
            $kernel = new \AppKernel('test', true);
            $kernel->boot();
            self::$_serviceContainer = $kernel->getContainer();
        }
    }

    protected function updateInstance($instance, array $data = [])
    {
        (function (array $data) {
            foreach ($data as $key => $value) {
                if (!property_exists($this, $key)) {
                    throw new \Exception(
                        "Attribute $key was not found on "
                        . get_class($this)
                    );
                }

                $this->$key = $value;
            }
        })->call($instance, $data);

        return $instance;
    }

    protected function getTestDouble(
        string $fqdn,
        $allowAnyCall = true
    ) {
        if (!$this->prophet) {
            $this->prophet = new \Prophecy\Prophet;
        }

        $collaborator = $this->prophet->prophesize(
            $fqdn
        );
        $this->collaborators[] = $collaborator;

        if (!$allowAnyCall) {
            return $collaborator;
        }

        $publicMethods = (new \ReflectionClass($fqdn))
                ->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($publicMethods as $publicMethod) {
            $methodName = $publicMethod->getName();
            if (strpos($methodName, '__') === 0) {
                continue;
            }

            $methodArguments = $publicMethod->getParameters();

            $arguments = [];
            foreach ($methodArguments as $item) {
                $arguments[] = Argument::any();
            }

            if (strpos($methodName, 'set') === 0) {
                $collaborator
                    ->{$methodName}(...$arguments)
                    ->willReturn(
                        $collaborator
                    );
            } else {
                $collaborator
                    ->{$methodName}(...$arguments)
                    ->will(function () {});
            }
        }

        return $collaborator;
    }

    public function letGo()
    {
        foreach ($this->collaborators as $collaborator) {
            $collaborator->checkProphecyMethodsPredictions();
        }
    }

    protected function getterProphecy($double, array $values, $shouldBeCalled = true)
    {
        foreach ($values as $method => $value) {
            if (is_callable($value)) {
                list($arguments, $returnValue) = $value();
                $prophecy = $double
                    ->{$method}(...$arguments)
                    ->willReturn($returnValue);

                if ($shouldBeCalled) {
                    $prophecy->shouldBeCalled();
                }

                continue;
            }

            $prophecy = $double
                ->{$method}()
                ->willReturn($value);

            if ($shouldBeCalled) {
                $prophecy->shouldBeCalled();
            }
        }

        return $double;
    }

    protected function setterProphecy($double, array $values, $shouldBeCalled = true)
    {
        foreach ($values as $method => $value) {
            if (is_callable($value)) {
                list($arguments, $returnValue) = $value();
                $prophecy = $double
                    ->{$method}(...$arguments)
                    ->willReturn($returnValue);
            } else {
                $prophecy = $double
                    ->{$method}($value)
                    ->willReturn(null);
            }

            if ($shouldBeCalled) {
                $prophecy->shouldBeCalled();
            }
        }
    }

    protected function fluentSetterProphecy($double, array $values, $shouldBeCalled = true)
    {
        foreach ($values as $method => $value) {
            if (!is_callable($value)) {
                $values[$method] = function () use ($double, $value) {
                    return [[$value], $double];
                };
            }
        }

        $this->setterProphecy($double, $values, $shouldBeCalled);
    }

    protected function resetProphecies($double, string $method)
    {
        $reflection = new \ReflectionClass($double);
        $property = $this->getProperty($reflection, 'methodProphecies');
        $property->setAccessible(true);
        $values = $property->getValue($double);
        unset($values[strtolower($method)]);
        $property->setValue($double, $values);
        $property->setAccessible(false);
    }

    protected function hydrate($object, $values)
    {
        $reflection = new \ReflectionClass($object);

        foreach ($values as $name => $value) {
            $property = $this->getProperty($reflection, $name);
            $property->setAccessible(true);
            $property->setValue($object, $value);
            $property->setAccessible(false);
        }
    }

    protected function getProperty(\ReflectionClass $reflection, $propertyName)
    {
        $propertyExists = $reflection->hasProperty($propertyName);

        if ($propertyExists) {
            return $reflection->getProperty($propertyName);
        }

        if ($reflection->getParentClass()) {
            return $this->getProperty(
                $reflection->getParentClass(),
                $propertyName
            );
        }

        throw new \Exception(
            'Property ' . $propertyName . ' does not exist'
        );
    }
}
