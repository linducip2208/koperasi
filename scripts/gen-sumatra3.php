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

// BENGKULU (10 kab/kota, ~129 kecamatan)
add($f, [
'Bengkulu Selatan|Air Nipis','Bengkulu Selatan|Bunga Mas','Bengkulu Selatan|Kedurang','Bengkulu Selatan|Kedurang Ilir',
'Bengkulu Selatan|Kota Manna','Bengkulu Selatan|Manna','Bengkulu Selatan|Pasar Manna','Bengkulu Selatan|Pino',
'Bengkulu Selatan|Pino Raya','Bengkulu Selatan|Seginim','Bengkulu Selatan|Ulu Manna',
'Bengkulu Tengah|Bang Haji','Bengkulu Tengah|Karang Tinggi','Bengkulu Tengah|Merigi Kelindang','Bengkulu Tengah|Merigi Sakti',
'Bengkulu Tengah|Pagar Jati','Bengkulu Tengah|Pematang Tiga','Bengkulu Tengah|Pondok Kelapa','Bengkulu Tengah|Pondok Kubang',
'Bengkulu Tengah|Taba Penanjung','Bengkulu Tengah|Talang Empat',
'Bengkulu Utara|Air Besi','Bengkulu Utara|Air Napal','Bengkulu Utara|Air Padang','Bengkulu Utara|Arga Makmur',
'Bengkulu Utara|Batik Nau','Bengkulu Utara|Enggano','Bengkulu Utara|Giri Mulya','Bengkulu Utara|Hulu Palik',
'Bengkulu Utara|Kerkap','Bengkulu Utara|Ketahun','Bengkulu Utara|Lais','Bengkulu Utara|Marga Sakti Sebelat',
'Bengkulu Utara|Napal Putih','Bengkulu Utara|Padang Jaya','Bengkulu Utara|Pinang Raya','Bengkulu Utara|Putri Hijau',
'Bengkulu Utara|Tanjung Agung Palik','Bengkulu Utara|Ulok Kupai',
'Kaur|Kaur Selatan','Kaur|Kaur Tengah','Kaur|Kaur Utara','Kaur|Kelam Tengah','Kaur|Kinal','Kaur|Luas','Kaur|Lungkang Kule',
'Kaur|Maje','Kaur|Muara Sahung','Kaur|Nasal','Kaur|Padang Guci Hilir','Kaur|Padang Guci Hulu','Kaur|Semidang Gumay',
'Kaur|Tanjung Kemuning','Kaur|Tetap',
'Kepahiang|Bermani Ilir','Kepahiang|Kebawetan','Kepahiang|Kepahiang','Kepahiang|Merigi','Kepahiang|Muara Kemumu',
'Kepahiang|Seberang Musi','Kepahiang|Tebat Karai','Kepahiang|Ujan Mas',
'Lebong|Amen','Lebong|Bingin Kuning','Lebong|Lebong Atas','Lebong|Lebong Sakti','Lebong|Lebong Selatan','Lebong|Lebong Tengah',
'Lebong|Lebong Utara','Lebong|Pinang Belapis','Lebong|Rimbo Pengadang','Lebong|Topos','Lebong|Tubei','Lebong|Uram Jaya',
'Mukomuko|Air Dikit','Mukomuko|Air Majunto','Mukomuko|Air Rami','Mukomuko|Ipuh','Mukomuko|Kota Mukomuko',
'Mukomuko|Lubuk Pinang','Mukomuko|Malin Deman','Mukomuko|Penarik','Mukomuko|Pondok Suguh','Mukomuko|Selagan Raya',
'Mukomuko|Sungai Rumbai','Mukomuko|Teramang Jaya','Mukomuko|Teras Terunjam','Mukomuko|V Koto',
'Rejang Lebong|Bermani Ulu','Rejang Lebong|Bermani Ulu Raya','Rejang Lebong|Binduriang','Rejang Lebong|Curup',
'Rejang Lebong|Curup Selatan','Rejang Lebong|Curup Tengah','Rejang Lebong|Curup Timur','Rejang Lebong|Curup Utara',
'Rejang Lebong|Kota Padang','Rejang Lebong|Padang Ulak Tanding','Rejang Lebong|Selupu Rejang',
'Rejang Lebong|Sindang Beliti Ilir','Rejang Lebong|Sindang Beliti Ulu','Rejang Lebong|Sindang Dataran','Rejang Lebong|Sindang Kelingi',
'Seluma|Air Periukan','Seluma|Ilir Talo','Seluma|Lubuk Sandi','Seluma|Seluma','Seluma|Seluma Barat','Seluma|Seluma Selatan',
'Seluma|Seluma Timur','Seluma|Seluma Utara','Seluma|Semidang Alas','Seluma|Semidang Alas Maras','Seluma|Sukaraja',
'Seluma|Talo','Seluma|Talo Kecil','Seluma|Ulu Talo',
'Bengkulu Kota|Gading Cempaka','Bengkulu Kota|Kampung Melayu','Bengkulu Kota|Muara Bangka Hulu','Bengkulu Kota|Ratu Agung',
'Bengkulu Kota|Ratu Samban','Bengkulu Kota|Selebar','Bengkulu Kota|Singaran Pati','Bengkulu Kota|Sungai Serut','Bengkulu Kota|Teluk Segara',
], 'Bengkulu');

