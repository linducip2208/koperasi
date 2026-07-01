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

// GORONTALO (6 kab/kota, ~77 kecamatan)
add($f, [
'Boalemo|Botumoito','Boalemo|Dulupi','Boalemo|Mananggu','Boalemo|Paguyaman','Boalemo|Paguyaman Pantai','Boalemo|Tilamuta','Boalemo|Wonosari',
'Bone Bolango|Bonepantai','Bone Bolango|Bone','Bone Bolango|Bone Raya','Bone Bolango|Botupingge','Bone Bolango|Bulango Selatan',
'Bone Bolango|Bulango Timur','Bone Bolango|Bulango Ulu','Bone Bolango|Bulango Utara','Bone Bolango|Bulawa',
'Bone Bolango|Kabila','Bone Bolango|Kabila Bone','Bone Bolango|Pinogu','Bone Bolango|Suwawa','Bone Bolango|Suwawa Selatan',
'Bone Bolango|Suwawa Tengah','Bone Bolango|Suwawa Timur','Bone Bolango|Tapa','Bone Bolango|Tilongkabila',
'Gorontalo|Asparaga','Gorontalo|Batudaa','Gorontalo|Batudaa Pantai','Gorontalo|Bilato','Gorontalo|Biluhu',
'Gorontalo|Boliyohuto','Gorontalo|Bongomeme','Gorontalo|Dungaliyo','Gorontalo|Limboto','Gorontalo|Limboto Barat',
'Gorontalo|Mootilango','Gorontalo|Pulubala','Gorontalo|Tabongo','Gorontalo|Telaga','Gorontalo|Telaga Biru',
'Gorontalo|Telaga Jaya','Gorontalo|Tibawa','Gorontalo|Tilango','Gorontalo|Tolangohula',
'Gorontalo Utara|Anggrek','Gorontalo Utara|Atinggola','Gorontalo Utara|Biau','Gorontalo Utara|Gentuma Raya',
'Gorontalo Utara|Kwandang','Gorontalo Utara|Monano','Gorontalo Utara|Ponelo Kepulauan','Gorontalo Utara|Sumalata',
'Gorontalo Utara|Sumalata Timur','Gorontalo Utara|Tolinggula','Gorontalo Utara|Tomilito',
'Pohuwato|Buntulia','Pohuwato|Dengilo','Pohuwato|Duhiadaa','Pohuwato|Lemito','Pohuwato|Marisa','Pohuwato|Paguat',
'Pohuwato|Patilanggio','Pohuwato|Popayato','Pohuwato|Popayato Barat','Pohuwato|Popayato Timur','Pohuwato|Randangan',
'Pohuwato|Taluditi','Pohuwato|Wanggarasi',
'Gorontalo Kota|Dumbo Raya','Gorontalo Kota|Dungingi','Gorontalo Kota|Hulonthalangi','Gorontalo Kota|Kota Barat',
'Gorontalo Kota|Kota Selatan','Gorontalo Kota|Kota Tengah','Gorontalo Kota|Kota Timur','Gorontalo Kota|Kota Utara','Gorontalo Kota|Sipatana',
], 'Gorontalo');

