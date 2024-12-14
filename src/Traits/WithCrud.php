<?php

namespace Oxalistech\LiveBStack\Traits;

use Livewire\WithPagination;

trait WithCrud
{
    use WithModalForm, WithTable, WithBulkActions, WithPagination;


    protected array $customSections = [];

    public function render()
    {
        return view('livewire.crud-layout', [
            'items' => $this->getItems(),
            'title' => $this->getTitle(),
            'icon' => $this->getIcon(),
            'createButtonText' => $this->getCreateButtonText(),
            'stats_cards' => $this->hasStats() ? $this->getStatsCards() : [],
            'customSections' => $this->getCustomSections(),
        ]);
    }
    protected function addCustomSection(string $component, string $location = 'before-table', array $params = []): void
    {
        $this->customSections[$location][] = [
            'component' => $component,
            'params' => $params
        ];
    }

    protected function getCustomSections(): array
    {
        return $this->customSections;
    }

    protected function getTitle(): string
    {
        return class_basename($this->getModelClass());
    }

    protected function getIcon(): string
    {
        return 'building';
    }

    protected function getCreateButtonText(): string
    {
        return 'Add ' . class_basename($this->getModelClass());
    }

    protected function hasStats(): bool
    {
        return method_exists($this, 'getStats');
    }

    protected function getStatsCards(): array
    {
        if (!$this->hasStats()) {
            return [];
        }

        $stats = $this->getStats();
        $cards = [];

        foreach ($stats as $key => $value) {
            $cards[] = [
                'title' => ucfirst($key),
                'value' => $value,
                'icon' => $this->getStatIcon($key),
                'color' => $this->getStatColor($key)
            ];
        }

        return $cards;
    }

    protected function getStatIcon(string $key): string
    {
        return match ($key) {
            'total' => $this->getIcon(),
            'active' => 'check-circle',
            'inactive' => 'x-circle',
            'pending' => 'clock',
            default => 'info-circle'
        };
    }

    protected function getStatColor(string $key): string
    {
        return match ($key) {
            'total' => 'primary',
            'active' => 'success',
            'inactive' => 'danger',
            'pending' => 'warning',
            default => 'info'
        };
    }

    protected function getItems()
    {
        return $this->getModelClass()::query()
            ->when($this->search, function($query) {
                $searchableColumns = collect($this->table()->getSchema()['columns'])
                    ->filter(fn($column) => $column['searchable'] ?? false)
                    ->pluck('name')
                    ->toArray();

                return $query->where(function($q) use ($searchableColumns) {
                    foreach ($searchableColumns as $column) {
                        $q->orWhere($column, 'like', "%{$this->search}%");
                    }
                });
            })
            ->when($this->sortField, fn($query) =>
            $query->orderBy($this->sortField, $this->sortDirection)
            )
            ->paginate($this->perPage);
    }

    abstract protected function getModelClass();
    abstract protected function form();
    abstract protected function table();
}