// LAMPUNG (15 kab/kota, ~228 kecamatan)
add($f, [
'Lampung Barat|Air Hitam','Lampung Barat|Balik Bukit','Lampung Barat|Bandar Negeri Suoh','Lampung Barat|Batu Brak',
'Lampung Barat|Batu Ketulis','Lampung Barat|Belalau','Lampung Barat|Gedung Surian','Lampung Barat|Kebun Tebu',
'Lampung Barat|Lumbok Seminung','Lampung Barat|Pagar Dewa','Lampung Barat|Sekincau','Lampung Barat|Sukau',
'Lampung Barat|Sumber Jaya','Lampung Barat|Suoh','Lampung Barat|Way Tenong',
'Lampung Selatan|Bakauheni','Lampung Selatan|Candipuro','Lampung Selatan|Jati Agung','Lampung Selatan|Kalianda',
'Lampung Selatan|Katibung','Lampung Selatan|Ketapang','Lampung Selatan|Merbau Mataram','Lampung Selatan|Natar',
'Lampung Selatan|Palas','Lampung Selatan|Penengahan','Lampung Selatan|Rajabasa','Lampung Selatan|Sidomulyo',
'Lampung Selatan|Sragi','Lampung Selatan|Tanjung Bintang','Lampung Selatan|Tanjung Sari','Lampung Selatan|Way Panji',
'Lampung Selatan|Way Sulan',
'Lampung Tengah|Anak Ratu Aji','Lampung Tengah|Anak Tuha','Lampung Tengah|Bandar Mataram','Lampung Tengah|Bandar Surabaya',
'Lampung Tengah|Bangun Rejo','Lampung Tengah|Bekri','Lampung Tengah|Bumi Nabung','Lampung Tengah|Bumi Ratu Nuban',
'Lampung Tengah|Gunung Sugih','Lampung Tengah|Kalirejo','Lampung Tengah|Kota Gajah','Lampung Tengah|Padang Ratu',
'Lampung Tengah|Pubian','Lampung Tengah|Punggur','Lampung Tengah|Putra Rumbia','Lampung Tengah|Rumbia',
'Lampung Tengah|Selagai Lingga','Lampung Tengah|Sendang Agung','Lampung Tengah|Seputih Agung','Lampung Tengah|Seputih Banyak',
'Lampung Tengah|Seputih Mataram','Lampung Tengah|Seputih Raman','Lampung Tengah|Seputih Surabaya','Lampung Tengah|Terbanggi Besar',
'Lampung Tengah|Terusan Nunyai','Lampung Tengah|Trimurjo','Lampung Tengah|Way Pangubuan','Lampung Tengah|Way Seputih',
'Lampung Utara|Abung Barat','Lampung Utara|Abung Kunang','Lampung Utara|Abung Pekurun','Lampung Utara|Abung Selatan',
'Lampung Utara|Abung Semuli','Lampung Utara|Abung Surakarta','Lampung Utara|Abung Tengah','Lampung Utara|Abung Timur',
'Lampung Utara|Abung Tinggi','Lampung Utara|Blambangan Pagar','Lampung Utara|Bukit Kemuning','Lampung Utara|Bunga Mayang',
'Lampung Utara|Hulu Sungkai','Lampung Utara|Kotabumi','Lampung Utara|Kotabumi Selatan','Lampung Utara|Kotabumi Utara',
'Lampung Utara|Muara Sungkai','Lampung Utara|Sungkai Barat','Lampung Utara|Sungkai Jaya','Lampung Utara|Sungkai Selatan',
'Lampung Utara|Sungkai Tengah','Lampung Utara|Sungkai Utara','Lampung Utara|Tanjung Raja',
'Lampung Timur|Bandar Sribawono','Lampung Timur|Batanghari','Lampung Timur|Batanghari Nuban','Lampung Timur|Braja Selebah',
'Lampung Timur|Bumi Agung','Lampung Timur|Gunung Pelindung','Lampung Timur|Jabung','Lampung Timur|Labuhan Maringgai',
'Lampung Timur|Labuhan Ratu','Lampung Timur|Marga Sekampung','Lampung Timur|Margatiga','Lampung Timur|Mataram Baru',
'Lampung Timur|Melinting','Lampung Timur|Metro Kibang','Lampung Timur|Pasir Sakti','Lampung Timur|Pekalongan',
'Lampung Timur|Purbolinggo','Lampung Timur|Raman Utara','Lampung Timur|Sekampung','Lampung Timur|Sekampung Udik',
'Lampung Timur|Sukadana','Lampung Timur|Waway Karya','Lampung Timur|Way Bungur','Lampung Timur|Way Jepara',
'Mesuji|Mesuji','Mesuji|Mesuji Timur','Mesuji|Panca Jaya','Mesuji|Rawa Jitu Utara','Mesuji|Simpang Pematang',
'Mesuji|Tanjung Raya','Mesuji|Way Serdang',
'Pesawaran|Gedong Tataan','Pesawaran|Kedondong','Pesawaran|Marga Punduh','Pesawaran|Negeri Katon','Pesawaran|Padang Cermin',
'Pesawaran|Punduh Pidada','Pesawaran|Tegineneng','Pesawaran|Teluk Pandan','Pesawaran|Way Khilau','Pesawaran|Way Lima',
'Pesawaran|Way Ratai',
'Pesisir Barat|Bangkuang','Pesisir Barat|Karya Penggawa','Pesisir Barat|Krui Selatan','Pesisir Barat|Lemong',
'Pesisir Barat|Ngambur','Pesisir Barat|Ngaras','Pesisir Barat|Pesisir Selatan','Pesisir Barat|Pesisir Tengah',
'Pesisir Barat|Pesisir Utara','Pesisir Barat|Pulau Pisang','Pesisir Barat|Way Krui',
'Pringsewu|Adiluwih','Pringsewu|Ambarawa','Pringsewu|Banyumas','Pringsewu|Gading Rejo','Pringsewu|Pagelaran',
'Pringsewu|Pagelaran Utara','Pringsewu|Pardasuka','Pringsewu|Pringsewu','Pringsewu|Sukoharjo',
'Tanggamus|Air Naningan','Tanggamus|Bandar Negeri Semuong','Tanggamus|Bulok','Tanggamus|Cukuh Balak',
'Tanggamus|Gisting','Tanggamus|Gunung Alip','Tanggamus|Kelumbayan','Tanggamus|Kelumbayan Barat','Tanggamus|Kota Agung',
'Tanggamus|Kota Agung Barat','Tanggamus|Kota Agung Timur','Tanggamus|Limau','Tanggamus|Pematang Sawa','Tanggamus|Pugung',
'Tanggamus|Pulau Panggung','Tanggamus|Semaka','Tanggamus|Sumberejo','Tanggamus|Talang Padang','Tanggamus|Ulubelu',
'Tanggamus|Wonosobo',
'Tulang Bawang|Banjar Agung','Tulang Bawang|Banjar Baru','Tulang Bawang|Banjar Margo','Tulang Bawang|Dente Teladas',
'Tulang Bawang|Gedung Aji','Tulang Bawang|Gedung Aji Baru','Tulang Bawang|Gedung Meneng','Tulang Bawang|Menggala',
'Tulang Bawang|Menggala Timur','Tulang Bawang|Meraksa Aji','Tulang Bawang|Penawar Aji','Tulang Bawang|Penawar Tama',
'Tulang Bawang|Rawa Pitu','Tulang Bawang|Rawajitu Selatan','Tulang Bawang|Rawajitu Timur',
'Tulang Bawang Barat|Batu Putih','Tulang Bawang Barat|Gunung Agung','Tulang Bawang Barat|Gunung Terang',
'Tulang Bawang Barat|Lambu Kibang','Tulang Bawang Barat|Pagar Dewa','Tulang Bawang Barat|Tulang Bawang Tengah',
'Tulang Bawang Barat|Tulang Bawang Udik','Tulang Bawang Barat|Tumijajar','Tulang Bawang Barat|Way Kenanga',
'Way Kanan|Bahuga','Way Kanan|Banjit','Way Kanan|Baradatu','Way Kanan|Blambangan Umpu','Way Kanan|Buay Bahuga',
'Way Kanan|Bumi Agung','Way Kanan|Gunung Labuhan','Way Kanan|Kasui','Way Kanan|Negara Batin','Way Kanan|Negeri Agung',
'Way Kanan|Negeri Besar','Way Kanan|Pakuan Ratu','Way Kanan|Rebang Tangkas','Way Kanan|Way Tuba',
'Bandar Lampung|Bumi Waras','Bandar Lampung|Enggal','Bandar Lampung|Kedamaian','Bandar Lampung|Kedaton',
'Bandar Lampung|Kemiling','Bandar Lampung|Labuhan Ratu','Bandar Lampung|Langkapura','Bandar Lampung|Panjang',
'Bandar Lampung|Rajabasa','Bandar Lampung|Sukabumi','Bandar Lampung|Sukarame','Bandar Lampung|Tanjung Karang Barat',
'Bandar Lampung|Tanjung Karang Pusat','Bandar Lampung|Tanjung Karang Timur','Bandar Lampung|Tanjung Senang',
'Bandar Lampung|Teluk Betung Barat','Bandar Lampung|Teluk Betung Selatan','Bandar Lampung|Teluk Betung Timur',
'Bandar Lampung|Teluk Betung Utara','Bandar Lampung|Way Halim',
'Metro|Metro Barat','Metro|Metro Pusat','Metro|Metro Selatan','Metro|Metro Timur','Metro|Metro Utara',
], 'Lampung');

