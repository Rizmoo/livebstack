<?php

namespace Oxalistech\LiveBStack\forms;

class Field
{

    protected array $field = [];

    public static function make(string $type, string $name): self
    {
        return (new static())
            ->setType($type)
            ->setName($name);
    }

    protected function setType(string $type): self
    {
        $this->field = array_merge([
            'type' => $type,
            'name' => '',
            'label' => '',
            'placeholder' => '',
            'rules' => [],
            'messages' => [],
            'columnClass' => 'col-12',
            'options' => [],
        ], $this->field);

        return $this;
    }

    protected function setName(string $name): self
    {
        $this->field['name'] = $name;
        return $this;
    }

    public function label(string $label): self
    {
        $this->field['label'] = $label;
        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->field['placeholder'] = $placeholder;
        return $this;
    }

    public function columnClass(string $class): self
    {
        $this->field['columnClass'] = $class;
        return $this;
    }

    public function relationship(string $relation, string $titleColumn = 'name'): self
    {
        $this->field['relationship'] = [
            'relation' => $relation,
            'titleColumn' => $titleColumn,
            'key' => $this->field['name'] // Store original field name
        ];
        return $this;
    }

    public function rules($rules): self
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        $this->field['rules'] = array_merge(
            $this->field['rules'] ?? [],
            (array) $rules
        );

        return $this;
    }

    public function required(): self
    {
        return $this->rules('required');
    }

    public function messages(array $messages): self
    {
        $this->field['messages'] = array_merge(
            $this->field['messages'] ?? [],
            $messages
        );
        return $this;
    }

    public function options(array $options): self
    {
        $this->field['options'] = collect($options)
            ->map(fn($label, $value) => [
                'value' => $value,
                'label' => $label
            ])
            ->values()
            ->toArray();

        return $this;
    }

    public function toArray(): array
    {
        $data = $this->field;

        // Convert rules array to string if exists
        if (!empty($data['rules'])) {
            $data['rules'] = implode('|', $data['rules']);
        }

        return $data;
    }



    public function default($value): self
    {
        $this->field['default'] = $value;
        return $this;
    }


    public function query(callable $callback): self
    {
        $this->field['options']['query'] = $callback;
        return $this;
    }


    public function prefix(string $prefix): self
    {
        $this->field['prefix'] = $prefix;
        return $this;
    }

    public function suffix(string $suffix): self
    {
        $this->field['suffix'] = $suffix;
        return $this;
    }

    public function step(float $step): self
    {
        $this->field['step'] = $step;
        return $this;
    }

    public function acceptedFileTypes(array $types): self
    {
        $this->field['acceptedFileTypes'] = $types;
        return $this;
    }

    public function maxSize(int $size): self
    {
        $this->rules("max:$size");
        return $this;
    }

    public function preview(): self
    {
        $this->field['preview'] = true;
        return $this;
    }

    public function multiple(): self
    {
        $this->field['multiple'] = true;
        return $this;
    }

}
