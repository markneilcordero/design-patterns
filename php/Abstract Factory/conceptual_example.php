<?php
# Source: Refactoring Guru

namespace RefactoringGuru\AbstractFactory\Conceptual;

interface AbstractFactory
{
  public function createProductA(): AbstractProductA;
  public function createProductB(): AbstractProductB;
}

class ConcreteFactory1 implements AbstractFactory
{
  public function createProductA(): AbstractProductA
  {
    return new ConcreteProductA1();
  }

  public function createProductB(): AbstractProductB
  {
    return new ConcreteProductB1();
  }
}

class ConcreteFactory2 implements AbstractFactory
{
  public function createProductA(): AbstractProductA
  {
    return new ConcreteProductA2();
  }

  public function createProductB(): AbstractProductB
  {
    return new ConcreteProductB2();
  }
}

interface AbstractProductA
{
  public function usefulFunctionA(): string;
}

class ConcreteProductA1 implements AbstractProductA
{
  public function usefulFunctionA(): string
  {
    return "The result of the product A1.";
  }
}

class ConcreteProductA2 implements AbstractProductA
{
  public function usefulFunctionA(): string
  {
    return "The result of the product A2.";
  }
}

interface AbstractProductB
{
  public function usefulFunctionB(): string;
  public function anotherUsefulFunctionB(AbstractProductA $collaborator): string; 
}

class ConcreteProductB1 implements AbstractProductB
{
  public function usefulFunctionB(): string
  {
    return "The result of the product B1.";
  }

  public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
  {
    $result = $collaborator->usefulFunctionA();
    return "The result of the B1 collaborating with the ({$result})";
  }
}

class ConcreteProductB2 implements AbstractProductB
{
  public function usefulFunctionB(): string
  {
    return "The result of the product B2.";
  }

  public function anotherUsefulFunctionB(AbstractProductA $collaborator): string
  {
    $result = $collaborator->usefulFunctionA();
    return "The result of the B2 collaborating with the ({$result})";
  }
}

function clientCode(AbstractFactory $factory)
{
  $productA = $factory->createProductA();
  $productB = $factory->createProductB();

  echo $productB->usefulFunctionB() . "\n";
  echo $productB->anotherUsefulFunctionB($productA) . "\n";
}

echo "Client: Testing client code with the first factory type:\n";
clientCode(new ConcreteFactory1());

echo "\n";

echo "Client: Testing the same client code with the second factory type:\n";
clientCode(new ConcreteFactory2());

/**
 * Output:
 * Client: Testing client code with the first factory type:
 *The result of the product B1.
 *The result of the B1 collaborating with the (The result of the product A1.)
 *
 *Client: Testing the same client code with the second factory type:
 *The result of the product B2.
 *The result of the B2 collaborating with the (The result of the product A2.)
 */
?>