// KEPULAUAN RIAU (7 kab/kota, ~76 kecamatan)
add($f, [
'Bintan|Bintan Pesisir','Bintan|Bintan Timur','Bintan|Bintan Utara','Bintan|Gunung Kijang','Bintan|Mantang',
'Bintan|Seri Kuala Lobam','Bintan|Tambelan','Bintan|Teluk Bintan','Bintan|Teluk Sebong','Bintan|Toapaya',
'Karimun|Belat','Karimun|Buru','Karimun|Durai','Karimun|Karimun','Karimun|Kundur','Karimun|Kundur Barat',
'Karimun|Kundur Utara','Karimun|Meral','Karimun|Meral Barat','Karimun|Moro','Karimun|Tebing','Karimun|Ungar',
'Kepulauan Anambas|Jemaja','Kepulauan Anambas|Jemaja Barat','Kepulauan Anambas|Jemaja Timur',
'Kepulauan Anambas|Palmatak','Kepulauan Anambas|Siantan','Kepulauan Anambas|Siantan Selatan',
'Kepulauan Anambas|Siantan Tengah','Kepulauan Anambas|Siantan Timur','Kepulauan Anambas|Siantan Utara',
'Lingga|Bakung Serumpun','Lingga|Katang Bidare','Lingga|Lingga','Lingga|Lingga Timur','Lingga|Lingga Utara',
'Lingga|Selayar','Lingga|Senayang','Lingga|Singkep','Lingga|Singkep Barat','Lingga|Singkep Pesisir','Lingga|Singkep Selatan',
'Lingga|Temiang Pesisir',
'Natuna|Bunguran Barat','Natuna|Bunguran Batubi','Natuna|Bunguran Selatan','Natuna|Bunguran Tengah',
'Natuna|Bunguran Timur','Natuna|Bunguran Timur Laut','Natuna|Bunguran Utara','Natuna|Midai','Natuna|Pulau Laut',
'Natuna|Pulau Panjang','Natuna|Pulau Seluan','Natuna|Pulau Tiga','Natuna|Serasan','Natuna|Serasan Timur',
'Natuna|Suak Midai','Natuna|Subi',
'Batam|Batam Kota','Batam|Batu Aji','Batam|Batu Ampar','Batam|Belakang Padang','Batam|Bengkong','Batam|Bulang',
'Batam|Galang','Batam|Lubuk Baja','Batam|Nongsa','Batam|Sagulung','Batam|Sei Beduk','Batam|Sekupang',
'Tanjung Pinang|Bukit Bestari','Tanjung Pinang|Tanjung Pinang Barat','Tanjung Pinang|Tanjung Pinang Kota',
'Tanjung Pinang|Tanjung Pinang Timur',
], 'Kepulauan Riau');

