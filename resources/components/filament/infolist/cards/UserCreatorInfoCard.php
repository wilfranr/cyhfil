<?php

namespace App\Filament\Components\Infolist\Cards;

use Filament\Components\Infolist\Card;
use Filament\Tables\Columns\TextColumn;

class UserCreatorInfoCard extends UserCreatorInfoCard
{
    protected function schema(): array
    {
        return [
            TextColumn::make('name')
                ->label('Nombre'),
            TextColumn::make('email')
                ->label('Correo electrónico'),
        ];
    }

    protected function getData($recordId): array
    {
        $user = $recordId->creator;

        return [
            'name' => $user->name,
            'email' => $user->email,
            'department' => $user->department,
        ];
    }
}
