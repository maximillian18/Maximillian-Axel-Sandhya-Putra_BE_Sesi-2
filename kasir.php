<?php
// Data Produk
$produk = [
    ["kode" => "A001", "nama" => "Indomie Goreng", "harga" => 3500, "stok" => 100],
    ["kode" => "A002", "nama" => "Teh Botol Sosro", "harga" => 4000, "stok" => 50],
    ["kode" => "A003", "nama" => "Susu Ultra Milk", "harga" => 12000, "stok" => 30],
    ["kode" => "A004", "nama" => "Roti Tawar Sari Roti", "harga" => 15000, "stok" => 20],
    ["kode" => "A005", "nama" => "Minyak Goreng Bimoli 1L", "harga" => 18000, "stok" => 15]
];

// 1. Fungsi cariProduk
function cariProduk($array_produk, $kode) {
    foreach ($array_produk as $item) {
        if ($item['kode'] === $kode) {
            return $item;
        }
    }
    return null;
}

// 2. Fungsi hitungSubtotal
function hitungSubtotal($harga, $jumlah) {
    return $harga * $jumlah;
}

// 3. Fungsi hitungDiskon
function hitungDiskon($total) {
    if ($total >= 100000) {
        return $total * 0.10;
    } elseif ($total >= 50000) {
        return $total * 0.05;
    } else {
        return 0;
    }
}

// 4. Fungsi hitungPajak
function hitungPajak($total, $persen = 11) {
    return $total * ($persen / 100);
}

// 5. Fungsi kurangiStok
function kurangiStok(&$produk, $kode, $jumlah) {
    foreach ($produk as &$item) {
        if ($item['kode'] === $kode) {
            $item['stok'] -= $jumlah;
            return;
        }
    }
}

// 6. Fungsi formatRupiah
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ",", ".");
}

// 7. Fungsi buatStrukBelanja
function buatStrukBelanja($transaksi, &$array_produk) {
    echo "========================================\n";
    echo "         MINIMARKET SEJAHTERA\n";
    echo "========================================\n";
    echo "Tanggal: " . date("d F Y") . "\n\n";

    $subtotal = 0;

    foreach ($transaksi as $item) {
        $dataProduk = cariProduk($array_produk, $item['kode']);
        if ($dataProduk === null) continue;

        $sub = hitungSubtotal($dataProduk['harga'], $item['jumlah']);
        $subtotal += $sub;

        echo $dataProduk['nama'] . "\n";
        echo formatRupiah($dataProduk['harga']) . " x " . $item['jumlah'] . "        = " . formatRupiah($sub) . "\n\n";

        kurangiStok($array_produk, $item['kode'], $item['jumlah']);
    }

    echo "----------------------------------------\n";
    echo "Subtotal            = " . formatRupiah($subtotal) . "\n";

    $diskon = hitungDiskon($subtotal);
    echo "Diskon (" . ($diskon > 0 ? round($diskon/$subtotal*100) : 0) . "%)         = " . formatRupiah($diskon) . "\n";

    $subtotalDiskon = $subtotal - $diskon;
    echo "Subtotal stl diskon = " . formatRupiah($subtotalDiskon) . "\n";

    $pajak = hitungPajak($subtotalDiskon);
    echo "PPN (11%)           = " . formatRupiah($pajak) . "\n";
    echo "----------------------------------------\n";

    $totalBayar = $subtotalDiskon + $pajak;
    echo "TOTAL BAYAR         = " . formatRupiah($totalBayar) . "\n";
    echo "========================================\n\n";

    echo "Status Stok Setelah Transaksi:\n";
    foreach ($array_produk as $item) {
        echo "- " . $item['nama'] . ": " . $item['stok'] . " pcs\n";
    }
    echo "========================================\n";
    echo "     Terima kasih atas kunjungan Anda\n";
    echo "========================================\n";
}

// Contoh Transaksi
$transaksi = [
    ["kode" => "A001", "jumlah" => 5],
    ["kode" => "A003", "jumlah" => 2],
    ["kode" => "A004", "jumlah" => 1]
];

buatStrukBelanja($transaksi, $produk);

?>
