<?php

namespace Oxalistech\LiveBStack;

class LiveBStack
{
    public function form()
    {
        return new Forms\Form();
    }

    public function table()
    {
        return new Tables\Table();
    }

    public function field(string $type, string $name)
    {
        return Forms\Field::make($type, $name);
    }

    public function column(string $type, string $name)
    {
        return Tables\Column::make($type, $name);
    }

    // Helper method to get package version
    public function version()
    {
        return '1.0.0';
    }

    // Helper method to check if stats are enabled
    public function statsEnabled(): bool
    {
        return config('livebstack.stats.enabled', true);
    }

    // Helper method to get configured theme
    public function theme(): string
    {
        return config('livebstack.layout.theme', 'bootstrap');
    }

    // Helper method to get notification settings
    public function notificationSettings(): array
    {
        return config('livebstack.notifications', []);
    }

    // Helper method for table settings
    public function tableSettings(): array
    {
        return config('livebstack.table', []);
    }
}