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

// KALIMANTAN BARAT (14 kab/kota, ~174 kecamatan)
add($f, [
'Bengkayang|Bengkayang','Bengkayang|Capkala','Bengkayang|Jagoi Babang','Bengkayang|Ledo','Bengkayang|Lembah Bawang',
'Bengkayang|Lumar','Bengkayang|Monterado','Bengkayang|Samalantan','Bengkayang|Sanggau Ledo','Bengkayang|Seluas',
'Bengkayang|Siding','Bengkayang|Sungai Betung','Bengkayang|Sungai Raya','Bengkayang|Sungai Raya Kepulauan',
'Bengkayang|Suti Semarang','Bengkayang|Teriak','Bengkayang|Tujuh Belas',
'Kapuas Hulu|Badau','Kapuas Hulu|Batang Lupar','Kapuas Hulu|Bika','Kapuas Hulu|Boyan Tanjung','Kapuas Hulu|Bunut Hilir',
'Kapuas Hulu|Bunut Hulu','Kapuas Hulu|Embaloh Hilir','Kapuas Hulu|Embaloh Hulu','Kapuas Hulu|Empanang',
'Kapuas Hulu|Hulu Gurung','Kapuas Hulu|Jongkong','Kapuas Hulu|Kalis','Kapuas Hulu|Mentebah','Kapuas Hulu|Pengkadan',
'Kapuas Hulu|Puring Kencana','Kapuas Hulu|Putussibau Selatan','Kapuas Hulu|Putussibau Utara','Kapuas Hulu|Seberuang',
'Kapuas Hulu|Selimbau','Kapuas Hulu|Semitau','Kapuas Hulu|Silat Hilir','Kapuas Hulu|Silat Hulu','Kapuas Hulu|Suhaid',
'Kayong Utara|Kepulauan Karimata','Kayong Utara|Pulau Maya','Kayong Utara|Seponti','Kayong Utara|Simpang Hilir',
'Kayong Utara|Sukadana','Kayong Utara|Teluk Batang',
'Ketapang|Air Upas','Ketapang|Benua Kayong','Ketapang|Delta Pawan','Ketapang|Hulu Sungai','Ketapang|Jelai Hulu',
'Ketapang|Kendawangan','Ketapang|Manis Mata','Ketapang|Marau','Ketapang|Matan Hilir Selatan','Ketapang|Matan Hilir Utara',
'Ketapang|Muara Pawan','Ketapang|Nanga Tayap','Ketapang|Pemahan','Ketapang|Sandai','Ketapang|Simpang Dua',
'Ketapang|Simpang Hulu','Ketapang|Singkup','Ketapang|Sungai Laur','Ketapang|Sungai Melayu Rayak','Ketapang|Tumbang Titi',
'Kubu Raya|Batu Ampar','Kubu Raya|Kuala Mandor B','Kubu Raya|Kubu','Kubu Raya|Rasau Jaya','Kubu Raya|Sungai Ambawang',
'Kubu Raya|Sungai Kakap','Kubu Raya|Sungai Raya','Kubu Raya|Teluk Pakedai','Kubu Raya|Terentang',
'Landak|Air Besar','Landak|Banyuke Hulu','Landak|Jelimpo','Landak|Kuala Behe','Landak|Mandor','Landak|Mempawah Hulu',
'Landak|Menjalin','Landak|Menyuke','Landak|Meranti','Landak|Ngabang','Landak|Sebangki','Landak|Sengah Temila','Landak|Sompak',
'Melawi|Belimbing','Melawi|Belimbing Hulu','Melawi|Ella Hilir','Melawi|Menukung','Melawi|Nanga Pinoh','Melawi|Pinoh Selatan',
'Melawi|Pinoh Utara','Melawi|Sayan','Melawi|Sokan','Melawi|Tanah Pinoh','Melawi|Tanah Pinoh Barat',
'Mempawah|Anjongan','Mempawah|Mempawah Hilir','Mempawah|Mempawah Timur','Mempawah|Sadaniang','Mempawah|Segedong',
'Mempawah|Siantan','Mempawah|Sungai Kunyit','Mempawah|Sungai Pinyuh','Mempawah|Toho',
'Sambas|Galing','Sambas|Jawai','Sambas|Jawai Selatan','Sambas|Paloh','Sambas|Pemangkat','Sambas|Sajad','Sambas|Sajingan Besar',
'Sambas|Salatiga','Sambas|Sambas','Sambas|Sebawi','Sambas|Sejangkung','Sambas|Selakau','Sambas|Selakau Timur',
'Sambas|Semparuk','Sambas|Subah','Sambas|Tangaran','Sambas|Tebas','Sambas|Tekarang','Sambas|Teluk Keramat',
'Sanggau|Balai','Sanggau|Beduai','Sanggau|Bonti','Sanggau|Entikong','Sanggau|Jangkang','Sanggau|Kapuas','Sanggau|Kembayan',
'Sanggau|Meliau','Sanggau|Mukok','Sanggau|Noyan','Sanggau|Parindu','Sanggau|Sekayam','Sanggau|Tayan Hilir','Sanggau|Tayan Hulu',
'Sanggau|Toba',
'Sekadau|Belitang','Sekadau|Belitang Hilir','Sekadau|Belitang Hulu','Sekadau|Nanga Mahap','Sekadau|Nanga Taman',
'Sekadau|Sekadau Hilir','Sekadau|Sekadau Hulu',
'Sintang|Ambalau','Sintang|Binjai Hulu','Sintang|Dedai','Sintang|Kayan Hilir','Sintang|Kayan Hulu','Sintang|Kelam Permai',
'Sintang|Ketungau Hilir','Sintang|Ketungau Hulu','Sintang|Ketungau Tengah','Sintang|Sepauk','Sintang|Serawai',
'Sintang|Sintang','Sintang|Sungai Tebelian','Sintang|Tempunak',
'Pontianak|Pontianak Barat','Pontianak|Pontianak Kota','Pontianak|Pontianak Selatan','Pontianak|Pontianak Tenggara',
'Pontianak|Pontianak Timur','Pontianak|Pontianak Utara',
'Singkawang|Singkawang Barat','Singkawang|Singkawang Selatan','Singkawang|Singkawang Tengah','Singkawang|Singkawang Timur',
'Singkawang|Singkawang Utara',
], 'Kalimantan Barat');

