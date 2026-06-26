<?php

namespace App\Filament\Resources\WholesaleSubmissionResource\Pages;

use App\Filament\Resources\WholesaleSubmissionResource;
use Filament\Resources\Pages\ListRecords;

class ListWholesaleSubmissions extends ListRecords
{
    protected static string $resource = WholesaleSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Read-only
        ];
    }
}
