<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $aptk_code Sequential code
 * @property string $aptk_toke Token
 * @property string $aptk_name Name
 * @property string $aptk_daho Date hour
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiToken whereAptkCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiToken whereAptkDaho($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiToken whereAptkName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiToken whereAptkToke($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperApiToken {}
}

namespace App\Models{
/**
 * @property int $coun_code Alpha-3 code
 * @property string|null $coun_name_ci Name (case insensitive)
 * @property string $coun_name_en Name (en)
 * @property string|null $coun_name Name
 * @property int|null $coun_ncod
 * @property string|null $coun_iso2 Alpha-2 code
 * @property int|null $coun_dial Dial code
 * @property int $coun_stat Status (type: status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCounCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCounDial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCounIso2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCounName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCounNameCi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCounNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCounNcod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCounStat($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCountry {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFile {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