// KALIMANTAN TENGAH (14 kab/kota, ~136 kecamatan)
add($f, [
'Barito Selatan|Dusun Hilir','Barito Selatan|Dusun Selatan','Barito Selatan|Dusun Utara','Barito Selatan|Gunung Bintang Awai',
'Barito Selatan|Jenamas','Barito Selatan|Karau Kuala',
'Barito Timur|Awang','Barito Timur|Benua Lima','Barito Timur|Dusun Tengah','Barito Timur|Dusun Timur','Barito Timur|Karusen Janang',
'Barito Timur|Paju Epat','Barito Timur|Paku','Barito Timur|Patangkep Tutui','Barito Timur|Pematang Karau','Barito Timur|Raren Batuah',
'Barito Utara|Gunung Purei','Barito Utara|Gunung Timang','Barito Utara|Lahei','Barito Utara|Lahei Barat','Barito Utara|Montallat',
'Barito Utara|Teweh Baru','Barito Utara|Teweh Selatan','Barito Utara|Teweh Tengah','Barito Utara|Teweh Timur',
'Gunung Mas|Damang Batu','Gunung Mas|Kahayan Hulu Utara','Gunung Mas|Kurun','Gunung Mas|Manuhing','Gunung Mas|Manuhing Raya',
'Gunung Mas|Mihing Raya','Gunung Mas|Miri Manasa','Gunung Mas|Rungan','Gunung Mas|Rungan Barat','Gunung Mas|Rungan Hulu',
'Gunung Mas|Sepang','Gunung Mas|Tewah',
'Kapuas|Basarang','Kapuas|Bataguh','Kapuas|Dadahup','Kapuas|Kapuas Barat','Kapuas|Kapuas Hilir','Kapuas|Kapuas Hulu',
'Kapuas|Kapuas Kuala','Kapuas|Kapuas Murung','Kapuas|Kapuas Tengah','Kapuas|Kapuas Timur','Kapuas|Mandau Talawang',
'Kapuas|Mantangai','Kapuas|Pasak Talawang','Kapuas|Pulau Petak','Kapuas|Selat','Kapuas|Tamban Catur','Kapuas|Timpah',
'Katingan|Bukit Raya','Katingan|Kamipang','Katingan|Katingan Hilir','Katingan|Katingan Hulu','Katingan|Katingan Kuala',
'Katingan|Katingan Tengah','Katingan|Marikit','Katingan|Mendawai','Katingan|Petak Malai','Katingan|Pulau Malan',
'Katingan|Sanaman Mantikei','Katingan|Tasik Payawan','Katingan|Tewang Sangalang Garing',
'Kotawaringin Barat|Arut Selatan','Kotawaringin Barat|Arut Utara','Kotawaringin Barat|Kotawaringin Lama',
'Kotawaringin Barat|Kumai','Kotawaringin Barat|Pangkalan Banteng','Kotawaringin Barat|Pangkalan Lada',
'Kotawaringin Timur|Antang Kalang','Kotawaringin Timur|Baamang','Kotawaringin Timur|Bukit Santuai','Kotawaringin Timur|Cempaga',
'Kotawaringin Timur|Cempaga Hulu','Kotawaringin Timur|Kota Besi','Kotawaringin Timur|Mentawa Baru Ketapang',
'Kotawaringin Timur|Mentaya Hilir Selatan','Kotawaringin Timur|Mentaya Hilir Utara','Kotawaringin Timur|Mentaya Hulu',
'Kotawaringin Timur|Parenggean','Kotawaringin Timur|Pulau Hanaut','Kotawaringin Timur|Seranau','Kotawaringin Timur|Telaga Antang',
'Kotawaringin Timur|Telawang','Kotawaringin Timur|Teluk Sampit','Kotawaringin Timur|Tualan Hulu',
'Lamandau|Batang Kawa','Lamandau|Belantikan Raya','Lamandau|Bulik','Lamandau|Bulik Timur','Lamandau|Delang','Lamandau|Lamandau',
'Lamandau|Menthobi Raya','Lamandau|Sematu Jaya',
'Murung Raya|Barito Tuhup Raya','Murung Raya|Laung Tuhup','Murung Raya|Murung','Murung Raya|Permata Intan',
'Murung Raya|Seribu Riam','Murung Raya|Sumber Barito','Murung Raya|Sungai Babuat','Murung Raya|Tanah Siang',
'Murung Raya|Tanah Siang Selatan','Murung Raya|Uut Murung',
'Pulang Pisau|Banama Tingang','Pulang Pisau|Jabiren Raya','Pulang Pisau|Kahayan Hilir','Pulang Pisau|Kahayan Kuala',
'Pulang Pisau|Kahayan Tengah','Pulang Pisau|Maliku','Pulang Pisau|Pandih Batu','Pulang Pisau|Sebangau Kuala',
'Sukamara|Balai Riam','Sukamara|Jelai','Sukamara|Pantai Lunci','Sukamara|Permata Kecubung','Sukamara|Sukamara',
'Seruyan|Batu Ampar','Seruyan|Danau Seluluk','Seruyan|Danau Sembuluh','Seruyan|Hanau','Seruyan|Seruyan Hilir',
'Seruyan|Seruyan Hilir Timur','Seruyan|Seruyan Hulu','Seruyan|Seruyan Raya','Seruyan|Seruyan Tengah','Seruyan|Suling Tambun',
'Palangka Raya|Bukit Batu','Palangka Raya|Jekan Raya','Palangka Raya|Pahandut','Palangka Raya|Rakumpit','Palangka Raya|Sebangau',
], 'Kalimantan Tengah');

