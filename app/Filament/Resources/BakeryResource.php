<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BakeryResource\Pages;
use App\Filament\Resources\BakeryResource\RelationManagers;
use App\Models\Bakery;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BakeryResource extends Resource
{
    protected static ?string $model = Bakery::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Panaderías';

    protected static ?string $modelLabel = 'Panadería';

    protected static ?string $pluralModelLabel = 'Panaderías';

    public static function canViewAny(): bool
    {
        // Si el usuario es root, puede ver todas las panaderías
        return auth()->user()->hasRole('root');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Si el usuario tiene rol 'admin', solo puede ver las panaderías que le corresponden
        if (auth()->user()->hasRole('admin')) {
            return $query->where('user_id', auth()->id()); // Filtro para ver solo panaderías del admin autenticado
        }

        // Si no tiene rol o tiene otro rol, solo puede ver las panaderías que le corresponden
        if (auth()->user()->roles->isEmpty()) {
            return $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->label('Nombre'),
            Forms\Components\TextInput::make('email')->email()->required()->label('Correo electrónico'),
            Forms\Components\TextInput::make('phone')->required()->label('Teléfono'),
            Forms\Components\TextArea::make('address')->label('Dirección'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable()->sortable()->label('Nombre'),
            Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('phone')->searchable()->label('Teléfono'),
            Tables\Columns\TextColumn::make('address')->searchable()->label('Dirección'),
        ])->filters([]) // Puedes agregar filtros aquí
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        $user = Auth::user();

        Log::debug($user);

        // Si el usuario no está autenticado, o no tiene rol asignado, no se muestra nada
        if (!$user) {
            return [];
        }

        // Lógica de acceso a páginas según el rol
        if ($user->roles->isEmpty()) {
            // Sin rol: permitir crear y editar panaderías
            return [
                'index' => Pages\ListBakeries::route('/'),
                'create' => Pages\CreateBakery::route('/create'),
                'edit' => Pages\EditBakery::route('/{record}/edit'),
            ];
        }

        if ($user->hasRole('root')) {
            // Rol 'root': puede ver todas las panaderías
            return [
                'index' => Pages\ListBakeries::route('/'),
                'create' => Pages\CreateBakery::route('/create'),
                'edit' => Pages\EditBakery::route('/{record}/edit'),
            ];
        }

        if ($user->hasRole('admin')) {
            // Rol 'admin': solo puede ver las panaderías que le pertenecen
            return [
                'index' => Pages\ListBakeries::route('/'),
            ];
        }

        return [];
    }
}
