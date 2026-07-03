<?php

namespace App\Queries;

use App\Enums\YiiEnum;
use Illuminate\Support\Facades\DB;

class CountryQuery extends Query
{
    public static function getSuggestOptions(
        ?string $term,
        int $limit = 40,
        int $offset = 0,
        int $withCity = 0, // 0|1 apenas países com cidades na tabela city
    ): array {
        $filters = [];
        $bindings = [];
        if ($term !== null) {
            $term = trim($term);
            if ($term) {
                $filters[] = 'coun_name_ci LIKE :coun_name_ci';
                $bindings['coun_name_ci'] = parent::resolveLikeTerm($term, true);
            }
        }
        if ($withCity) {
            $filters[] = 'EXISTS (SELECT * FROM misc.city WHERE city_coun = coun_code)';
        }
        $sqlVar1 = YiiEnum::STATUS_OK->value;
        $sql = <<<SQL
          SELECT coun_code AS id
               , coun_name AS label
               , JSONB_BUILD_OBJECT (
                   'dialing_code', coun_dial::text,
                   'id', coun_code,
                   'iso2_id', coun_iso2
                 ) AS data_list_item_extra
            FROM misc.country
           WHERE coun_stat = $sqlVar1
          SQL;
        $sql .= parent::resolveAdditionalSql(
            filters: $filters,
            orderBy: 'coun_name_ci',
            limit: $limit,
            offset: $offset,
        );
        return DB::select($sql, $bindings);
    }
}
