<?php
namespace App\Modules\Identity\Domain\Auth\ValueObjects;

enum LoginStatus: string
{
    case SUCCESS = 'success';
    case FAILURE = 'failure';
}
