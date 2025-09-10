<?php
// app/Utils/StatusHelper.php

namespace App\Utils;

class StatusHelper
{
    public function getStatusText(string $status): string
    {
        switch ($status) {
            case 'pending':
                return 'Menunggu';
            case 'diterima':
                return 'Diterima';
            case 'ditolak':
                return 'Ditolak';
            case 'lulus':
                return 'Lulus';
            case 'dropout':
                return 'Dropout';
            default:
                return 'Tidak Diketahui';
        }
    }

    public function getStatusColor(string $status): string
    {
        switch ($status) {
            case 'pending':
                return 'yellow';
            case 'diterima':
                return 'green';
            case 'ditolak':
                return 'red';
            case 'lulus':
                return 'blue';
            case 'dropout':
                return 'gray';
            default:
                return 'gray';
        }
    }

    public function getValidStatuses(): array
    {
        return ['pending', 'diterima', 'ditolak', 'lulus', 'dropout'];
    }

    public function isValidStatus(string $status): bool
    {
        return in_array($status, $this->getValidStatuses());
    }
}
