<?php

namespace Oxalistech\LiveBStack\forms;

class Form
{
    protected array $schema = [];
    protected $currentField = null;

    public static function make(): self
    {
        return new static();
    }

    public function schema(array $fields): self
    {
        $this->schema = $fields;
        return $this;
    }

    public function getFields(): array
    {
        return array_map(function ($field) {
            return $field instanceof Field ? $field->toArray() : $field;
        }, $this->schema);
    }

    public static function text(string $name): Field
    {
        return Field::make('text', $name);
    }


    public static function input(string $name): Field
    {
        return Field::make('text', $name);
    }

    public static function textarea(string $name): Field
    {
        return Field::make('textarea', $name);
    }

    public static function select(string $name): Field
    {
        return Field::make('select', $name);
    }

    public static function multiSelect(string $name): Field
    {
        return Field::make('multiselect', $name);
    }

    public static function number(string $name): Field
    {
        return Field::make('number', $name);
    }

    public static function boolean(string $name): Field
    {
        return Field::make('checkbox', $name);
    }

    public static function date(string $name): Field
    {
        return Field::make('date', $name);
    }

    public static function file(string $name): Field
    {
        return Field::make('file', $name);
    }

    public static function image(string $name): Field
    {
        return Field::make('image', $name)
            ->acceptedFileTypes(['image/*']);
    }

    public static function radio(string $name): Field
    {
        return Field::make('radio', $name);
    }

    public static function hidden(string $name): Field
    {
        return Field::make('hidden', $name);
    }
    public static function checkbox(string $name): Field
    {
        return Field::make('checkbox', $name);
    }

    public static function toggle(string $name): Field
    {
        return Field::make('toggle', $name);
    }

    public function getSchema(): array
    {
        return array_map(function ($field) {
            return $field->toArray();
        }, $this->schema);
    }
}