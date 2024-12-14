<?php

namespace Oxalistech\LiveBStack\Traits;

use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

trait WithBulkActions
{
    public $selected = [];
    public $selectAll = false;
    public $selectPage = false;

    public function updatedSelectPage($value)
    {
        $this->selected = $value
            ? $this->items->pluck('id')->map(fn($id) => (string) $id)->toArray()
            : [];
    }

    public function updatedSelected()
    {
        $this->selectAll = false;
        $this->selectPage = false;
    }

    public function selectAll()
    {
        $this->selectAll = true;
        $this->selected = $this->getFilteredItems()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    public function getSelectedItems()
    {
        return $this->getModelClass()::whereIn('id', $this->selected)->get();
    }

    public function executeBulkAction($name)
    {
        if (method_exists($this, 'bulk' . ucfirst($name))) {
            $this->{'bulk' . ucfirst($name)}();
        }

        $this->selected = [];
        $this->selectAll = false;
        $this->selectPage = false;
    }

    public function export($format)
    {
        $items = $this->selected
            ? $this->getSelectedItems()
            : $this->getFilteredItems();

        $filename = $this->getExportFilename($format);

        switch ($format) {
            case 'csv':
                return $this->exportToCsv($items, $filename);
            case 'excel':
                return $this->exportToExcel($items, $filename);
            case 'pdf':
                return $this->exportToPdf($items, $filename);
        }
    }

    protected function getExportFilename($format)
    {
        $modelName = class_basename($this->getModelClass());
        return strtolower($modelName) . '_' . now()->format('Y-m-d_His') . '.' . $format;
    }

    protected function exportToCsv($items, $filename)
    {
        $csv = Writer::createFromString('');

        // Add headers
        $headers = $this->getExportHeaders();
        $csv->insertOne($headers);

        // Add rows
        foreach ($items as $item) {
            $row = $this->getExportRow($item);
            $csv->insertOne($row);
        }

        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    protected function getExportHeaders()
    {
        return collect($this->table()->getSchema()['columns'])
            ->reject(fn($column) => in_array($column['type'], ['actions', 'image']))
            ->pluck('label')
            ->toArray();
    }

    protected function getExportRow($item)
    {
        return collect($this->table()->getSchema()['columns'])
            ->reject(fn($column) => in_array($column['type'], ['actions', 'image']))
            ->map(function($column) use ($item) {
                $value = data_get($item, $column['name']);
                if (isset($column['format'])) {
                    $formatted = $column['format']($value, $item);
                    return $formatted['title'] ?? $formatted;
                }
                return $value;
            })
            ->toArray();
    }

    protected function getFilteredItems()
    {
        return $this->getModelClass()::query()
            ->when($this->search, fn($query) =>
            $query->where(function($q) {
                foreach ($this->getSearchableColumns() as $column) {
                    $q->orWhere($column, 'like', "%{$this->search}%");
                }
            })
            )
            ->when($this->filters, function($query) {
                foreach ($this->filters as $filter => $value) {
                    if ($value) {
                        $query->where($filter, $value);
                    }
                }
            })
            ->get();
    }

    protected function getSearchableColumns()
    {
        return collect($this->table()->getSchema()['columns'])
            ->filter(fn($column) => $column['searchable'] ?? false)
            ->pluck('name')
            ->toArray();
    }
}
