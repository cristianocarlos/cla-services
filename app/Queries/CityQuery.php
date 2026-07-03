<?php

namespace App\Queries;

use App\DTOs\AddressDTO;
use App\Enums\YiiEnum;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class CityQuery extends Query
{
    public static string $colLabel = "CONCAT(city_name, ' (',  CASE WHEN city_coun = '" . Country::HOME . "' THEN city_stte_acro ELSE coun_name END, ')')";

    /**
     * @return object{'latitude': float|null, 'longitude': float|null}|null
     */
    public static function getGeoLocationCoordinates(?int $id): ?object {
        if ($id == null) return null;
        $sql = <<<SQL
          SELECT city_geo[1] AS latitude
               , city_geo[2] AS longitude
            FROM misc.city
           WHERE city_code=:city_code
        SQL;
        /** @var object{'latitude': float|null, 'longitude': float|null}|null $coordinates */
        $coordinates = collect(DB::select($sql, ['city_code' => $id]))->first();
        if (!$coordinates) return null;
        if (!$coordinates->latitude) return null;
        if (!$coordinates->longitude) return null;
        return $coordinates;
    }

    public static function getSql(bool $shouldCount = false, ?object $params = null): string {
        $sqlVar1 = static::$colLabel;
        return <<<SQL
          SELECT city_code AS id
               , $sqlVar1 AS label
            FROM misc.city
               , misc.country
           WHERE coun_code = city_coun
        SQL;
    }

    public static function getSuggestOptions(
        ?string $term,
        int $limit = 40,
        int $offset = 0,
        string $countryId = Country::HOME,
    ): array {
        // pt_br (filtros e nomes das cidades)
        $filters = [];
        $bindings = [];
        if ($term !== null) {
            $term = trim($term);
            if ($term) {
                $filters[] = 'city_name_ci LIKE :city_name_ci';
                $bindings['city_name_ci'] = parent::resolveLikeTerm($term, true);
            }
        }

        if ($countryId) {
            $filters[] = 'city_coun = :city_coun';
            $bindings['city_coun'] = $countryId;
        }

        $sqlVar1 = static::$colLabel;
        $sqlVar2 = YiiEnum::STATUS_OK->value;
        $sql = <<<SQL
          SELECT city_code AS id
               , $sqlVar1 AS label
               , JSONB_BUILD_OBJECT(
                  'city_coun', city_coun,
                  'city_coun_desc', coun_name
               ) AS data_list_item_extra
            FROM misc.city
               , misc.country
           WHERE city_stat = $sqlVar2
             AND coun_code = city_coun
        SQL;
        $sql .= parent::resolveAdditionalSql(
            filters: $filters,
            orderBy: 'city_name_ci',
            limit: $limit,
            offset: $offset,
        );
        return DB::select($sql, $bindings);
    }

    public static function getFilterOptions(int $limit): array {
        $sql = static::getSql() . parent::resolveAdditionalSql(
            orderBy: 'city_rele DESC, city_name_ci',
            limit: $limit,
        );
        return DB::select($sql);
    }

    public static function getFilterTags(array $ids): array {
        $sql = static::getSql() . parent::resolveAdditionalSql(
            filters: ['city_code IN (' . implode(',', $ids) . ')'],
        );
        return DB::select($sql);
    }

    public static function resolveViaCepJsonResponse(?array $responseData): AddressDTO {
        $sql = <<<SQL
          SELECT city_code
            FROM misc.city
           WHERE city_name_ci = F_CI(:city_name_ci)
             AND city_stte_acro = :city_stte_acro
        SQL;
        $bindings['city_name_ci'] = $responseData['localidade'];
        $bindings['city_stte_acro'] = $responseData['uf'];
        $data = DB::selectOne($sql, $bindings);
        $cityCode = $data?->city_code;
        $cityDesc = $responseData['localidade'] . ' (' . $responseData['uf'] . ')';
        // line1
        $logradouro = $responseData['logradouro'] ?? null;
        $line1 = str_replace([
            'Alameda',
            'Avenida',
            'Conjunto',
            'Doutor',
            'Engenheiro',
            'Rodovia',
        ], [
            'Al.',
            'Av.',
            'Cj.',
            'Dr.',
            'Eng.',
            'Rod.',
        ], trim($logradouro));
        return new AddressDTO(
            city: $cityCode,
            city_desc: $cityDesc,
            complement: null,
            line1: $line1,
            line2: $responseData['bairro'],
            number: $responseData['numero'] ?? null,
            zip_code: $responseData['cep'],
        );
    }
}
