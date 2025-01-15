<?php

namespace App\Filament\Resources\BakeryResource\Pages;

use App\Filament\Resources\BakeryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBakery extends EditRecord
{
    protected static string $resource = BakeryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
