<?php
# Source: Refactoring Guru

namespace RefactoringGuru\Builder\Conceptual;

interface Builder
{
  public function producePartA(): void;
  public function producePartB(): void;
  public function producePartC(): void;
}

class ConcreteBuilder1 implements Builder
{
  private $product;

  public function __construct()
  {
    $this->reset();
  }

  public function reset(): void
  {
    $this->product = new Product1();
  }

  public function producePartA(): void
  {
    $this->product->parts[] = "PartA1";
  }

  public function producePartB(): void
  {
    $this->product->parts[] = "PartB1";
  }

  public function producePartC(): void
  {
    $this->product->parts[] = "PartC1";
  }

  public function getProduct(): Product1
  {
    $result = $this->product;
    $this->reset();

    return $result;
  }
}

class Product1
{
  public $parts = [];
  
  public function listParts(): void
  {
    echo "Product parts: " . implode(', ', $this->parts) . "\n\n";
  }
}

class Director
{
  private $builder;

  public function setBuilder(Builder $builder): void
  {
    $this->builder = $builder;
  }

  public function buildMinimalViableProduct(): void
  {
    $this->builder->producePartA();
  }

  public function buildFullFeaturedProduct(): void
  {
    $this->builder->producePartA();
    $this->builder->producePartB();
    $this->builder->producePartC();
  }
}

function clientCode(Director $director)
{
  $builder = new ConcreteBuilder1();
  $director->setBuilder($builder);

  echo "Standard basic product:\n";
  $director->buildMinimalViableProduct();
  $builder->getProduct()->listParts();

  echo "Standard full featured product:\n";
  $director->buildFullFeaturedProduct();
  $builder->getProduct()->listParts();

  echo "Custom product:\n";
  $builder->producePartA();
  $builder->producePartC();
  $builder->getProduct()->listParts();
}

$director = new Director();
clientcode($director);

/**
 * Output:
 *Standard basic product:
 *Product parts: PartA1
 *
 *Standard full featured product:
 *Product parts: PartA1, PartB1, PartC1
 *
 *Custom product:
 *Product parts: PartA1, PartC1
 */
?>