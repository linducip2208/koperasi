@extends('laporan.layout')

@section('title', strtoupper($def->name()))
@section('periode', 'Periode ' . ($params['dari'] ?? $params['tanggal'] ?? '') . (isset($params['sampai']) ? ' s/d ' . $params['sampai'] : '') . ' · ' . \App\Support\CooperativeContext::name())

@section('content')
    @if($def->description())
        <p style="font-size:10px;color:#4b5563;margin-bottom:10px;">{{ $def->description() }}</p>
    @endif

    @if(! empty($result->metrics))
        <table style="margin-bottom:12px;">
            <tbody>
                @foreach($result->metrics as $m)
                    <tr>
                        <td style="width:260px;font-weight:bold;">{{ $m['label'] }}</td>
                        <td class="text-right">
                            @if(($m['format'] ?? '') === 'money')
                                Rp {{ number_format((int) $m['value'], 0, ',', '.') }}
                            @else
                                {{ $m['value'] }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <table>
        <thead>
            <tr>
                @foreach($result->columns as $c)
                    <th class="{{ ($c['align'] ?? '') === 'right' ? 'text-right' : '' }}">{{ $c['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($result->rows as $row)
                <tr>
                    @foreach($result->columns as $c)
                        @php $v = $row[$c['key']] ?? ''; @endphp
                        <td class="{{ ($c['align'] ?? '') === 'right' ? 'text-right' : '' }}">
                            @if(($c['format'] ?? '') === 'money')
                                {{ number_format((int) $v, 0, ',', '.') }}
                            @else
                                {{ $v }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ count($result->columns) }}" class="text-center"><em>Belum ada data pada periode ini.</em></td></tr>
            @endforelse
            @if(! empty($result->totals))
                <tr class="grand-total">
                    @foreach($result->columns as $i => $c)
                        <td class="{{ ($c['align'] ?? '') === 'right' ? 'text-right' : '' }}">
                            @if($i === 0) TOTAL
                            @elseif(isset($result->totals[$c['key']])) {{ number_format((int) $result->totals[$c['key']], 0, ',', '.') }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    <p style="font-size:9px;color:#6b7280;margin-top:10px;">
        Dibuat {{ now()->format('d M Y H:i') }} oleh {{ auth()->user()->name ?? 'Sistem' }} ·
        {{ config('product.name') }} v{{ config('product.version') }} · Dokumen internal koperasi — bersifat rahasia.
    </p>
@endsection
