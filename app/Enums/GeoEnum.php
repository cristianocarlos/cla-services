<?php

namespace App\Enums;

enum GeoEnum: string
{
    case COUNTRY_HOME = 'BRA';
    case COUNTRY_HOME_DESC = 'Brasil';
    case COUNTRY_HOME_DIALING_CODE = '55';
    case COUNTRY_HOME_ISO2_ID = 'br';
    case STATE_FOREIGN = '98';
}