// KALIMANTAN SELATAN (13 kab/kota, ~153 kecamatan)
add($f, [
'Balangan|Awayan','Balangan|Batu Mandi','Balangan|Halong','Balangan|Juai','Balangan|Lampihong','Balangan|Paringin',
'Balangan|Paringin Selatan','Balangan|Tebing Tinggi',
'Banjar|Aluh Aluh','Banjar|Aranio','Banjar|Astambul','Banjar|Beruntung Baru','Banjar|Gambut','Banjar|Karang Intan',
'Banjar|Kertak Hanyar','Banjar|Martapura','Banjar|Martapura Barat','Banjar|Martapura Timur','Banjar|Mataraman',
'Banjar|Paramasan','Banjar|Pengaron','Banjar|Sambung Makmur','Banjar|Simpang Empat','Banjar|Sungai Pinang',
'Banjar|Sungai Tabuk','Banjar|Telaga Bauntung','Banjar|Tatah Makmur',
'Barito Kuala|Alalak','Barito Kuala|Anjir Muara','Barito Kuala|Anjir Pasar','Barito Kuala|Bakumpai','Barito Kuala|Barambai',
'Barito Kuala|Belawang','Barito Kuala|Cerbon','Barito Kuala|Jejangkit','Barito Kuala|Kuripan','Barito Kuala|Mandastana',
'Barito Kuala|Marabahan','Barito Kuala|Mekar Sari','Barito Kuala|Rantau Badauh','Barito Kuala|Tabukan','Barito Kuala|Tabunganen',
'Barito Kuala|Tamban','Barito Kuala|Wanaraya',
'Hulu Sungai Selatan|Angkinang','Hulu Sungai Selatan|Daha Barat','Hulu Sungai Selatan|Daha Selatan','Hulu Sungai Selatan|Daha Utara',
'Hulu Sungai Selatan|Kalumpang','Hulu Sungai Selatan|Kandangan','Hulu Sungai Selatan|Loksado','Hulu Sungai Selatan|Padang Batung',
'Hulu Sungai Selatan|Simpur','Hulu Sungai Selatan|Sungai Raya','Hulu Sungai Selatan|Telaga Langsat',
'Hulu Sungai Tengah|Barabai','Hulu Sungai Tengah|Batang Alai Selatan','Hulu Sungai Tengah|Batang Alai Timur',
'Hulu Sungai Tengah|Batang Alai Utara','Hulu Sungai Tengah|Batu Benawa','Hulu Sungai Tengah|Hantakan','Hulu Sungai Tengah|Haruyan',
'Hulu Sungai Tengah|Labuan Amas Selatan','Hulu Sungai Tengah|Labuan Amas Utara','Hulu Sungai Tengah|Limpasu',
'Hulu Sungai Tengah|Pandawan',
'Hulu Sungai Utara|Amuntai Selatan','Hulu Sungai Utara|Amuntai Tengah','Hulu Sungai Utara|Amuntai Utara',
'Hulu Sungai Utara|Babirik','Hulu Sungai Utara|Banjang','Hulu Sungai Utara|Danau Panggang','Hulu Sungai Utara|Haur Gading',
'Hulu Sungai Utara|Paminggir','Hulu Sungai Utara|Sungai Pandan','Hulu Sungai Utara|Sungai Tabukan',
'Kotabaru|Hampang','Kotabaru|Kelumpang Barat','Kotabaru|Kelumpang Hilir','Kotabaru|Kelumpang Hulu',
'Kotabaru|Kelumpang Selatan','Kotabaru|Kelumpang Tengah','Kotabaru|Kelumpang Utara','Kotabaru|Pamukan Barat',
'Kotabaru|Pamukan Selatan','Kotabaru|Pamukan Utara','Kotabaru|Pulau Laut Barat','Kotabaru|Pulau Laut Kepulauan',
'Kotabaru|Pulau Laut Selatan','Kotabaru|Pulau Laut Sigam','Kotabaru|Pulau Laut Tanjung Selayar',
'Kotabaru|Pulau Laut Tengah','Kotabaru|Pulau Laut Timur','Kotabaru|Pulau Laut Utara','Kotabaru|Pulau Sebuku',
'Kotabaru|Pulau Sembilan','Kotabaru|Sampanahan','Kotabaru|Sungai Durian',
'Tabalong|Banua Lawas','Tabalong|Bintang Ara','Tabalong|Haruai','Tabalong|Jaro','Tabalong|Kelua','Tabalong|Muara Harus',
'Tabalong|Muara Uya','Tabalong|Murung Pudak','Tabalong|Pugaan','Tabalong|Tanjung','Tabalong|Tanta','Tabalong|Upau',
'Tanah Bumbu|Angsana','Tanah Bumbu|Batulicin','Tanah Bumbu|Karang Bintang','Tanah Bumbu|Kuranji','Tanah Bumbu|Kusan Hilir',
'Tanah Bumbu|Kusan Hulu','Tanah Bumbu|Kusan Tengah','Tanah Bumbu|Mantewe','Tanah Bumbu|Satui','Tanah Bumbu|Simpang Empat',
'Tanah Bumbu|Sungai Loban','Tanah Bumbu|Teluk Kepayang',
'Tanah Laut|Bajuin','Tanah Laut|Bati Bati','Tanah Laut|Batu Ampar','Tanah Laut|Bumi Makmur','Tanah Laut|Jorong',
'Tanah Laut|Kintap','Tanah Laut|Kurau','Tanah Laut|Panyipatan','Tanah Laut|Pelaihari','Tanah Laut|Takisung','Tanah Laut|Tambang Ulang',
'Tapin|Bakarangan','Tapin|Binuang','Tapin|Bungur','Tapin|Candi Laras Selatan','Tapin|Candi Laras Utara','Tapin|Hatungun',
'Tapin|Lokpaikat','Tapin|Piani','Tapin|Salam Babaris','Tapin|Tapin Selatan','Tapin|Tapin Tengah','Tapin|Tapin Utara',
'Banjarbaru|Banjar Baru Selatan','Banjarbaru|Banjar Baru Utara','Banjarbaru|Cempaka','Banjarbaru|Landasan Ulin','Banjarbaru|Liang Anggang',
'Banjarmasin|Banjarmasin Barat','Banjarmasin|Banjarmasin Selatan','Banjarmasin|Banjarmasin Tengah','Banjarmasin|Banjarmasin Timur',
'Banjarmasin|Banjarmasin Utara',
], 'Kalimantan Selatan');

