<?php

namespace App\Filament\Resources\MaquinasResource\RelationManagers;

use App\Filament\Resources\PedidosResource;
use App\Models\Pedido;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PedidosRelationManager extends RelationManager
{
    protected static string $relationship = 'pedidos';
    protected static ?string $title = 'Pedidos de esta Máquina';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Fecha de Creación')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Fecha de Actualización')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Vendedor')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado del Pedido')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Nuevo' => 'primary',
                        'En_Costeo' => 'gray',
                        'Cotizado' => 'info',
                        'Aprobado' => 'warning',
                        'Enviado' => 'success',
                        'Entregado' => 'success',
                        'Cancelado' => 'danger',
                        'Rechazado' => 'danger',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Nuevo' => 'heroicon-o-star',
                        'En_Costeo' => 'heroicon-c-list-bullet',
                        'Cotizado' => 'heroicon-o-currency-dollar',
                        'Aprobado' => 'ri-checkbox-line',
                        'Enviado' => 'heroicon-o-check-circle',
                        'Entregado' => 'heroicon-o-check-circle',
                        'Cancelado' => 'heroicon-o-x-circle',
                        'Rechazado' => 'heroicon-o-x-circle',
                    }),
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Ver')
                ->icon('heroicon-o-eye')
                ->url(fn(Pedido $record): string => PedidosResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}