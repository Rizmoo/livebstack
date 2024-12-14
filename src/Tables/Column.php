<?php

namespace Oxalistech\LiveBStack\Tables;


class Column
{
    protected array $column = [];

    public static function make(string $type, string $name): self
    {
        return (new static())
            ->setType($type)
            ->setName($name);
    }

    protected function setType(string $type): self
    {
        $this->column = array_merge([
            'type' => $type,
            'sortable' => false,
            'searchable' => false,
            'alignment' => null,
            'width' => null,
            'format' => null,
            'states' => [],
            'actions' => [],
        ], $this->column);

        return $this;
    }

    protected function setName(string $name): self
    {
        $this->column['name'] = $name;
        return $this;
    }

    public function label(string $label): self
    {
        $this->column['label'] = $label;
        return $this;
    }

    public function sortable(bool $sortable = true): self
    {
        $this->column['sortable'] = $sortable;
        return $this;
    }

    public function searchable(bool $searchable = true): self
    {
        $this->column['searchable'] = $searchable;
        return $this;
    }

    public function format(callable $callback): self
    {
        $this->column['format'] = $callback;
        return $this;
    }

    public function alignment(string $alignment): self
    {
        $this->column['alignment'] = $alignment;
        return $this;
    }

    public function width(string $width): self
    {
        $this->column['width'] = $width;
        return $this;
    }

    public function relationship(string $relation, string $column): self
    {
        $this->column['relationship'] = [
            'name' => $relation,
            'column' => $column
        ];
        return $this;
    }

    public function badge(array $states): self
    {
        $this->column['states'] = $states;
        return $this;
    }

    public function action(string $name, array $options = []): self
    {
        $this->column['actions'][] = array_merge([
            'name' => $name
        ], $options);
        return $this;
    }

    public function when($condition): self
    {
        $this->column['condition'] = $condition;
        return $this;
    }

    public function toArray(): array
    {
        return $this->column;
    }
}