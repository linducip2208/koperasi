<?php
function add($f, $entries, $prov) {
    $o = '';
    foreach ($entries as $e) {
        $p = explode('|', $e);
        $k = trim($p[0]); $n = trim($p[1]);
        $s = strtolower(str_replace(' ', '-', $n));
        $s = str_replace(["'", '/', '(', ')', '.'], '', $s);
        $s = str_replace('--', '-', $s); $s = trim($s, '-');
        $ne = str_replace("'", "\'", $n);
        $o .= "        ['slug' => '{$s}', 'nama' => '{$ne}', 'kota' => '{$k}', 'provinsi' => '{$prov}'],\n";
    }
    file_put_contents($f, $o, FILE_APPEND);
}
$f = __DIR__ . '/../config/pseo-kecamatan.php';

// BANTEN (8 kab/kota, ~155 kecamatan)
add($f, [
'Lebak|Banjarsari','Lebak|Bayah','Lebak|Bojongmanik','Lebak|Cibadak','Lebak|Cibeber','Lebak|Cigemblong',
'Lebak|Cihara','Lebak|Cijaku','Lebak|Cikulur','Lebak|Cileles','Lebak|Cilograng','Lebak|Cimarga','Lebak|Cipanas',
'Lebak|Cirinten','Lebak|Curugbitung','Lebak|Gunung Kencana','Lebak|Kalanganyar','Lebak|Lebakgedong',
'Lebak|Leuwidamar','Lebak|Maja','Lebak|Malingping','Lebak|Muncang','Lebak|Panggarangan','Lebak|Rangkasbitung',
'Lebak|Sajira','Lebak|Sobang','Lebak|Wanasalam','Lebak|Warunggunung',
'Pandeglang|Angsana','Pandeglang|Banjar','Pandeglang|Bojong','Pandeglang|Cadasari','Pandeglang|Carita',
'Pandeglang|Cibaliung','Pandeglang|Cibitung','Pandeglang|Cigeulis','Pandeglang|Cikeusik','Pandeglang|Cimanggu',
'Pandeglang|Cimanuk','Pandeglang|Cipeucang','Pandeglang|Cisata','Pandeglang|Jiput','Pandeglang|Kaduhejo',
'Pandeglang|Karangtanjung','Pandeglang|Koroncong','Pandeglang|Labuan','Pandeglang|Majasari','Pandeglang|Mandalawangi',
'Pandeglang|Mekarjaya','Pandeglang|Menes','Pandeglang|Munjul','Pandeglang|Pagelaran','Pandeglang|Pandeglang',
'Pandeglang|Panimbang','Pandeglang|Patia','Pandeglang|Picung','Pandeglang|Pulosari','Pandeglang|Saketi',
'Pandeglang|Sindangresmi','Pandeglang|Sobang','Pandeglang|Sukaresmi','Pandeglang|Sumur',
'Serang|Anyar','Serang|Bandung','Serang|Baros','Serang|Binuang','Serang|Bojonegara','Serang|Carenang','Serang|Cikande',
'Serang|Cikeusal','Serang|Cinangka','Serang|Ciomas','Serang|Ciruas','Serang|Gunungsari','Serang|Jawilan',
'Serang|Kibin','Serang|Kopo','Serang|Kragilan','Serang|Kramatwatu','Serang|Lebakwangi','Serang|Mancak',
'Serang|Pabuaran','Serang|Padarincang','Serang|Pamarayan','Serang|Petir','Serang|Pontang','Serang|Pulo Ampel',
'Serang|Tanara','Serang|Tirtayasa','Serang|Tunjung Teja','Serang|Waringinkurung',
'Tangerang|Balaraja','Tangerang|Cikupa','Tangerang|Cisauk','Tangerang|Cisoka','Tangerang|Curug','Tangerang|Gunung Kaler',
'Tangerang|Jambe','Tangerang|Jayanti','Tangerang|Kelapa Dua','Tangerang|Kemiri','Tangerang|Kosambi','Tangerang|Kresek',
'Tangerang|Kronjo','Tangerang|Legok','Tangerang|Mauk','Tangerang|Mekar Baru','Tangerang|Pagedangan','Tangerang|Pakuhaji',
'Tangerang|Panongan','Tangerang|Pasar Kemis','Tangerang|Rajeg','Tangerang|Sepatan','Tangerang|Sepatan Timur',
'Tangerang|Sindang Jaya','Tangerang|Solear','Tangerang|Sukadiri','Tangerang|Sukamulya','Tangerang|Teluknaga',
'Tangerang|Tigaraksa',
'Cilegon|Cibeber','Cilegon|Cilegon','Cilegon|Citangkil','Cilegon|Ciwandan','Cilegon|Gerogol','Cilegon|Jombang',
'Cilegon|Pulomerak','Cilegon|Purwakarta',
'Serang Kota|Cipocok Jaya','Serang Kota|Curug','Serang Kota|Kasemen','Serang Kota|Serang','Serang Kota|Taktakan',
'Serang Kota|Walantaka',
'Tangerang Kota|Batuceper','Tangerang Kota|Benda','Tangerang Kota|Cibodas','Tangerang Kota|Ciledug','Tangerang Kota|Cipondoh',
'Tangerang Kota|Jatiuwung','Tangerang Kota|Karang Tengah','Tangerang Kota|Karawaci','Tangerang Kota|Larangan',
'Tangerang Kota|Neglasari','Tangerang Kota|Periuk','Tangerang Kota|Pinang','Tangerang Kota|Tangerang',
'Tangerang Selatan|Ciputat','Tangerang Selatan|Ciputat Timur','Tangerang Selatan|Pamulang','Tangerang Selatan|Pondok Aren',
'Tangerang Selatan|Serpong','Tangerang Selatan|Serpong Utara','Tangerang Selatan|Setu',
], 'Banten');

