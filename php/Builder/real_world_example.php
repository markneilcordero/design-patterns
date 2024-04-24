<?php
# Source: Refactoring Guru

namespace RefactoringGuru\Builder\RealWorld;

interface SQLQueryBuilder
{
  public function select(string $table, array $fields): SQLQueryBuilder;
  public function where(string $field, string $value, string $operator = '=');
  public function limit(int $start, int $offset): SQLQueryBuilder;
  public function getSQL(): string;
}

class MysqlQueryBuilder implements SQLQueryBuilder
{
  protected $query;

  protected function reset(): void
  {
    $this->query = new \stdClass();
  }

  public function select(string $table, array $fields): SQLQueryBuilder
  {
    $this->reset();
    $this->query->base = "SELECT " . implode(", ", $fields) . " FROM " . $table;
    $this->query->type = 'select';

    return $this;
  }

  public function where(string $field, string $value, string $operator = '='): SQLQueryBuilder
  {
    if (!in_array($this->query->type, ['select', 'update', 'delete']))
    {
      throw new \Exception("WHERE can only be added to SELECT, UPDATE OR DELETE");
    }
    $this->query->where[] = "$field $operator '$value'";
    return $this;
  }

  public function limit(int $start, int $offset): SQLQueryBuilder
  {
    if (!in_array($this->query->type, ['select']))
    {
      throw new \Exception("LIMIT can only be added to SELECT");
    }
    $this->query->limit = " LIMIT " . $start . ", " . $offset;
    return $this;
  }

  public function getSQL(): string
  {
    $query = $this->query;
    $sql = $query->base;
    if (!empty($query->where))
    {
      $sql .= " WHERE " . implode(' AND ', $query->where);
    }
    if (isset($query->limit))
    {
      $sql .= $query->limit;
    }
    $sql .= ";";
    return $sql;
  }
}

class PostgresQueryBuilder extends MysqlQueryBuilder
{
  public function limit(int $start, int $offset): SQLQueryBuilder
  {
    parent::limit($start, $offset);
    $this->query->limit = " LIMIT " . $start . " OFFSET " . $offset;
    return $this;
  }
}

function clientCode(SQLQueryBuilder $queryBuilder)
{
  $query = $queryBuilder
    ->select("users", ["name", "email", "password"])
    ->where("age", 18, ">")
    ->where("age", 30, "<")
    ->limit(10, 20)
    ->getSQL();
  echo $query;
}

echo "Testing MySQL query builder:\n";
clientCode(new MysqlQueryBuilder());

echo "\n\n";

echo "Testing PostgresSQL query builder:\n";
clientCode(new PostgresQueryBuilder());

/**
 * Output: 
 *Testing MySQL query builder:
 *SELECT name, email, password FROM users WHERE age > '18' AND age < '30' LIMIT 10, 20;
 *
 *Testing PostgresSQL query builder:
 *SELECT name, email, password FROM users WHERE age > '18' AND age < '30' LIMIT 10 OFFSET 20;
 */
?>