@extends('portal.layout')
@section('title', 'Voting RAT')
@section('page-title', 'Voting RAT')
@section('page-subtitle', 'Suara Anda menentukan masa depan koperasi')

@section('content')
<div class="space-y-6">
    @php
        $votingList = \App\Models\RatVoting::where('is_aktif', true)
            ->where('mulai', '<=', now())->where('selesai', '>=', now())
            ->with(['suara' => fn($q) => $q->where('anggota_id', $anggota->id)])
            ->get();
    @endphp

    @if($votingList->isEmpty())
        <div class="card p-12 text-center text-stone-400">
            <div class="text-5xl mb-3 opacity-30">🗳️</div>
            <p class="font-semibold">Tidak ada voting aktif saat ini</p>
            <p class="text-xs mt-1">Voting muncul saat periode RAT berlangsung</p>
        </div>
    @else
        @foreach($votingList as $v)
            <div class="card p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="font-extrabold text-lg text-stone-800">{{ $v->judul }}</h3>
                        <p class="text-sm text-stone-500 mt-1">{{ $v->deskripsi }}</p>
                        <div class="text-xs text-stone-400 mt-2">
                            🕐 {{ \Carbon\Carbon::parse($v->mulai)->format('d M H:i') }} — {{ \Carbon\Carbon::parse($v->selesai)->format('d M H:i') }}
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">AKTIF</span>
                </div>

                @if($v->suara->isNotEmpty())
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 font-semibold">
                        ✅ Anda sudah memberikan suara. Terima kasih!
                    </div>
                @else
                    <form action="{{ route('portal.voting.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="voting_id" value="{{ $v->id }}">
                        <div class="space-y-3 mb-4">
                            @foreach($v->opsi as $i => $o)
                                <label class="flex items-center gap-3 p-4 bg-stone-50 rounded-xl border border-stone-200 hover:border-emerald-300 cursor-pointer transition">
                                    <input type="radio" name="opsi_index" value="{{ $i }}" required class="w-5 h-5 text-emerald-600">
                                    <span class="font-semibold text-stone-800">{{ $o }}</span>
                                </label>
                            @endforeach
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-extrabold py-3 rounded-xl hover:shadow-lg transition">
                            🗳️ Kirim Suara
                        </button>
                    </form>
                @endif

                {{-- Hasil (muncul setelah vote) --}}
                @php $totalSuara = \App\Models\RatVotingSuara::where('voting_id', $v->id)->count(); @endphp
                @if($totalSuara > 0)
                    <div class="mt-6 pt-4 border-t border-stone-200">
                        <h4 class="font-bold text-sm text-stone-700 mb-3">Hasil Sementara</h4>
                        @foreach($v->opsi as $i => $o)
                            @php $count = \App\Models\RatVotingSuara::where('voting_id', $v->id)->where('opsi_index', $i)->count(); @endphp
                            <div class="mb-2">
                                <div class="flex justify-between text-sm mb-1"><span class="font-semibold text-stone-700">{{ $o }}</span><span class="text-stone-500">{{ $count }} suara ({{ $totalSuara > 0 ? round(($count/$totalSuara)*100) : 0 }}%)</span></div>
                                <div class="w-full bg-stone-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-emerald-500 h-2.5 rounded-full transition-all" style="width: {{ $totalSuara > 0 ? round(($count/$totalSuara)*100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>
@endsection