// SULAWESI BARAT (6 kab/kota, ~69 kecamatan)
add($f, [
'Majene|Banggae','Majene|Banggae Timur','Majene|Malunda','Majene|Pamboang','Majene|Sendana','Majene|Tammerodo Sendana',
'Majene|Tubo Sendana','Majene|Ulumanda',
'Mamasa|Aralle','Mamasa|Balla','Mamasa|Bambang','Mamasa|Buntumalangka','Mamasa|Mamasa','Mamasa|Mambi','Mamasa|Mehalaan',
'Mamasa|Messawa','Mamasa|Nosu','Mamasa|Pana','Mamasa|Rantebulahan Timur','Mamasa|Sesena Padang','Mamasa|Sumarorong',
'Mamasa|Tabang','Mamasa|Tabulahan','Mamasa|Tanduk Kalua','Mamasa|Tawalian',
'Mamuju|Bonehau','Mamuju|Kalukku','Mamuju|Kalumpang','Mamuju|Kepulauan Balabalakang','Mamuju|Mamuju','Mamuju|Papalang',
'Mamuju|Sampaga','Mamuju|Simboro dan Kepulauan','Mamuju|Tapalang','Mamuju|Tapalang Barat','Mamuju|Tommo',
'Mamuju Tengah|Budong-Budong','Mamuju Tengah|Karossa','Mamuju Tengah|Pangale','Mamuju Tengah|Topoyo','Mamuju Tengah|Tobadak',
'Pasangkayu|Bambaira','Pasangkayu|Bambalamotu','Pasangkayu|Baras','Pasangkayu|Bulu Taba','Pasangkayu|Dapurang',
'Pasangkayu|Duripoku','Pasangkayu|Lariang','Pasangkayu|Pasangkayu','Pasangkayu|Pedongga','Pasangkayu|Sarjo','Pasangkayu|Sarudu',
'Pasangkayu|Tikke Raya',
'Polewali Mandar|Allu','Polewali Mandar|Anreapi','Polewali Mandar|Balanipa','Polewali Mandar|Binuang','Polewali Mandar|Bulo',
'Polewali Mandar|Campalagian','Polewali Mandar|Limboro','Polewali Mandar|Luyo','Polewali Mandar|Mapilli','Polewali Mandar|Matakali',
'Polewali Mandar|Matangnga','Polewali Mandar|Polewali','Polewali Mandar|Tapango','Polewali Mandar|Tinambung',
'Polewali Mandar|Tubbi Taramanu','Polewali Mandar|Wonomulyo',
], 'Sulawesi Barat');

