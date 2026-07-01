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

// SULAWESI UTARA (15 kab/kota, ~171 kecamatan)
add($f, [
'Bolaang Mongondow|Bilalang','Bolaang Mongondow|Bolaang','Bolaang Mongondow|Bolaang Timur','Bolaang Mongondow|Dumoga',
'Bolaang Mongondow|Dumoga Barat','Bolaang Mongondow|Dumoga Tengah','Bolaang Mongondow|Dumoga Tenggara','Bolaang Mongondow|Dumoga Timur',
'Bolaang Mongondow|Dumoga Utara','Bolaang Mongondow|Lolak','Bolaang Mongondow|Lolayan','Bolaang Mongondow|Passi Barat',
'Bolaang Mongondow|Passi Timur','Bolaang Mongondow|Poigar','Bolaang Mongondow|Sangtombolang',
'Bolaang Mongondow Selatan|Bolaang Uki','Bolaang Mongondow Selatan|Helumo','Bolaang Mongondow Selatan|Pinolosian',
'Bolaang Mongondow Selatan|Pinolosian Tengah','Bolaang Mongondow Selatan|Pinolosian Timur','Bolaang Mongondow Selatan|Posigadan',
'Bolaang Mongondow Utara|Bintauna','Bolaang Mongondow Utara|Bolangitang Barat','Bolaang Mongondow Utara|Bolangitang Timur',
'Bolaang Mongondow Utara|Kaidipang','Bolaang Mongondow Utara|Pinogaluman','Bolaang Mongondow Utara|Sangkub',
'Bolaang Mongondow Timur|Kotabunan','Bolaang Mongondow Timur|Modayag','Bolaang Mongondow Timur|Modayag Barat',
'Bolaang Mongondow Timur|Mooat','Bolaang Mongondow Timur|Nuangan','Bolaang Mongondow Timur|Tutuyan',
'Kepulauan Sangihe|Kendahe','Kepulauan Sangihe|Kepulauan Marore','Kepulauan Sangihe|Manganitu','Kepulauan Sangihe|Manganitu Selatan',
'Kepulauan Sangihe|Nusa Tabukan','Kepulauan Sangihe|Tabukan Selatan','Kepulauan Sangihe|Tabukan Selatan Tengah',
'Kepulauan Sangihe|Tabukan Selatan Tenggara','Kepulauan Sangihe|Tabukan Tengah','Kepulauan Sangihe|Tabukan Utara',
'Kepulauan Sangihe|Tahuna','Kepulauan Sangihe|Tahuna Barat','Kepulauan Sangihe|Tahuna Timur','Kepulauan Sangihe|Tamako',
'Kepulauan Sangihe|Tatoareng',
'Kepulauan Siau Tagulandang Biaro|Biaro','Kepulauan Siau Tagulandang Biaro|Siau Barat','Kepulauan Siau Tagulandang Biaro|Siau Barat Selatan',
'Kepulauan Siau Tagulandang Biaro|Siau Barat Utara','Kepulauan Siau Tagulandang Biaro|Siau Tengah','Kepulauan Siau Tagulandang Biaro|Siau Timur',
'Kepulauan Siau Tagulandang Biaro|Siau Timur Selatan','Kepulauan Siau Tagulandang Biaro|Tagulandang','Kepulauan Siau Tagulandang Biaro|Tagulandang Selatan',
'Kepulauan Siau Tagulandang Biaro|Tagulandang Utara',
'Kepulauan Talaud|Beo','Kepulauan Talaud|Beo Selatan','Kepulauan Talaud|Beo Utara','Kepulauan Talaud|Damao','Kepulauan Talaud|Essang',
'Kepulauan Talaud|Essang Selatan','Kepulauan Talaud|Gemeh','Kepulauan Talaud|Kabaruan','Kepulauan Talaud|Kalongan',
'Kepulauan Talaud|Lirung','Kepulauan Talaud|Melonguane','Kepulauan Talaud|Melonguane Timur','Kepulauan Talaud|Miangas',
'Kepulauan Talaud|Moronge','Kepulauan Talaud|Nanusa','Kepulauan Talaud|Pulutan','Kepulauan Talaud|Rainis','Kepulauan Talaud|Salibabu',
'Kepulauan Talaud|Tampanamma',
'Minahasa|Eris','Minahasa|Kakas','Minahasa|Kakas Barat','Minahasa|Kawangkoan','Minahasa|Kawangkoan Barat',
'Minahasa|Kawangkoan Utara','Minahasa|Kombi','Minahasa|Langowan Barat','Minahasa|Langowan Selatan','Minahasa|Langowan Timur',
'Minahasa|Langowan Utara','Minahasa|Lembean Timur','Minahasa|Mandolang','Minahasa|Pineleng','Minahasa|Remboken',
'Minahasa|Sonder','Minahasa|Tombariri','Minahasa|Tombariri Timur','Minahasa|Tombulu','Minahasa|Tompaso',
'Minahasa|Tompaso Barat','Minahasa|Tondano Barat','Minahasa|Tondano Selatan','Minahasa|Tondano Timur','Minahasa|Tondano Utara',
'Minahasa Selatan|Amurang','Minahasa Selatan|Amurang Barat','Minahasa Selatan|Amurang Timur','Minahasa Selatan|Kumelembuai',
'Minahasa Selatan|Maesaan','Minahasa Selatan|Modoinding','Minahasa Selatan|Motoling','Minahasa Selatan|Motoling Barat',
'Minahasa Selatan|Motoling Timur','Minahasa Selatan|Ranoyapo','Minahasa Selatan|Sinonsayang','Minahasa Selatan|Suluun Tareran',
'Minahasa Selatan|Tareran','Minahasa Selatan|Tatapaan','Minahasa Selatan|Tenga','Minahasa Selatan|Tompaso Baru','Minahasa Selatan|Tumpaan',
'Minahasa Tenggara|Belang','Minahasa Tenggara|Pasan','Minahasa Tenggara|Pusomaen','Minahasa Tenggara|Ratahan',
'Minahasa Tenggara|Ratahan Timur','Minahasa Tenggara|Ratatotok','Minahasa Tenggara|Silian Raya','Minahasa Tenggara|Tombatu',
'Minahasa Tenggara|Tombatu Timur','Minahasa Tenggara|Tombatu Utara','Minahasa Tenggara|Touluaan','Minahasa Tenggara|Touluaan Selatan',
'Minahasa Utara|Airmadidi','Minahasa Utara|Dimembe','Minahasa Utara|Kalawat','Minahasa Utara|Kauditan','Minahasa Utara|Kema',
'Minahasa Utara|Likupang Barat','Minahasa Utara|Likupang Selatan','Minahasa Utara|Likupang Timur','Minahasa Utara|Talawaan',
'Minahasa Utara|Wori',
'Bitung|Aertembaga','Bitung|Girian','Bitung|Lembeh Selatan','Bitung|Lembeh Utara','Bitung|Madidir','Bitung|Maesa',
'Bitung|Matuari','Bitung|Ranowulu',
'Kotamobagu|Kotamobagu Barat','Kotamobagu|Kotamobagu Selatan','Kotamobagu|Kotamobagu Timur','Kotamobagu|Kotamobagu Utara',
'Manado|Bunaken','Manado|Bunaken Kepulauan','Manado|Malalayang','Manado|Mapanget','Manado|Paal Dua','Manado|Sario',
'Manado|Singkil','Manado|Tikala','Manado|Tuminting','Manado|Wanea','Manado|Wenang',
'Tomohon|Tomohon Barat','Tomohon|Tomohon Selatan','Tomohon|Tomohon Tengah','Tomohon|Tomohon Timur','Tomohon|Tomohon Utara',
], 'Sulawesi Utara');

