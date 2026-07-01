<?php

namespace App\Domain\Numbering;

use App\Support\Tenant\CurrentTenant;
use Illuminate\Support\Facades\DB;

class NumberingService
{
    public static function next(string $kode, ?string $prefix = null, string $format = '{prefix}{ymd}{seq:5}'): string
    {
        $tenantId = CurrentTenant::id();

        return DB::transaction(function () use ($tenantId, $kode, $prefix, $format) {
            $row = DB::table('numbering_setting')
                ->where('tenant_id', $tenantId)
                ->where('kode', $kode)
                ->lockForUpdate()
                ->first();

            if (! $row) {
                DB::table('numbering_setting')->insert([
                    'tenant_id'    => $tenantId,
                    'kode'         => $kode,
                    'prefix'       => $prefix,
                    'format'       => $format,
                    'panjang_seq'  => 5,
                    'next_number'  => 2,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                $seq = 1;
                $usePrefix = $prefix ?? '';
                $useFormat = $format;
            } else {
                $seq = $row->next_number;
                DB::table('numbering_setting')
                    ->where('id', $row->id)
                    ->update(['next_number' => $seq + 1, 'updated_at' => now()]);
                $usePrefix = $row->prefix ?? '';
                $useFormat = $row->format;
            }

            return self::format($useFormat, $usePrefix, $seq);
        });
    }

    private static function format(string $tpl, string $prefix, int $seq): string
    {
        $now = now();
        $replacements = [
            '{prefix}' => $prefix,
            '{ymd}'    => $now->format('ymd'),
            '{ym}'     => $now->format('ym'),
            '{y}'      => $now->format('y'),
            '{Y}'      => $now->format('Y'),
            '{m}'      => $now->format('m'),
            '{d}'      => $now->format('d'),
        ];

        $result = strtr($tpl, $replacements);
        $result = preg_replace_callback('/\{seq:(\d+)\}/', fn ($m) => str_pad((string) $seq, (int) $m[1], '0', STR_PAD_LEFT), $result);

        return $result;
    }
}
