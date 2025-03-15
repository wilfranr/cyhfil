<?php

namespace App\Filament\Resources\ArticulosResource\RelationManagers;

use App\Models\Lista;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\View\TablesRenderHook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Forms\Components\DisplayImage;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Blade;

class MedidasRelationManager extends RelationManager
{
    protected static string $relationship = 'medidas';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('articulo');
    }



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Mostrar la imagen del artículo
                Group::make()
                    ->schema([
                        View::make('articulos.medida-imagen'),
                        FileUpload::make('imagen')
                            ->image()
                            ->directory('medidas'),
                    ]),
                Group::make()
                    ->schema([
                        // Otros campos del formulario
                        Forms\Components\TextInput::make('identificador')
                            ->required()
                            ->maxLength(255),

                        Select::make('nombre')
                            ->options(Lista::where('tipo', 'NOMBRE DE MEDIDA')->pluck('nombre', 'nombre'))
                            ->searchable(),

                        Select::make('unidad')
                            ->options(Lista::where('tipo', 'Unidad de medida')->pluck('nombre', 'nombre'))
                            ->searchable(),

                        Forms\Components\TextInput::make('valor'),

                        Select::make('tipo')
                            ->options(Lista::where('tipo', 'Tipo de medida')->pluck('nombre', 'nombre'))
                            ->searchable(),

                        
                    ]),

                // View::make('articulos.medida-imagen'),


            ])
            ->columns(2); // Divide el formulario en dos columnas
    }









    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('identificador')
            ->columns([
                Tables\Columns\TextColumn::make('identificador')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('nombre')->sortable(),
                Tables\Columns\TextColumn::make('unidad')->sortable(),
                Tables\Columns\TextColumn::make('valor')->sortable(),
                Tables\Columns\TextColumn::make('tipo')->sortable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->modal('form')->slideOver()->stickyModalFooter(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('')->modal('form')->slideOver(),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}