// KALIMANTAN TIMUR (10 kab/kota, ~103 kecamatan)
add($f, [
'Berau|Batu Putih','Berau|Biatan','Berau|Biduk-Biduk','Berau|Gunung Tabur','Berau|Kelay','Berau|Maratua',
'Berau|Pulau Derawan','Berau|Sambaliung','Berau|Segah','Berau|Tabalar','Berau|Talisayan','Berau|Tanjung Redeb','Berau|Teluk Bayur',
'Kutai Barat|Barong Tongkok','Kutai Barat|Bentian Besar','Kutai Barat|Bongan','Kutai Barat|Damai','Kutai Barat|Jempang',
'Kutai Barat|Linggang Bigung','Kutai Barat|Long Apari','Kutai Barat|Long Bagun','Kutai Barat|Long Hubung','Kutai Barat|Long Iram',
'Kutai Barat|Long Pahangai','Kutai Barat|Manor Bulatn','Kutai Barat|Melak','Kutai Barat|Muara Lawa','Kutai Barat|Muara Pahu',
'Kutai Barat|Nyuatan','Kutai Barat|Penyinggahan','Kutai Barat|Sekolaq Darat','Kutai Barat|Siluq Ngurai','Kutai Barat|Tering',
'Kutai Kartanegara|Anggana','Kutai Kartanegara|Kembang Janggut','Kutai Kartanegara|Kenohan','Kutai Kartanegara|Kota Bangun',
'Kutai Kartanegara|Loa Janan','Kutai Kartanegara|Loa Kulu','Kutai Kartanegara|Marang Kayu','Kutai Kartanegara|Muara Badak',
'Kutai Kartanegara|Muara Jawa','Kutai Kartanegara|Muara Kaman','Kutai Kartanegara|Muara Muntai','Kutai Kartanegara|Muara Wis',
'Kutai Kartanegara|Samboja','Kutai Kartanegara|Sanga-Sanga','Kutai Kartanegara|Sebulu','Kutai Kartanegara|Tabang',
'Kutai Kartanegara|Tenggarong','Kutai Kartanegara|Tenggarong Seberang',
'Kutai Timur|Batu Ampar','Kutai Timur|Bengalon','Kutai Timur|Busang','Kutai Timur|Kaliorang','Kutai Timur|Karangan',
'Kutai Timur|Kaubun','Kutai Timur|Kongbeng','Kutai Timur|Long Mesangat','Kutai Timur|Muara Ancalong','Kutai Timur|Muara Bengkal',
'Kutai Timur|Muara Wahau','Kutai Timur|Rantau Pulung','Kutai Timur|Sandaran','Kutai Timur|Sangkulirang',
'Kutai Timur|Sangatta Selatan','Kutai Timur|Sangatta Utara','Kutai Timur|Telen','Kutai Timur|Teluk Pandan',
'Mahakam Ulu|Laham','Mahakam Ulu|Long Apari','Mahakam Ulu|Long Bagun','Mahakam Ulu|Long Hubung','Mahakam Ulu|Long Pahangai',
'Paser|Batu Engau','Paser|Batu Sopang','Paser|Kuaro','Paser|Long Ikis','Paser|Long Kali','Paser|Muara Komam','Paser|Muara Samu',
'Paser|Pasir Belengkong','Paser|Tanah Grogot','Paser|Tanjung Harapan',
'Penajam Paser Utara|Babulu','Penajam Paser Utara|Penajam','Penajam Paser Utara|Sepaku','Penajam Paser Utara|Waru',
'Balikpapan|Balikpapan Barat','Balikpapan|Balikpapan Kota','Balikpapan|Balikpapan Selatan','Balikpapan|Balikpapan Tengah',
'Balikpapan|Balikpapan Timur','Balikpapan|Balikpapan Utara',
'Bontang|Bontang Barat','Bontang|Bontang Selatan','Bontang|Bontang Utara',
'Samarinda|Loa Janan Ilir','Samarinda|Palaran','Samarinda|Samarinda Ilir','Samarinda|Samarinda Kota','Samarinda|Samarinda Seberang',
'Samarinda|Samarinda Ulu','Samarinda|Samarinda Utara','Samarinda|Sambutan','Samarinda|Sungai Kunjang','Samarinda|Sungai Pinang',
], 'Kalimantan Timur');