// MALUKU (11 kab/kota, ~118 kecamatan)
add($f, [
'Buru|Air Buaya','Buru|Batabual','Buru|Fena Leisela','Buru|Lilialy','Buru|Lolong Guba','Buru|Namlea','Buru|Teluk Kaiely',
'Buru|Waeapo','Buru|Waelata','Buru|Waplau',
'Buru Selatan|Ambalau','Buru Selatan|Fena Fafan','Buru Selatan|Kepala Madan','Buru Selatan|Leksula','Buru Selatan|Namrole',
'Buru Selatan|Waesama',
'Kepulauan Aru|Aru Selatan','Kepulauan Aru|Aru Selatan Timur','Kepulauan Aru|Aru Selatan Utara','Kepulauan Aru|Aru Tengah',
'Kepulauan Aru|Aru Tengah Selatan','Kepulauan Aru|Aru Tengah Timur','Kepulauan Aru|Aru Utara','Kepulauan Aru|Aru Utara Timur Batuley',
'Kepulauan Aru|Pulau-Pulau Aru','Kepulauan Aru|Sir-Sir',
'Kepulauan Tanimbar|Fordata','Kepulauan Tanimbar|Kormomolin','Kepulauan Tanimbar|Molu Maru','Kepulauan Tanimbar|Nirunmas',
'Kepulauan Tanimbar|Selaru','Kepulauan Tanimbar|Tanimbar Selatan','Kepulauan Tanimbar|Tanimbar Utara','Kepulauan Tanimbar|Wer Maktian',
'Kepulauan Tanimbar|Wer Tamrian','Kepulauan Tanimbar|Wuar Labobar','Kepulauan Tanimbar|Yaru',
'Maluku Barat Daya|Damer','Maluku Barat Daya|Kisar Selatan','Maluku Barat Daya|Kisar Utara','Maluku Barat Daya|Lakor',
'Maluku Barat Daya|Letti','Maluku Barat Daya|Moa Lakor','Maluku Barat Daya|Pulau Lakor','Maluku Barat Daya|Pulau Letti',
'Maluku Barat Daya|Pulau Masela','Maluku Barat Daya|Pulau Wetang','Maluku Barat Daya|Pulau-Pulau Babar',
'Maluku Barat Daya|Pulau-Pulau Babar Timur','Maluku Barat Daya|Pulau-Pulau Terselatan','Maluku Barat Daya|Wetar',
'Maluku Barat Daya|Wetar Barat','Maluku Barat Daya|Wetar Timur','Maluku Barat Daya|Wetar Utara',
'Maluku Tengah|Amahai','Maluku Tengah|Banda','Maluku Tengah|Kota Masohi','Maluku Tengah|Leihitu','Maluku Tengah|Leihitu Barat',
'Maluku Tengah|Nusalaut','Maluku Tengah|Pulau Haruku','Maluku Tengah|Salahutu','Maluku Tengah|Saparua','Maluku Tengah|Saparua Timur',
'Maluku Tengah|Seram Utara','Maluku Tengah|Seram Utara Barat','Maluku Tengah|Seram Utara Timur Kobi','Maluku Tengah|Seram Utara Timur Seti',
'Maluku Tengah|Tehoru','Maluku Tengah|Teluk Elpaputih','Maluku Tengah|Telutih','Maluku Tengah|Teon Nila Serua',
'Maluku Tenggara|Hoat Sorbay','Maluku Tenggara|Kei Besar','Maluku Tenggara|Kei Besar Selatan','Maluku Tenggara|Kei Besar Utara Barat',
'Maluku Tenggara|Kei Besar Utara Timur','Maluku Tenggara|Kei Kecil','Maluku Tenggara|Kei Kecil Barat','Maluku Tenggara|Kei Kecil Timur',
'Maluku Tenggara|Kei Kecil Timur Selatan','Maluku Tenggara|Manyeuw',
'Seram Bagian Barat|Amalatu','Seram Bagian Barat|Elpaputih','Seram Bagian Barat|Huamual','Seram Bagian Barat|Huamual Belakang',
'Seram Bagian Barat|Inamosol','Seram Bagian Barat|Kairatu','Seram Bagian Barat|Kairatu Barat','Seram Bagian Barat|Kepulauan Manipa',
'Seram Bagian Barat|Piru','Seram Bagian Barat|Seram Barat','Seram Bagian Barat|Taniwel','Seram Bagian Barat|Taniwel Timur',
'Seram Bagian Timur|Bula','Seram Bagian Timur|Bula Barat','Seram Bagian Timur|Gorom Timur','Seram Bagian Timur|Kian Darat',
'Seram Bagian Timur|Kilmury','Seram Bagian Timur|Pulau Gorom','Seram Bagian Timur|Pulau Panjang','Seram Bagian Timur|Seram Timur',
'Seram Bagian Timur|Siritaun Wida Timur','Seram Bagian Timur|Siwalalat','Seram Bagian Timur|Teluk Waru','Seram Bagian Timur|Tutuk Tolu',
'Seram Bagian Timur|Wakate','Seram Bagian Timur|Werinama',
'Ambon|Baguala','Ambon|Leitimur Selatan','Ambon|Nusaniwe','Ambon|Sirimau','Ambon|Teluk Ambon',
'Tual|Kur Selatan','Tual|Pulau Dullah Selatan','Tual|Pulau Dullah Utara','Tual|Pulau Tayando Tam','Tual|Pulau-Pulau Kur',
], 'Maluku');

