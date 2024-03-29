<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $villeges = [
            "Banyumanik",
            "Gedawang",
            "Jabungan",
            "Ngesrep",
            "Padangsari",
            "Pedalangan",
            "Pudakpayung",
            "Srondol Kulon",
            "Srondol Wetan",
            "Sumurboto",
            "Tinjomoyo",
            "Candi",
            "Jatingaleh",
            "Jomblang",
            "Kaliwiru",
            "Karanganyar Gunung",
            "Tegalsari",
            "Wonotingal",
            "Bendanduwur",
            "Bendan Ngisor",
            "Bendungan",
            "Gajahmungkur",
            "Karangrejo",
            "Lempongsari",
            "Petompon",
            "Sampangan",
            "Gayamsari",
            "Kaligawe",
            "Pandean Lamper",
            "Sambirejo",
            "Sawahbesar",
            "Siwalan",
            "Tambakrejo",
            "Bangetayu Kulon",
            "Bangetayu Wetan",
            "Banjardowo",
            "Gebangsari",
            "Genuksari",
            "Karangroto",
            "Kudu",
            "Muktiharjo Lor",
            "Penggaron Lor",
            "Sembungharjo",
            "Terboyo Kulon",
            "Terboyo Wetan",
            "Trimulyo",
            "Cepoko",
            "Gunungpati",
            "Jatirejo",
            "Kalisegoro",
            "Kandri",
            "Mangunsari",
            "Ngijo",
            "Nongkosawit",
            "Pakintelan",
            "Patemon",
            "Plalangan",
            "Pongangan",
            "Sadeng",
            "Sekaran",
            "Sukorejo",
            "Sumurejo",
            "Bubakan",
            "Cangkiran",
            "Jatibarang",
            "Jatisari",
            "Karangmalang",
            "Kedungpane",
            "Mijen",
            "Ngadirgo",
            "Pesantren",
            "Polaman",
            "Purwosari",
            "Tambangan",
            "Wonolopo",
            "Wonoplumbon",
            "Bambankerep",
            "Bringin",
            "Gondoriyo",
            "Kalipancur",
            "Ngaliyan",
            "Podorejo",
            "Purwoyoso",
            "Tambakaji",
            "Wonosari",
            "Wates",
            "Gemah",
            "Kalicari",
            "Muktiharjo Kidul",
            "Palebon",
            "Pedurungan Kidul",
            "Pedurungan Lor",
            "Pedurungan Tengah",
            "Penggaron Kidul",
            "Plamongan Sari",
            "Tlogomulyo",
            "Tlogosari Kulon",
            "Tlogosari Wetan",
            "Bojongsalaman",
            "Bongsari",
            "Cabean",
            "Gisikdrono",
            "Kalibanteng Kidul",
            "Kalibanteng Kulon",
            "Karangayu",
            "Kembangarum",
            "Krapyak",
            "Krobokan",
            "Manyaran",
            "Ngemplak Simongan",
            "Salaman Mloyo",
            "Tambakharjo",
            "Tawang Mas",
            "Tawangsari",
            "Barusari",
            "Bulustalan",
            "Lamper Kidul",
            "Lamper Lor",
            "Lamper Tengah",
            "Mugassari",
            "Peterongan",
            "Pleburan",
            "Randusari",
            "Wonodri",
            "Bangunharjo",
            "Brumbungan",
            "Gabahan",
            "Jagalan",
            "Karangkidul",
            "Kauman",
            "Kembangsari",
            "Kranggan",
            "Miroto",
            "Pandansari",
            "Pekunden",
            "Pendrikan Kidul",
            "Pendrikan Lor",
            "Purwodinatan",
            "Sekayu",
            "Bugangan",
            "Karangtempel",
            "Karangturi",
            "Kebonagung",
            "Kemijen",
            "Mlatibaru",
            "Mlatiharjo",
            "Rejomulyo",
            "Rejosari",
            "Sarirejo",
            "Bandarharjo",
            "Bulusan",
            "Jangli",
            "Kedungmundu",
            "Kramas",
            "Mangunharjo Tembalang",
            "Meteseh",
            "Rowosari",
            "Sambiroto",
            "Sendangguwo",
            "Sendangmulyo",
            "Tandang",
            "Tembalang",
            "Jerakah",
            "Karanganyar",
            "Mangkang Kulon",
            "Mangkang Wetan",
            "Mangunharjo Tugu",
            "Randu Garut",
            "Tugurejo"
        ];

        foreach ($villeges as $villege) {
            Village::create([
                'name' => $villege,
            ]);
        }
    }
}
