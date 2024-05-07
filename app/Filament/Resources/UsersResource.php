<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsersResource\Pages;
use App\Filament\Resources\UsersResource\RelationManagers;
use App\Models\User;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Filament\Resources\UsersResource\Pages\ListUsers;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UsersResource\CreateUser; // Import the missing class
use Filament\Resources\Pages\CreateRecord;
Use Filament\Resources\Pages\Page;



class UsersResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Usuarios';
    protected static ?string $navigationGroup = 'Sistema';
    protected static ?int $navigationSort = 0;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->placeholder('Ingrese el nombre del usuario'),
                TextInput::make('email')
                    ->label('Correo')
                    ->required()
                    ->placeholder('Ingrese el correo del usuario'),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->placeholder('Ingrese la contraseña del usuario')
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->required(fn (Page $livewire) => ($livewire instanceof CreateRecord)),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Rol')
                    ->required()
                    ->preload(),
                    
                    

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d-M-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d-M-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Rol')
                    ->searchable()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUsers::route('/create'),
            'edit' => Pages\EditUsers::route('/{record}/edit'),
        ];
    }
}