// BALI (9 kab/kota, 57 kecamatan)
add($f, [
'Badung|Abiansemal','Badung|Kuta','Badung|Kuta Selatan','Badung|Kuta Utara','Badung|Mengwi','Badung|Petang',
'Bangli|Bangli','Bangli|Kintamani','Bangli|Susut','Bangli|Tembuku',
'Buleleng|Banjar','Buleleng|Buleleng','Buleleng|Busungbiu','Buleleng|Gerokgak','Buleleng|Kubutambahan',
'Buleleng|Sawan','Buleleng|Seririt','Buleleng|Sukasada','Buleleng|Tejakula',
'Gianyar|Blahbatuh','Gianyar|Gianyar','Gianyar|Payangan','Gianyar|Sukawati','Gianyar|Tampaksiring','Gianyar|Tegallalang',
'Gianyar|Ubud',
'Jembrana|Jembrana','Jembrana|Melaya','Jembrana|Mendoyo','Jembrana|Negara','Jembrana|Pekutatan',
'Karangasem|Abang','Karangasem|Bebandem','Karangasem|Karangasem','Karangasem|Kubu','Karangasem|Manggis',
'Karangasem|Rendang','Karangasem|Selat','Karangasem|Sidemen',
'Klungkung|Banjarangkan','Klungkung|Dawan','Klungkung|Klungkung','Klungkung|Nusa Penida',
'Tabanan|Baturiti','Tabanan|Kediri','Tabanan|Kerambitan','Tabanan|Marga','Tabanan|Penebel','Tabanan|Pupuan',
'Tabanan|Selemadeg','Tabanan|Selemadeg Barat','Tabanan|Selemadeg Timur','Tabanan|Tabanan',
'Denpasar|Denpasar Barat','Denpasar|Denpasar Selatan','Denpasar|Denpasar Timur','Denpasar|Denpasar Utara',
], 'Bali');

