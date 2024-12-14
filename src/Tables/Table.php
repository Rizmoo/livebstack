<?php

namespace Oxalistech\LiveBStack\Tables;

class Table
{
    protected array $columns = [];
    protected bool $searchable = false;
    protected array $filters = [];
    protected array $actions = [];
    protected array $bulkActions = [];
    protected array $exports = [];
    protected int $perPage = 10;
    protected $defaultSort = null;
    protected bool $bulkActionsEnabled = false;
    protected bool $exportsEnabled = false;

    public static function make(): self
    {
        return new static();
    }

    // Column Types
    public static function text(string $name): Column
    {
        return Column::make('text', $name);
    }

    public static function image(string $name): Column
    {
        return Column::make('image', $name);
    }

    public static function badge(string $name): Column
    {
        return Column::make('badge', $name);
    }

    public static function boolean(string $name): Column
    {
        return Column::make('boolean', $name);
    }

    public static function number(string $name): Column
    {
        return Column::make('number', $name);
    }

    public static function date(string $name): Column
    {
        return Column::make('date', $name);
    }

    public static function relationship(string $name): Column
    {
        return Column::make('relationship', $name);
    }

    public static function actions(): Column
    {
        return Column::make('actions', 'actions')
            ->label('Actions')
            ->alignment('end');
    }

    // Table Configuration
    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function searchable(bool $searchable = true): self
    {
        $this->searchable = $searchable;
        return $this;
    }

    public function addFilter(string $name, string $label, array|callable $options): self
    {
        $this->filters[$name] = [
            'label' => $label,
            'options' => $options
        ];
        return $this;
    }

    public function perPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    public function defaultSort(string $column, string $direction = 'asc'): self
    {
        $this->defaultSort = [
            'column' => $column,
            'direction' => $direction
        ];
        return $this;
    }

    // Bulk Actions
    public function bulkActions(array $actions): self
    {
        $this->bulkActionsEnabled = true;
        $this->bulkActions = $actions;
        return $this;
    }

    public static function bulkAction(string $name, string $label, string $icon = null): array
    {
        return [
            'name' => $name,
            'label' => $label,
            'icon' => $icon,
        ];
    }

    // Exports
    public function withExports(array $exports): self
    {
        $this->exportsEnabled = true;
        $this->exports = $exports;
        return $this;
    }

    public static function export(string $format, string $label, string $icon = null): array
    {
        return [
            'format' => $format,
            'label' => $label,
            'icon' => $icon,
        ];
    }

    // Schema
    public function getSchema(): array
    {
        return [
            'columns' => array_map(fn($column) => $column->toArray(), $this->columns),
            'searchable' => $this->searchable,
            'filters' => $this->filters,
            'actions' => $this->actions,
            'bulkActionsEnabled' => $this->bulkActionsEnabled,
            'bulkActions' => $this->bulkActions,
            'exportsEnabled' => $this->exportsEnabled,
            'exports' => $this->exports,
            'perPage' => $this->perPage,
            'defaultSort' => $this->defaultSort,
        ];
    }
}