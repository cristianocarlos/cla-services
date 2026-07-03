<?php

namespace App\Queries;

use App\Custom\Helper;
use Illuminate\Support\Facades\DB;

class Query
{
    public static function getSql(bool $shouldCount = false, ?object $params = null): string {
        return '';
    }

    public static function getColumns(?object $params = null): string {
        return '';
    }

    /**
     * @return object{'filters': array, 'bindings': array}
     */
    public static function getCommonFilter(): object {
        return literal();
    }

    /**
     * @return object{'filters': array, 'bindings': array}
     */
    public static function getScopeFilter(): object {
        return literal(filters: [], bindings: []);
    }

    /**
     * @return object{'filters': array, 'bindings': array, 'orders': array}
     */
    public static function resolveRequestFilter(?object $params = null): object {
        return literal();
    }

    public static function resolveAdditionalSql(
        array $filters = [],
        string $orderBy = '',
        int $limit = -1,
        int $offset = -1,
        string $filterPrefix = 'AND',
        bool $shouldCount = false,
    ): string {
        $sql = PHP_EOL;
        $sql .= (empty($filters) ? '' : $filterPrefix . ' ' . implode(PHP_EOL . 'AND ', $filters));
        if (!$shouldCount) {
            $sql .= '
        ' . (empty($orderBy) ? '' : PHP_EOL . 'ORDER BY ' . $orderBy) . '
        ' . ($limit === -1 ? '' : PHP_EOL . 'LIMIT ' . $limit) . '
        ' . ($offset === -1 ? '' : PHP_EOL . 'OFFSET ' . $offset) . '
      ';
        }
        return $sql;
    }

    public static function resolveLikeTerm(string $term, bool $withFci = false): string {
        if (str_contains($term, '%')) return $term; // Se ja vier com % usa o que veio, ai nem precisa usar o ^. ^Maria e Maria% da o mesmo resultado
        $term = str_replace([' e ', ' & ', ', '], ' ', $term);
        $term = str_replace(' ', '%', $term);
        $term = (mb_substr($term, 0, 1) === '^') ? substr_replace($term, '', 0, 1) . '%' : '%' . $term . '%';
        if ($withFci) {
            // No padients/suggest foi constatado lentidão
            // "pers_name_ci LIKE F_CI(:pers_name_ci)" é muito mais lento que "pers_name_ci LIKE :pers_name_ci";
            $term = mb_strtoupper(Helper::removeAccents($term));
        }
        return $term;
    }

    /**
     * Mantém o random consolidado em caso de paginação
     */
    public static function seedRandom(): bool {
        return DB::statement('SELECT SETSEED(' . (request()->query('pg_random_seed') ?: session('pg_random_seed') ?: '0.5') . ')');
    }

    public static function recursiveItemsReorder(array $items, callable $update): void {
        foreach ($items as $index => $data) {
            if (!empty($data['items'])) {
                static::recursiveItemsReorder($data['items'], $update);
            }
            $update($data, $index);
        }
    }
}
