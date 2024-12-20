<?php

namespace App\Filament\Resources\ContractResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoginCredentialsRelationManager extends RelationManager
{
    protected static string $relationship = 'login_credentials';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1) // Ensures a single-column layout
                ->schema([
                    Section::make('General')->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Name'),
                        MarkdownEditor::make('description')
                            ->label('Description'),
                        TextInput::make('email')
                            ->email()
                            ->label('Email'),
                        TextInput::make('password')
                            ->password()
                            ->label('Password'),
                        FileUpload::make('attachments')
                            ->multiple()
                            ->label('Attachments')
                            ->directory('login_credentials_attachments')
                            ->visibility('public')
                            ->preserveFilenames(),
                    ])->collapsible(),
                    Section::make('Contract')
                        ->schema([
                            Select::make('contracts')
                                ->multiple()
                                ->preload()
                                ->relationship('contracts', 'name')
                                ->searchable(),
                        ])
                        ->collapsible()
                        ->collapsed(false),

                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }


    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
