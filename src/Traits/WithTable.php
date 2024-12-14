<?php

namespace Oxalistech\LiveBStack\Traits;

trait WithTable
{
    public $sortField = '';
    public $sortDirection = 'asc';
    public $search = '';
    public $perPage = 5;

    public function getQueryString()
    {
        return array_merge(parent::getQueryString(), [
            'sortField' => ['except' => ''],
            'sortDirection' => ['except' => 'asc'],
            'search' => ['except' => ''],
            'perPage' => ['except' => 5],
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'sortField', 'sortDirection']);
        $this->resetPage();
    }

    public function getPerPageOptions(): array
    {
        return [5, 10, 25, 50, 100];
    }
}