// MALUKU UTARA (10 kab/kota, ~115 kecamatan)
add($f, [
'Halmahera Barat|Ibu','Halmahera Barat|Ibu Selatan','Halmahera Barat|Jailolo','Halmahera Barat|Jailolo Selatan',
'Halmahera Barat|Loloda','Halmahera Barat|Sahu','Halmahera Barat|Sahu Timur','Halmahera Barat|Tabaru',
'Halmahera Selatan|Bacan','Halmahera Selatan|Bacan Barat','Halmahera Selatan|Bacan Barat Utara','Halmahera Selatan|Bacan Selatan',
'Halmahera Selatan|Bacan Timur','Halmahera Selatan|Bacan Timur Selatan','Halmahera Selatan|Bacan Timur Tengah',
'Halmahera Selatan|Gane Barat','Halmahera Selatan|Gane Barat Selatan','Halmahera Selatan|Gane Barat Utara','Halmahera Selatan|Gane Timur',
'Halmahera Selatan|Gane Timur Selatan','Halmahera Selatan|Gane Timur Tengah','Halmahera Selatan|Kasiruta Barat',
'Halmahera Selatan|Kasiruta Timur','Halmahera Selatan|Kayoa','Halmahera Selatan|Kayoa Barat','Halmahera Selatan|Kayoa Selatan',
'Halmahera Selatan|Kayoa Utara','Halmahera Selatan|Kepulauan Botanglomang','Halmahera Selatan|Kepulauan Joronga',
'Halmahera Selatan|Mandioli Selatan','Halmahera Selatan|Mandioli Utara','Halmahera Selatan|Obi','Halmahera Selatan|Obi Barat',
'Halmahera Selatan|Obi Selatan','Halmahera Selatan|Obi Timur','Halmahera Selatan|Obi Utara',
'Halmahera Tengah|Patani','Halmahera Tengah|Patani Barat','Halmahera Tengah|Patani Timur','Halmahera Tengah|Patani Utara',
'Halmahera Tengah|Pulau Gebe','Halmahera Tengah|Weda','Halmahera Tengah|Weda Selatan','Halmahera Tengah|Weda Tengah','Halmahera Tengah|Weda Timur',
'Halmahera Timur|Kota Maba','Halmahera Timur|Maba','Halmahera Timur|Maba Selatan','Halmahera Timur|Maba Tengah','Halmahera Timur|Maba Utara',
'Halmahera Timur|Wasile','Halmahera Timur|Wasile Selatan','Halmahera Timur|Wasile Tengah','Halmahera Timur|Wasile Timur','Halmahera Timur|Wasile Utara',
'Halmahera Utara|Galela','Halmahera Utara|Galela Barat','Halmahera Utara|Galela Selatan','Halmahera Utara|Galela Utara',
'Halmahera Utara|Kao','Halmahera Utara|Kao Barat','Halmahera Utara|Kao Teluk','Halmahera Utara|Kao Utara','Halmahera Utara|Loloda Kepulauan',
'Halmahera Utara|Loloda Utara','Halmahera Utara|Malifut','Halmahera Utara|Tobelo','Halmahera Utara|Tobelo Barat',
'Halmahera Utara|Tobelo Selatan','Halmahera Utara|Tobelo Tengah','Halmahera Utara|Tobelo Timur','Halmahera Utara|Tobelo Utara',
'Kepulauan Sula|Mangoli Barat','Kepulauan Sula|Mangoli Selatan','Kepulauan Sula|Mangoli Tengah','Kepulauan Sula|Mangoli Timur',
'Kepulauan Sula|Mangoli Utara','Kepulauan Sula|Mangoli Utara Timur','Kepulauan Sula|Sanana','Kepulauan Sula|Sanana Utara',
'Kepulauan Sula|Sulabesi Barat','Kepulauan Sula|Sulabesi Selatan','Kepulauan Sula|Sulabesi Tengah','Kepulauan Sula|Sulabesi Timur',
'Pulau Morotai|Morotai Jaya','Pulau Morotai|Morotai Selatan','Pulau Morotai|Morotai Selatan Barat','Pulau Morotai|Morotai Timur',
'Pulau Morotai|Morotai Utara',
'Pulau Taliabu|Lede','Pulau Taliabu|Tabona','Pulau Taliabu|Taliabu Barat','Pulau Taliabu|Taliabu Barat Laut',
'Pulau Taliabu|Taliabu Selatan','Pulau Taliabu|Taliabu Timur','Pulau Taliabu|Taliabu Timur Selatan','Pulau Taliabu|Taliabu Utara',
'Ternate|Kota Ternate Selatan','Ternate|Kota Ternate Tengah','Ternate|Kota Ternate Utara','Ternate|Moti','Ternate|Pulau Batang Dua',
'Ternate|Pulau Hiri','Ternate|Pulau Ternate','Ternate|Ternate Barat',
'Tidore Kepulauan|Oba','Tidore Kepulauan|Oba Selatan','Tidore Kepulauan|Oba Tengah','Tidore Kepulauan|Oba Utara',
'Tidore Kepulauan|Tidore','Tidore Kepulauan|Tidore Selatan','Tidore Kepulauan|Tidore Timur','Tidore Kepulauan|Tidore Utara',
], 'Maluku Utara');

echo "Gorontalo, Sulbar, Maluku, Maluku Utara done.\n";
