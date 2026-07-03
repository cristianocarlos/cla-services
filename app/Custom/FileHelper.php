<?php

namespace App\Custom;

class FileHelper
{
    /**
     * @return object{'current': string, 'new': string}
     */
    public static function resolveFileDotExt(string $value): object {
        $previousDotExt = trim(mb_strtolower(pathinfo($value, PATHINFO_EXTENSION))); // extrai a extensão do nome
        $newDotExt = '';
        if ($previousDotExt && mb_strlen($previousDotExt) < 8) {
            if ($previousDotExt === 'gz' && str_ends_with($value, '.tar.gz')) {
                $newDotExt = '.tar.gz';
            } elseif ($previousDotExt === 'jpeg') {
                $newDotExt = '.jpg';
            } else {
                $newDotExt = '.' . $previousDotExt;
            }
        }
        return literal(current: $previousDotExt, new: $newDotExt);
    }

    public static function resolveFileName(string $value, int $maxLength, string $hash, bool $stripExtension = false): string {
        $dotExt = static::resolveFileDotExt($value);
        $value = Helper::cleanUpText($value);
        $value = Helper::removeAccents($value);
        $value = mb_strtolower($value, 'UTF-8'); // caixa baixa pra facilitar o processo
        $value = str_replace($dotExt->current, '', $value); // elimina a extensão pra fazer as substituições
        $value = preg_replace('/[^a-z0-9]/', '-', $value); // substitui tudo que não é letra e número por traço
        $value = preg_replace('/-{2,}/', '-', trim($value, '-')); // elimina os traços múltiplos
        $sufix = $hash . ($stripExtension ? '' : $dotExt->new);
        $resolvedMaxLength = ($maxLength - mb_strlen($sufix));
        $value = trim(mb_substr($value, 0, $resolvedMaxLength), '-');
        return trim($value) . $sufix; // adiciona novamente a extensão
    }

    public static function resolveUploadName(string $name, string $mimeType = ''): string {
        $dotExt = static::resolveFileDotExt($name);
        $nameWithoutExt = str_replace($dotExt->current, '', Cast::textLine($name));
        $newName = mb_substr($nameWithoutExt, 0, 100) . $dotExt->new;
        if ($mimeType && !$dotExt->new) { // Snapshot
            $newName .= $mimeType === 'image/png' ? '.png' : '.jpg';
        }
        return $newName;
    }

    /**
     * Converte o tipo bytea de coluna do postgresql em binário
     */
    public static function hex2Blob($value): string {
        return hex2bin(stream_get_contents($value));
    }
}
