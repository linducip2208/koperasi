<?php

namespace App\Filament\Pages;

use App\Models\Kas;
use App\Models\TokoBarang;
use App\Models\TokoPenjualan;
use App\Filament\Concerns\HasRoleAccess;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class PosKasir extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static ?string $permissionModule = 'pos';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Toko & Unit Usaha';
    protected static ?string $navigationLabel = 'POS Kasir (Touch)';
    protected static ?string $title = 'POS Kasir';
    protected static ?int $navigationSort = 31;

    protected static string $view = 'filament.pages.pos-kasir';

    public ?array $data = [];

    public array $cart = [];
    public int $cartTotal = 0;
    public string $searchQuery = '';
    public array $searchResults = [];

    public function mount(): void
    {
        $this->form->fill([
            'metode_bayar' => 'cash',
            'tanggal'      => now()->toDateString(),
            'bayar'        => 0,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Hidden::make('tanggal'),
            Forms\Components\Hidden::make('metode_bayar'),
            Forms\Components\Hidden::make('bayar'),
        ])->statePath('data');
    }

    public function updatedSearchQuery(): void
    {
        if (strlen($this->searchQuery) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = TokoBarang::where('aktif', true)
            ->where(fn ($q) => $q->where('nama', 'like', "%{$this->searchQuery}%")
                ->orWhere('barcode', 'like', "%{$this->searchQuery}%"))
            ->limit(12)
            ->get()
            ->map(fn ($b) => [
                'id'    => $b->id,
                'nama'  => $b->nama,
                'harga' => (int) ($b->harga_jual_umum ?? 0),
                'stok'  => $b->stok ?? 0,
            ])
            ->toArray();
    }

    public function addToCart(int $barangId): void
    {
        $barang = TokoBarang::find($barangId);
        if (!$barang || $barang->stok <= 0) return;

        $key = (string) $barangId;
        if (isset($this->cart[$key])) {
            $this->cart[$key]['qty']++;
            $this->cart[$key]['subtotal'] = $this->cart[$key]['qty'] * $this->cart[$key]['harga'];
        } else {
            $this->cart[$key] = [
                'id'       => $barang->id,
                'nama'     => $barang->nama,
                'harga'    => (int) ($barang->harga_jual_umum ?? 0),
                'hpp'      => (int) ($barang->harga_beli ?? 0),
                'qty'      => 1,
                'subtotal' => (int) ($barang->harga_jual_umum ?? 0),
            ];
        }
        $this->recalculateCart();
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function updateQty(string $key, int $qty): void
    {
        if ($qty <= 0) {
            unset($this->cart[$key]);
        } elseif (isset($this->cart[$key])) {
            $this->cart[$key]['qty'] = $qty;
            $this->cart[$key]['subtotal'] = $qty * $this->cart[$key]['harga'];
        }
        $this->recalculateCart();
    }

    public function removeItem(string $key): void
    {
        unset($this->cart[$key]);
        $this->recalculateCart();
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->cartTotal = 0;
    }

    protected function recalculateCart(): void
    {
        $this->cartTotal = collect($this->cart)->sum('subtotal');
    }

    public function processPayment(): void
    {
        if (empty($this->cart)) return;

        $this->validate([
            'data.metode_bayar' => 'required|in:cash,transfer,qris',
            'data.bayar'        => 'required|integer|min:0',
        ]);

        $bayar = (int) $this->data['bayar'];
        if ($bayar < $this->cartTotal && $this->data['metode_bayar'] === 'cash') {
            Notification::make()->title('Uang kurang!')->danger()->send();
            return;
        }

        $kembalian = max(0, $bayar - $this->cartTotal);

        $penjualan = TokoPenjualan::create([
            'tenant_id'    => 1,
            'tanggal'      => $this->data['tanggal'] ?? now(),
            'nomor'        => 'POS-' . now()->format('Ymd-His') . '-' . rand(100, 999),
            'metode_bayar' => $this->data['metode_bayar'],
            'status'       => 'lunas',
            'subtotal'     => $this->cartTotal,
            'diskon'       => 0,
            'pajak'        => 0,
            'total'        => $this->cartTotal,
            'bayar'        => $bayar,
            'kembali'      => $kembalian,
            'kas_id'       => Kas::where('aktif', true)->where('tipe', 'kas')->value('id'),
        ]);

        foreach ($this->cart as $item) {
            $penjualan->detail()->create([
                'barang_id'    => $item['id'],
                'jumlah'       => $item['qty'],
                'harga_satuan' => $item['harga'],
                'hpp'          => $item['hpp'],
                'subtotal'     => $item['subtotal'],
            ]);

            TokoBarang::where('id', $item['id'])->decrement('stok', $item['qty']);
        }

        $this->clearCart();
        $this->data['bayar'] = 0;

        Notification::make()
            ->title("Transaksi berhasil! Kembalian: Rp " . number_format($kembalian, 0, ',', '.'))
            ->success()
            ->send();
    }

    public function getHeading(): string|Htmlable
    {
        return new HtmlString('<span class="text-emerald-600">POS</span> Kasir');
    }
}
