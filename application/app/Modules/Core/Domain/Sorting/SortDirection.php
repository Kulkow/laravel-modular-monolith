<?php

namespace App\Modules\Core\Domain\Sorting;

enum SortDirection: string
{
    case Asc = 'asc';
    case Desc = 'desc';
}