// KALIMANTAN UTARA (5 kab/kota, ~55 kecamatan)
add($f, [
'Bulungan|Peso','Bulungan|Peso Hilir','Bulungan|Sekatak','Bulungan|Tanjung Palas','Bulungan|Tanjung Palas Barat',
'Bulungan|Tanjung Palas Tengah','Bulungan|Tanjung Palas Timur','Bulungan|Tanjung Palas Utara','Bulungan|Tanjung Selor',
'Malinau|Bahau Hulu','Malinau|Kayan Hilir','Malinau|Kayan Hulu','Malinau|Kayan Selatan','Malinau|Malinau Barat',
'Malinau|Malinau Kota','Malinau|Malinau Selatan','Malinau|Malinau Selatan Hilir','Malinau|Malinau Selatan Hulu',
'Malinau|Malinau Utara','Malinau|Mentarang','Malinau|Mentarang Hulu','Malinau|Pujungan','Malinau|Sungai Boh',
'Malinau|Sungai Tubu',
'Nunukan|Krayan','Nunukan|Krayan Barat','Nunukan|Krayan Selatan','Nunukan|Krayan Tengah','Nunukan|Krayan Timur',
'Nunukan|Lumbis','Nunukan|Lumbis Hulu','Nunukan|Lumbis Ogong','Nunukan|Nunukan','Nunukan|Nunukan Selatan',
'Nunukan|Sebatik','Nunukan|Sebatik Barat','Nunukan|Sebatik Tengah','Nunukan|Sebatik Timur','Nunukan|Sebatik Utara',
'Nunukan|Sebuku','Nunukan|Sei Menggaris','Nunukan|Sembakung','Nunukan|Sembakung Atulai','Nunukan|Tulin Onsoi',
'Tana Tidung|Betayau','Tana Tidung|Muruk Rian','Tana Tidung|Sesayap','Tana Tidung|Sesayap Hilir','Tana Tidung|Tana Lia',
'Tarakan|Tarakan Barat','Tarakan|Tarakan Tengah','Tarakan|Tarakan Timur','Tarakan|Tarakan Utara',
], 'Kalimantan Utara');

echo "All Kalimantan done.\n";
