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

// NUSA TENGGARA TIMUR (22 kab/kota, ~309 kecamatan)
add($f, [
'Alor|Abad Selatan','Alor|Alor Barat Daya','Alor|Alor Barat Laut','Alor|Alor Selatan','Alor|Alor Tengah Utara',
'Alor|Alor Timur','Alor|Alor Timur Laut','Alor|Kabola','Alor|Lembur','Alor|Mataru','Alor|Pantar','Alor|Pantar Barat',
'Alor|Pantar Barat Laut','Alor|Pantar Tengah','Alor|Pantar Timur','Alor|Pulau Pura','Alor|Pureman','Alor|Teluk Mutiara',
'Belu|Atambua Barat','Belu|Atambua Selatan','Belu|Kakuluk Mesak','Belu|Kota Atambua','Belu|Lamaknen','Belu|Lamaknen Selatan',
'Belu|Lasiolat','Belu|Nanaet Duabesi','Belu|Raihat','Belu|Raimanuk','Belu|Tasifeto Barat','Belu|Tasifeto Timur',
'Ende|Detusoko','Ende|Ende','Ende|Ende Selatan','Ende|Ende Tengah','Ende|Ende Timur','Ende|Ende Utara','Ende|Kelimutu',
'Ende|Kotabaru','Ende|Lepembusu Kelisoke','Ende|Lio Timur','Ende|Maukaro','Ende|Maurole','Ende|Nangapanda',
'Ende|Ndona','Ende|Ndona Timur','Ende|Ndori','Ende|Pulau Ende','Ende|Wewaria','Ende|Wolojita','Ende|Wolowaru',
'Flores Timur|Adonara','Flores Timur|Adonara Barat','Flores Timur|Adonara Tengah','Flores Timur|Adonara Timur',
'Flores Timur|Demon Pagong','Flores Timur|Ile Boleng','Flores Timur|Ile Bura','Flores Timur|Ile Mandiri',
'Flores Timur|Kelubagolit','Flores Timur|Larantuka','Flores Timur|Lewolema','Flores Timur|Solor Barat',
'Flores Timur|Solor Selatan','Flores Timur|Solor Timur','Flores Timur|Tanjung Bunga','Flores Timur|Titehena',
'Flores Timur|Witihama','Flores Timur|Wotan Ulumando','Flores Timur|Wulanggitang',
'Kupang|Amabi Oefeto','Kupang|Amabi Oefeto Timur','Kupang|Amarasi','Kupang|Amarasi Barat','Kupang|Amarasi Selatan',
'Kupang|Amarasi Timur','Kupang|Amfoang Barat Daya','Kupang|Amfoang Barat Laut','Kupang|Amfoang Selatan',
'Kupang|Amfoang Tengah','Kupang|Amfoang Timur','Kupang|Amfoang Utara','Kupang|Fatuleu','Kupang|Fatuleu Barat',
'Kupang|Fatuleu Tengah','Kupang|Kupang Barat','Kupang|Kupang Tengah','Kupang|Kupang Timur','Kupang|Nekamese',
'Kupang|Semau','Kupang|Semau Selatan','Kupang|Sulamu','Kupang|Taebenu','Kupang|Takari',
'Lembata|Atadei','Lembata|Buyasuri','Lembata|Ile Ape','Lembata|Ile Ape Timur','Lembata|Lebatukan','Lembata|Nagawutung',
'Lembata|Nubatukan','Lembata|Omesuri','Lembata|Wulandoni',
'Malaka|Botin Leobele','Malaka|Io Kufeu','Malaka|Kobalima','Malaka|Kobalima Timur','Malaka|Laenmanen',
'Malaka|Malaka Barat','Malaka|Malaka Tengah','Malaka|Malaka Timur','Malaka|Rinhat','Malaka|Sasita Mean',
'Malaka|Weliman','Malaka|Wewiku',
'Manggarai|Cibal','Manggarai|Cibal Barat','Manggarai|Langke Rembong','Manggarai|Lelak','Manggarai|Rahong Utara',
'Manggarai|Reok','Manggarai|Reok Barat','Manggarai|Ruteng','Manggarai|Satar Mese','Manggarai|Satar Mese Barat',
'Manggarai|Satar Mese Utara','Manggarai|Wae Rii',
'Manggarai Barat|Boleng','Manggarai Barat|Komodo','Manggarai Barat|Kuwus','Manggarai Barat|Kuwus Barat',
'Manggarai Barat|Lembor','Manggarai Barat|Lembor Selatan','Manggarai Barat|Macang Pacar','Manggarai Barat|Mbeliling',
'Manggarai Barat|Ndoso','Manggarai Barat|Pacar','Manggarai Barat|Sano Nggoang','Manggarai Barat|Welak',
'Manggarai Timur|Benteng Jawa','Manggarai Timur|Congkar','Manggarai Timur|Elar','Manggarai Timur|Elar Selatan',
'Manggarai Timur|Kota Komba','Manggarai Timur|Lamba Leda','Manggarai Timur|Lamba Leda Selatan',
'Manggarai Timur|Lamba Leda Timur','Manggarai Timur|Lamba Leda Utara','Manggarai Timur|Poco Ranaka',
'Manggarai Timur|Poco Ranaka Timur','Manggarai Timur|Rana Mese','Manggarai Timur|Sambi Rampas',
'Nagekeo|Aesesa','Nagekeo|Aesesa Selatan','Nagekeo|Boawae','Nagekeo|Keo Tengah','Nagekeo|Mauponggo','Nagekeo|Nangaroro',
'Nagekeo|Wolowae',
'Ngada|Aimere','Ngada|Bajawa','Ngada|Bajawa Utara','Ngada|Golewa','Ngada|Golewa Barat','Ngada|Golewa Selatan',
'Ngada|Inerie','Ngada|Jerebuu','Ngada|Riung','Ngada|Riung Barat','Ngada|Soa','Ngada|Wolomeze','Ngada|Wolo',
'Rote Ndao|Landu Leko','Rote Ndao|Lobalain','Rote Ndao|Ndao Nuse','Rote Ndao|Pantai Baru','Rote Ndao|Rote Barat',
'Rote Ndao|Rote Barat Daya','Rote Ndao|Rote Barat Laut','Rote Ndao|Rote Selatan','Rote Ndao|Rote Tengah','Rote Ndao|Rote Timur',
'Sabu Raijua|Hawu Mehara','Sabu Raijua|Raijua','Sabu Raijua|Sabu Barat','Sabu Raijua|Sabu Liae','Sabu Raijua|Sabu Tengah',
'Sabu Raijua|Sabu Timur',
'Sikka|Alok','Sikka|Alok Barat','Sikka|Alok Timur','Sikka|Bola','Sikka|Doreng','Sikka|Hewokloang','Sikka|Kanga',
'Sikka|Kewapante','Sikka|Koting','Sikka|Lela','Sikka|Magepanda','Sikka|Mapitara','Sikka|Mego','Sikka|Nelle',
'Sikka|Nita','Sikka|Paga','Sikka|Palue','Sikka|Talibura','Sikka|Tana Wawo','Sikka|Waiblama','Sikka|Waigete',
'Sumba Barat|Kota Waikabubak','Sumba Barat|Lamboya','Sumba Barat|Loli','Sumba Barat|Tana Righu','Sumba Barat|Wanokaka',
'Sumba Barat Daya|Kodi','Sumba Barat Daya|Kodi Balaghar','Sumba Barat Daya|Kodi Bangedo','Sumba Barat Daya|Kodi Utara',
'Sumba Barat Daya|Kota Tambolaka','Sumba Barat Daya|Loura','Sumba Barat Daya|Wewewa Barat','Sumba Barat Daya|Wewewa Selatan',
'Sumba Barat Daya|Wewewa Tengah','Sumba Barat Daya|Wewewa Timur','Sumba Barat Daya|Wewewa Utara',
'Sumba Tengah|Katiku Tana','Sumba Tengah|Katiku Tana Selatan','Sumba Tengah|Mamboro','Sumba Tengah|Umbu Ratu Nggay',
'Sumba Tengah|Umbu Ratu Nggay Barat',
'Sumba Timur|Haharu','Sumba Timur|Kahaungu Eti','Sumba Timur|Kambata Mapambuhang','Sumba Timur|Kambera','Sumba Timur|Kanatang',
'Sumba Timur|Karera','Sumba Timur|Katala Hamu Lingu','Sumba Timur|Kota Waingapu','Sumba Timur|Lewa','Sumba Timur|Lewa Tidahu',
'Sumba Timur|Mahu','Sumba Timur|Matawai La Pawu','Sumba Timur|Ngadu Ngala','Sumba Timur|Nggaha Ori Angu',
'Sumba Timur|Paberiwai','Sumba Timur|Pahunga Lodu','Sumba Timur|Pandawai','Sumba Timur|Pinu Pahar','Sumba Timur|Rindi',
'Sumba Timur|Tabundung','Sumba Timur|Umalulu','Sumba Timur|Wulla Waijelu',
'Timor Tengah Selatan|Amanatun Selatan','Timor Tengah Selatan|Amanatun Utara','Timor Tengah Selatan|Amanuban Barat',
'Timor Tengah Selatan|Amanuban Selatan','Timor Tengah Selatan|Amanuban Tengah','Timor Tengah Selatan|Amanuban Timur',
'Timor Tengah Selatan|Batu Putih','Timor Tengah Selatan|Boking','Timor Tengah Selatan|Fatukopa','Timor Tengah Selatan|Fatumnasi',
'Timor Tengah Selatan|Fautmolo','Timor Tengah Selatan|Kie','Timor Tengah Selatan|Kok Baun','Timor Tengah Selatan|Kolbano',
'Timor Tengah Selatan|Kot\'olin','Timor Tengah Selatan|Kota Soe','Timor Tengah Selatan|Kualin','Timor Tengah Selatan|Kuanfatu',
'Timor Tengah Selatan|Mollo Barat','Timor Tengah Selatan|Mollo Selatan','Timor Tengah Selatan|Mollo Tengah',
'Timor Tengah Selatan|Mollo Utara','Timor Tengah Selatan|Noebana','Timor Tengah Selatan|Noebeba','Timor Tengah Selatan|Nunbena',
'Timor Tengah Selatan|Nunkolo','Timor Tengah Selatan|Oenino','Timor Tengah Selatan|Polen','Timor Tengah Selatan|Santian',
'Timor Tengah Selatan|Tobu','Timor Tengah Selatan|Toianas',
'Timor Tengah Utara|Biboki Anleu','Timor Tengah Utara|Biboki Feotleu','Timor Tengah Utara|Biboki Moenleu',
'Timor Tengah Utara|Biboki Selatan','Timor Tengah Utara|Biboki Tanpah','Timor Tengah Utara|Biboki Utara',
'Timor Tengah Utara|Bikomi Nilulat','Timor Tengah Utara|Bikomi Selatan','Timor Tengah Utara|Bikomi Tengah',
'Timor Tengah Utara|Bikomi Utara','Timor Tengah Utara|Insana','Timor Tengah Utara|Insana Barat','Timor Tengah Utara|Insana Fafinesu',
'Timor Tengah Utara|Insana Tengah','Timor Tengah Utara|Insana Utara','Timor Tengah Utara|Kota Kefamenanu',
'Timor Tengah Utara|Miomaffo Barat','Timor Tengah Utara|Miomaffo Tengah','Timor Tengah Utara|Miomaffo Timur',
'Timor Tengah Utara|Musi','Timor Tengah Utara|Mutis','Timor Tengah Utara|Naibenu','Timor Tengah Utara|Noemuti',
'Timor Tengah Utara|Noemuti Timur',
'Kupang Kota|Alak','Kupang Kota|Kelapa Lima','Kupang Kota|Kota Lama','Kupang Kota|Kota Raja','Kupang Kota|Maulafa','Kupang Kota|Oebobo',
], 'Nusa Tenggara Timur');

echo "NTT done.\n";
