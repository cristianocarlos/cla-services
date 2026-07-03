<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @mixin IdeHelperCountry
 */
class Country extends Model
{
    protected $table = 'misc.country';
    protected $primaryKey = 'coun_code';

    const string HOME = 'BRA';
    const string HOME_DESC = 'Brasil';
    const int HOME_DIALING_CODE = 55;
    const string HOME_ISO2_ID = 'br';
}
