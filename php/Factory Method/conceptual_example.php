<?php
# Source: Refactoring Guru

namespace RefactoringGuru\FactoryMethod\Conceptual;

abstract class Creator
{
    abstract public function factoryMethod(): Product;

    public function someOperation(): string
    {
        $product = $this->factoryMethod();
        $result = "Creator: The same creator's code has just worked with " . 
        $product->operation();
        return $result;
    }
}

class ConcreteCreator1 extends Creator
{
    public function factoryMethod(): Product
    {
        return new ConcreteProduct1();
    }
}

class ConcreteCreator2 extends Creator
{
    public function factoryMethod(): Product
    {
        return new ConcreteProduct2();
    }
}

interface Product
{
    public function operation(): string;
}

class ConcreteProduct1 implements Product
{
    public function operation(): string
    {
        return "{Result of the ConcreteProduct1}";
    }
}

class ConcreteProduct2 implements Product
{
    public function operation(): string
    {
        return "{Result of the ConcreteProduct2}";
    }
}

function clientCode(Creator $creator)
{
    echo "Client: I'm not aware of the creator's class, but it still works.\n" .
    $creator->someOperation();
}

echo "App: Launched with the ConcreteCreator1.\n";
clientCode(new ConcreteCreator1());
echo "\n\n";
echo "App: Launched with the ConcreteCreator2.\n";
clientCode(new ConcreteCreator2());

/**
 * Output:
 *App: Launched with the ConcreteCreator1.
 *Client: I'm not aware of the creator's class, but it still works.
 *Creator: The same creator's code has just worked with {Result of the ConcreteProduct1}
 *
 *App: Launched with the ConcreteCreator2.
 *Client: I'm not aware of the creator's class, but it still works.
 *Creator: The same creator's code has just worked with {Result of the ConcreteProduct2}
 */
?>