// NUSA TENGGARA BARAT (10 kab/kota, ~117 kecamatan)
add($f, [
'Bima|Ambalawi','Bima|Belo','Bima|Bolo','Bima|Donggo','Bima|Lambitu','Bima|Lambu','Bima|Langgudu','Bima|Madapangga',
'Bima|Monta','Bima|Palibelo','Bima|Parado','Bima|Sanggar','Bima|Sape','Bima|Soromandi','Bima|Tambora','Bima|Wawo',
'Bima|Wera','Bima|Woha',
'Dompu|Dompu','Dompu|Hu\'u','Dompu|Kempo','Dompu|Kilo','Dompu|Manggelewa','Dompu|Pajo','Dompu|Pekat','Dompu|Woja',
'Lombok Barat|Batu Layar','Lombok Barat|Gerung','Lombok Barat|Gunungsari','Lombok Barat|Kediri','Lombok Barat|Kuripan',
'Lombok Barat|Labuapi','Lombok Barat|Lembar','Lombok Barat|Lingsar','Lombok Barat|Narmada','Lombok Barat|Sekotong',
'Lombok Tengah|Batukliang','Lombok Tengah|Batukliang Utara','Lombok Tengah|Janapria','Lombok Tengah|Jonggat',
'Lombok Tengah|Kopang','Lombok Tengah|Praya','Lombok Tengah|Praya Barat','Lombok Tengah|Praya Barat Daya',
'Lombok Tengah|Praya Tengah','Lombok Tengah|Praya Timur','Lombok Tengah|Pringgarata','Lombok Tengah|Pujut',
'Lombok Timur|Aikmel','Lombok Timur|Jerowaru','Lombok Timur|Keruak','Lombok Timur|Labuhan Haji','Lombok Timur|Masbagik',
'Lombok Timur|Montong Gading','Lombok Timur|Pringgabaya','Lombok Timur|Pringgasela','Lombok Timur|Sakra',
'Lombok Timur|Sakra Barat','Lombok Timur|Sakra Timur','Lombok Timur|Sambelia','Lombok Timur|Selong',
'Lombok Timur|Sembalun','Lombok Timur|Sikur','Lombok Timur|Suela','Lombok Timur|Sukamulia','Lombok Timur|Suralaga',
'Lombok Timur|Terara','Lombok Timur|Wanasaba',
'Lombok Utara|Bayan','Lombok Utara|Gangga','Lombok Utara|Kayangan','Lombok Utara|Pemenang','Lombok Utara|Tanjung',
'Sumbawa|Alas','Sumbawa|Alas Barat','Sumbawa|Batulanteh','Sumbawa|Buer','Sumbawa|Empang','Sumbawa|Labangka',
'Sumbawa|Labuhan Badas','Sumbawa|Lantung','Sumbawa|Lape','Sumbawa|Lenangguar','Sumbawa|Lopok','Sumbawa|Lunyuk',
'Sumbawa|Maronge','Sumbawa|Moyo Hilir','Sumbawa|Moyo Hulu','Sumbawa|Moyo Utara','Sumbawa|Orong Telu','Sumbawa|Plampang',
'Sumbawa|Rhee','Sumbawa|Ropang','Sumbawa|Sumbawa','Sumbawa|Tarano','Sumbawa|Unter Iwes','Sumbawa|Utan',
'Sumbawa Barat|Brang Ene','Sumbawa Barat|Brang Rea','Sumbawa Barat|Jereweh','Sumbawa Barat|Maluk','Sumbawa Barat|Poto Tano',
'Sumbawa Barat|Sekongkang','Sumbawa Barat|Seteluk','Sumbawa Barat|Taliwang',
'Bima Kota|Asakota','Bima Kota|Mpunda','Bima Kota|Raba','Bima Kota|Rasanae Barat','Bima Kota|Rasanae Timur',
'Mataram|Ampenan','Mataram|Cakranegara','Mataram|Mataram','Mataram|Sandubaya','Mataram|Sekarbela','Mataram|Selaparang',
], 'Nusa Tenggara Barat');

echo "Banten, Bali, NTB done.\n";
