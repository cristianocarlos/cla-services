<?php

namespace App\Enums;

enum YiiEnum: int
{
    case STATUS_OK = 2;
    case STORAGE_DELIVERY_PUBLIC = 8001;
    case STORAGE_DELIVERY_PRIVATE = 8002;
    case STORAGE_DELIVERY_AUTHENTICATED = 8003;
}
