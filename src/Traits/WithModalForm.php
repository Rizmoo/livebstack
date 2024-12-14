<?php

namespace Oxalistech\LiveBStack\Traits;

use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

trait WithModalForm
{
    use WithFileUploads;

    public $isOpen = false;
    public $model = null;
    public $formData = [];
    public $tempImages = [];
    public $relationships = [];
    public $rules = [];
    public $messages = [];
    public $fields = [];

    public function mount()
    {
        $this->initializeFormData();
    }

    protected function initializeFormData()
    {
        $this->initializeFields();
        $this->formData = $this->extractDefaultValues();
        $this->relationships = $this->extractRelationships();
    }

    protected function initializeFields()
    {
        if (method_exists($this, 'form')) {
            $form = $this->form();
            $this->fields = $form->getFields();
            $this->buildValidationRules();
        }
    }

    protected function extractDefaultValues(): array
    {
        return collect($this->fields)->mapWithKeys(function ($field) {
            $default = $field['default'] ?? $this->getDefaultValueForType($field['type']);
            return [$field['name'] => $default];
        })->toArray();
    }

    protected function getDefaultValueForType(string $type)
    {
        return match ($type) {
            'number' => 0,
            'checkbox', 'boolean', 'toggle' => false,
            'multiselect' => [],
            'radio' => array_key_first($this->fields['options']['values'] ?? []),
            default => null
        };
    }

    protected function extractRelationships(): array
    {
        return collect($this->fields)
            ->filter(fn($field) => isset($field['relationship']))
            ->mapWithKeys(function ($field) {
                $relationshipName = $field['relationship']['relation'];
                $isMultiple = in_array($field['type'], ['multiselect']);

                return [$relationshipName => [
                    'model' => $this->guessModelClass($relationshipName),
                    'type' => $isMultiple ? 'multiple' : 'single',
                    'label_field' => $field['relationship']['titleColumn'] ?? 'name',
                    'key' => $field['name']
                ]];
            })->toArray();
    }

    protected function guessModelClass(string $relationship): string
    {
        $modelClass = $this->getModelClass();
        $instance = new $modelClass;

        $relation = $instance->$relationship();
        return get_class($relation->getRelated());
    }

    public function getRelationshipOptions($relationName)
    {
        $modelClass = $this->getModelClass();
        $instance = new $modelClass;

        // Get the relationship
        $relation = $instance->$relationName();
        $relatedModel = $relation->getRelated();

        // Get query from model if exists
        if (method_exists($this, 'getRelationshipQuery')) {
            $query = $this->getRelationshipQuery($relationName);
            if (!$query) {
                $query = $relatedModel->query();
            }
        } else {
            $query = $relatedModel->query();
        }

        // Get the field configuration
        $field = collect($this->fields)
            ->first(function($field) use ($relationName) {
                return isset($field['relationship']) &&
                    $field['relationship']['relation'] === $relationName;
            });

        $titleColumn = $field['relationship']['titleColumn'] ?? 'name';

        // Get the results
        return $query->get()->map(function($item) use ($titleColumn) {
            return [
                'id' => $item->id,
                'label' => $item->$titleColumn
            ];
        });
    }

    protected function buildValidationRules()
    {
        foreach ($this->fields as $field) {
            if (isset($field['rules'])) {
                $this->rules["formData.{$field['name']}"] = $field['rules'];
            }
            if (isset($field['messages'])) {
                foreach ($field['messages'] as $rule => $message) {
                    $this->messages["formData.{$field['name']}.{$rule}"] = $message;
                }
            }
        }
    }

    public function openModal($id = null)
    {
        if ($id) {
            $this->model = $this->findModel($id);
            $this->formData = $this->mapModelToFormData($this->model);
        } else {
            $this->model = null;
            $this->formData = $this->extractDefaultValues();
            $this->tempImages = [];
        }

        $this->resetValidation();
        $this->dispatch('modal-opened');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->model = null;
        $this->formData = $this->extractDefaultValues();
        $this->tempImages = [];
        $this->resetValidation();
        $this->dispatch('modal-closed');
    }

    protected function findModel($id)
    {
        $modelClass = $this->getModelClass();
        return $modelClass::findOrFail($id);
    }

    protected function mapModelToFormData($model)
    {
        $data = $model->toArray();

        // Handle relationships
        foreach ($this->relationships as $relation => $config) {
            if ($model->$relation) {
                $data[$config['key']] = $this->mapRelationshipData($model->$relation, $config);
            }
        }

        return $data;
    }

    protected function mapRelationshipData($relation, $config)
    {
        if ($config['type'] === 'multiple') {
            return $relation->pluck('id')->toArray();
        }

        return $relation->id;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->model) {
                $this->update();
            } else {
                $this->create();
            }

            $this->closeModal();
            $this->dispatch('saved');
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Record saved successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error saving record: ' . $e->getMessage()
            ]);
        }
    }

    protected function create()
    {
        $modelClass = $this->getModelClass();
        $model = new $modelClass();
        $this->saveModel($model);
    }

    protected function update()
    {
        $this->saveModel($this->model);
    }

    protected function saveModel($model)
    {
        $data = $this->prepareDataForSave();

        $model->fill($data);
        $model->save();

        $this->saveRelationships($model);
        $this->saveImages($model);
    }

    protected function prepareDataForSave()
    {
        $data = [];

        foreach ($this->formData as $key => $value) {
            // Check if this field exists in relationships
            $relationship = collect($this->relationships)
                ->first(function($config) use ($key) {
                    return $config['key'] === $key;
                });

            if ($relationship) {
                // Use the relationship key
                $data[$key] = $value;
            } else {
                // Use the original key
                $data[$key] = $value;
            }
        }

        return collect($data)
            ->except(['created_at', 'updated_at'])
            ->toArray();
    }

    protected function saveRelationships($model)
    {
        foreach ($this->relationships as $relation => $config) {
            if (isset($this->formData[$config['key']])) {
                if ($config['type'] === 'multiple') {
                    $model->$relation()->sync($this->formData[$config['key']]);
                } else {
                    $model->$relation()->associate($this->formData[$config['key']]);
                    $model->save();
                }
            }
        }
    }

    protected function saveImages($model)
    {
        foreach ($this->tempImages as $field => $image) {
            if ($image) {
                $path = $image->store('uploads/' . Str::plural(Str::lower(class_basename($model))));
                $model->update([$field => $path]);
            }
        }
    }

    public function removeImage($field)
    {
        unset($this->tempImages[$field]);
        $this->formData[$field] = null;
    }

    public function delete($id)
    {
        $modelClass = $this->getModelClass();
        $item = $modelClass::findOrFail($id);
        $item->delete();
        $this->dispatch('deleted');
    }

    abstract protected function getModelClass();
    abstract protected function form();
}
