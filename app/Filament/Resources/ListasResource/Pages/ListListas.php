<?php

namespace App\Filament\Resources\ListasResource\Pages;

use App\Filament\Resources\ListasResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ListasImport;
use Filament\Notifications\Notification;

class ListListas extends ListRecords
{
    protected static string $resource = ListasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('FileUpload')
                ->label('Cargar Excel')
                ->modalHeading('Cargar archivo Excel')
                ->modalDescription('Seleccione un archivo Excel con los datos de la lista. Si lo requiere puede descargar la plantilla correspondiente.')
                ->modalSubmitActionLabel('Cargar')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    FileUpload::make('file')
                        ->label('Archivo Excel')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->disk('local') // Asegúrate de que está configurado el disco correctamente
                        ->required(),
                ])
                ->action(function ($data) {
                    $file = $data['file'] ?? null;

                    if ($file) {
                        // Obtener la ruta completa del archivo
                        $filePath = Storage::path($file);

                        // Procesar el archivo Excel
                        $this->importExcel($filePath);
                    }
                })
                ->modalContent(view('filament.custom.upload-file')),
        ];
    }

    /**
     * Método para procesar el archivo Excel y guardar los datos en la base de datos.
     */
    public function importExcel(string $filePath)
    {
        try {
            // Usar el importador personalizado para procesar los datos
            Excel::import(new ListasImport, $filePath);

            // Notificar al usuario que la importación fue exitosa
            Notification::make()
                ->title('Datos importados corretamente')
                ->icon('heroicon-o-document-text')
                ->iconColor('success')
                ->send();
        } catch (\Exception $e) {
            // Manejar errores y notificar al usuario
            // $this->notify('danger', 'Ocurrió un error al importar los datos: ' . $e->getMessage());
            Notification::make()
                ->title('Error al cargar los datos')
                ->body('Si lo requiere descargue la plantilla de excel y vuelva a intentarlo.')
                ->icon('heroicon-o-x-circle')
                ->iconColor('danger')
                ->actions([
                    NotificationAction::make('Descargar plantilla')
                        ->button()
                        ->url(route('downloadExcel'))
                        ->color('primary'),
                ])
                ->send();
        }
    }

    public function getTabs(): array
    {
        return [
            'Todos' => Tab::make(),
            'Marcas' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Marca');
            }),
            'Tipos de Máquina' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Tipo de Máquina');
            }),
            'Tipos de Artículo' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Tipo de Artículo');
            }),
            'Unidades Medida' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Unidad de Medida');
            }),
            'Tipos de Medida' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Tipo de Medida');
            }),
            'Nombres de Medida' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Nombre de Medida');
            }),
            'Piezas Estandar' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Pieza Estandar');
            }),
        ];
    }
}