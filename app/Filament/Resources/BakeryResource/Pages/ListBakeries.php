<?php

namespace App\Filament\Resources\BakeryResource\Pages;

use App\Filament\Resources\BakeryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBakeries extends ListRecords
{
    protected static string $resource = BakeryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