// SULAWESI TENGAH (13 kab/kota, ~175 kecamatan)
add($f, [
'Banggai|Balantak','Banggai|Balantak Selatan','Banggai|Balantak Utara','Banggai|Batui','Banggai|Batui Selatan',
'Banggai|Bualemo','Banggai|Bunta','Banggai|Kintom','Banggai|Lamala','Banggai|Lobu','Banggai|Luwuk','Banggai|Luwuk Selatan',
'Banggai|Luwuk Timur','Banggai|Luwuk Utara','Banggai|Mantoh','Banggai|Masama','Banggai|Moilong','Banggai|Nambo',
'Banggai|Nuhon','Banggai|Pagimana','Banggai|Simpang Raya','Banggai|Toili','Banggai|Toili Barat',
'Banggai Kepulauan|Banggai','Banggai Kepulauan|Banggai Selatan','Banggai Kepulauan|Banggai Tengah','Banggai Kepulauan|Banggai Utara',
'Banggai Kepulauan|Bangkurung','Banggai Kepulauan|Bokan Kepulauan','Banggai Kepulauan|Buko','Banggai Kepulauan|Buko Selatan',
'Banggai Kepulauan|Bulagi','Banggai Kepulauan|Bulagi Selatan','Banggai Kepulauan|Bulagi Utara','Banggai Kepulauan|Labobo',
'Banggai Kepulauan|Liang','Banggai Kepulauan|Peling Tengah','Banggai Kepulauan|Tinangkung','Banggai Kepulauan|Tinangkung Selatan',
'Banggai Kepulauan|Tinangkung Utara','Banggai Kepulauan|Totikum','Banggai Kepulauan|Totikum Selatan',
'Banggai Laut|Banggai','Banggai Laut|Banggai Selatan','Banggai Laut|Banggai Tengah','Banggai Laut|Banggai Utara',
'Banggai Laut|Bangkurung','Banggai Laut|Labobo',
'Buol|Biau','Buol|Bokat','Buol|Bukal','Buol|Bunobogu','Buol|Gadung','Buol|Karamat','Buol|Lakea','Buol|Momunu','Buol|Paleleh',
'Buol|Paleleh Barat','Buol|Tiloan',
'Donggala|Balaesang','Donggala|Balaesang Tanjung','Donggala|Banawa','Donggala|Banawa Selatan','Donggala|Banawa Tengah',
'Donggala|Dampelas','Donggala|Labuan','Donggala|Pinembani','Donggala|Rio Pakava','Donggala|Sindue','Donggala|Sindue Tobata',
'Donggala|Sindue Tombusabora','Donggala|Sirenja','Donggala|Sojol','Donggala|Sojol Utara','Donggala|Tanantovea',
'Morowali|Bahodopi','Morowali|Bumi Raya','Morowali|Bungku Barat','Morowali|Bungku Pesisir','Morowali|Bungku Selatan',
'Morowali|Bungku Tengah','Morowali|Bungku Timur','Morowali|Menui Kepulauan','Morowali|Wita Ponda',
'Morowali Utara|Bungku Utara','Morowali Utara|Lembo','Morowali Utara|Lembo Raya','Morowali Utara|Mamosalato',
'Morowali Utara|Mori Atas','Morowali Utara|Mori Utara','Morowali Utara|Petasia','Morowali Utara|Petasia Barat',
'Morowali Utara|Petasia Timur','Morowali Utara|Soyo Jaya',
'Parigi Moutong|Ampibabo','Parigi Moutong|Balinggi','Parigi Moutong|Bolano','Parigi Moutong|Bolano Lambunu',
'Parigi Moutong|Kasimbar','Parigi Moutong|Mepanga','Parigi Moutong|Moutong','Parigi Moutong|Ongka Malino',
'Parigi Moutong|Palasa','Parigi Moutong|Parigi','Parigi Moutong|Parigi Barat','Parigi Moutong|Parigi Selatan',
'Parigi Moutong|Parigi Tengah','Parigi Moutong|Parigi Utara','Parigi Moutong|Sausu','Parigi Moutong|Siniu',
'Parigi Moutong|Taopa','Parigi Moutong|Tinombo','Parigi Moutong|Tinombo Selatan','Parigi Moutong|Tomini',
'Parigi Moutong|Toribulu','Parigi Moutong|Torue',
'Poso|Lage','Poso|Lore Barat','Poso|Lore Piore','Poso|Lore Selatan','Poso|Lore Tengah','Poso|Lore Timur','Poso|Lore Utara',
'Poso|Pamona Barat','Poso|Pamona Puselemba','Poso|Pamona Selatan','Poso|Pamona Tenggara','Poso|Pamona Timur',
'Poso|Pamona Utara','Poso|Poso Kota','Poso|Poso Kota Selatan','Poso|Poso Kota Utara','Poso|Poso Pesisir',
'Poso|Poso Pesisir Selatan','Poso|Poso Pesisir Utara',
'Tojo Una-Una|Ampana Kota','Tojo Una-Una|Ampana Tete','Tojo Una-Una|Batudaka','Tojo Una-Una|Ratolindo',
'Tojo Una-Una|Talatako','Tojo Una-Una|Togean','Tojo Una-Una|Tojo','Tojo Una-Una|Tojo Barat','Tojo Una-Una|Ulu Bongka',
'Tojo Una-Una|Una-Una','Tojo Una-Una|Walea Besar','Tojo Una-Una|Walea Kepulauan',
'Tolitoli|Baolan','Tolitoli|Basidondo','Tolitoli|Dako Pemean','Tolitoli|Dampal Selatan','Tolitoli|Dampal Utara',
'Tolitoli|Dondo','Tolitoli|Galang','Tolitoli|Lampasio','Tolitoli|Ogodeide','Tolitoli|Tolitoli Utara',
'Sigi|Dolo','Sigi|Dolo Barat','Sigi|Dolo Selatan','Sigi|Gumbasa','Sigi|Kinovaro','Sigi|Kulawi','Sigi|Kulawi Selatan',
'Sigi|Lindu','Sigi|Marawola','Sigi|Marawola Barat','Sigi|Nokilalaki','Sigi|Palolo','Sigi|Pipikoro','Sigi|Sigi Biromaru',
'Sigi|Tanambulava',
'Palu|Mantikulore','Palu|Palu Barat','Palu|Palu Selatan','Palu|Palu Timur','Palu|Palu Utara','Palu|Tatanga','Palu|Tawaeli','Palu|Ulujadi',
], 'Sulawesi Tengah');

echo "Sulawesi Utara & Tengah done.\n";
