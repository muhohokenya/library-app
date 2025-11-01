<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // REMOVED member_id field - it's auto-generated now

                TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->email()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),

                Select::make('member_type')
                    ->options([
                        'student' => 'Student',
                        'teacher' => 'Teacher',
                        'staff' => 'Staff',
                        'public' => 'Public',
                    ])
                    ->required()
                    ->default('student'),
            ]);
    }
}
