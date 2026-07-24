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
 * @property int $file_code Sequential code
 * @property string $file_name Name
 * @property string|null $file_path Path
 * @property int $file_byte Size (bytes)
 * @property string $file_mity Mime type
 * @property string $file_daho Date hour (creation, search pourposes)
 * @property int|null $file_moid Id in the table of the module, e.g. dtse_code
 * @property int|null $file_modu Module (FK) without constraint on porpouse
 * @property int $file_type Type (type: file)
 * @property int $file_stse Storage service (type: storageService)
 * @property bool $file_tras Trashed, to be deleted on the storage service
 * @property string|null $file_data Binary data
 * @property string|null $sys_log JSON log: {
 *         insert_date_hour: timestamp,
 *         insert_who_id: number,
 *         insert_who_name: string,
 *         last_update_date_hour: timestamp,
 *         last_update_who_id: number,
 *         last_update_who_name: string
 *       }
 * @property string|null $file_tag_rows JSON tags
 * @property int|null $file_heig File height (for image)
 * @property int|null $file_widt File width (for image)
 * @property bool $file_conf Confirmed (handleUploadSuccess)
 * @property int $file_orde Order (galleries only)
 * @property int $file_stde Storage delivery (type: storageDelivery)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileByte($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileConf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileDaho($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileHeig($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileMity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileModu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileMoid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileOrde($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileStde($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileStse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileTagRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileTras($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileWidt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereSysLog($value)
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