// BANGKA BELITUNG (7 kab/kota, ~47 kecamatan)
add($f, [
'Bangka|Bakam','Bangka|Belinyu','Bangka|Mendo Barat','Bangka|Merawang','Bangka|Pemali','Bangka|Puding Besar',
'Bangka|Riau Silip','Bangka|Sungai Liat',
'Bangka Barat|Jebus','Bangka Barat|Kelapa','Bangka Barat|Mentok','Bangka Barat|Parittiga','Bangka Barat|Simpang Teritip',
'Bangka Barat|Tempilang',
'Bangka Selatan|Air Gegas','Bangka Selatan|Kepulauan Pongok','Bangka Selatan|Lepar','Bangka Selatan|Lepar Pongok',
'Bangka Selatan|Payung','Bangka Selatan|Pulau Besar','Bangka Selatan|Simpang Rimba','Bangka Selatan|Toboali',
'Bangka Selatan|Tukak Sadai',
'Bangka Tengah|Koba','Bangka Tengah|Lubuk Besar','Bangka Tengah|Namang','Bangka Tengah|Pangkalan Baru',
'Bangka Tengah|Simpang Katis','Bangka Tengah|Sungai Selan',
'Belitung|Badau','Belitung|Membalong','Belitung|Selat Nasik','Belitung|Sijuk','Belitung|Tanjung Pandan',
'Belitung Timur|Dendang','Belitung Timur|Gantung','Belitung Timur|Kelapa Kampit','Belitung Timur|Manggar',
'Belitung Timur|Simpang Pesak','Belitung Timur|Simpang Renggiang',
'Pangkal Pinang|Bukit Intan','Pangkal Pinang|Gabek','Pangkal Pinang|Gerunggang','Pangkal Pinang|Girimaya',
'Pangkal Pinang|Pangkal Balam','Pangkal Pinang|Rangkui','Pangkal Pinang|Taman Sari',
], 'Bangka Belitung');

