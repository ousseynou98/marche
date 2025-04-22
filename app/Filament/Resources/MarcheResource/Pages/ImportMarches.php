<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MarcheImport;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;
use Illuminate\Support\Facades\Validator;



class ImportMarches extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-upload';
    protected static string $view = 'filament.pages.import-marches';

    public $file;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('file')
                ->label('Sélectionner un fichier')
                ->disk('local')
                ->directory('imports')
                ->required()
                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv']),
        ];
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        $filePath = Storage::disk('local')->path($this->file);
        Excel::import(new MarcheImport, $filePath);

        session()->flash('success', 'Importation réussie !');
        return redirect()->route('filament.admin.pages.import-marches');
    }
}
