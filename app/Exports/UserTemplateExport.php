<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UserTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new UserSheetExport(),
            new RoleSheetExport(),
        ];
    }
}