// DKI JAKARTA (6 kab/kota, 44 kecamatan)
add($f, [
'Jakarta Barat|Cengkareng','Jakarta Barat|Grogol Petamburan','Jakarta Barat|Kalideres','Jakarta Barat|Kebon Jeruk',
'Jakarta Barat|Kembangan','Jakarta Barat|Palmerah','Jakarta Barat|Taman Sari','Jakarta Barat|Tambora',
'Jakarta Pusat|Cempaka Putih','Jakarta Pusat|Gambir','Jakarta Pusat|Johar Baru','Jakarta Pusat|Kemayoran',
'Jakarta Pusat|Menteng','Jakarta Pusat|Sawah Besar','Jakarta Pusat|Senen','Jakarta Pusat|Tanah Abang',
'Jakarta Selatan|Cilandak','Jakarta Selatan|Jagakarsa','Jakarta Selatan|Kebayoran Baru','Jakarta Selatan|Kebayoran Lama',
'Jakarta Selatan|Mampang Prapatan','Jakarta Selatan|Pancoran','Jakarta Selatan|Pasar Minggu','Jakarta Selatan|Pesanggrahan',
'Jakarta Selatan|Setiabudi','Jakarta Selatan|Tebet',
'Jakarta Timur|Cakung','Jakarta Timur|Cipayung','Jakarta Timur|Ciracas','Jakarta Timur|Duren Sawit',
'Jakarta Timur|Jatinegara','Jakarta Timur|Kramat Jati','Jakarta Timur|Makasar','Jakarta Timur|Matraman',
'Jakarta Timur|Pasar Rebo','Jakarta Timur|Pulo Gadung',
'Jakarta Utara|Cilincing','Jakarta Utara|Kelapa Gading','Jakarta Utara|Koja','Jakarta Utara|Pademangan',
'Jakarta Utara|Penjaringan','Jakarta Utara|Tanjung Priok',
'Kepulauan Seribu|Kepulauan Seribu Selatan','Kepulauan Seribu|Kepulauan Seribu Utara',
], 'DKI Jakarta');

echo "Bengkulu, Lampung, Kepri, Babel, DKI done.\n";
