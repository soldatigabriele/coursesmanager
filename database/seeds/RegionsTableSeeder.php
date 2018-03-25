<?php

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('regions')->delete();
        \DB::table('regions')->insert(array (

	
		0 => [ "id" => 1,
			"name" => "Abruzzo"
		],

		1 => [ "id" => 2,
			"name" => "Basilicata"
		],

		2 => [ "id" => 3,
			"name" => "Calabria"
		],

		3 => [ "id" => 4,
			"name" => "Campania"
		],

		4 => [ "id" => 5,
			"name" => "Emilia-Romagna"
		],

		5 => [ "id" => 6,
			"name" => "Friuli"
		],

		6 => [ "id" => 7,
			"name" => "Lazio"
		],

		7 => [ "id" => 8,
			"name" => "Liguria"
		],

		8 => [ "id" => 9,
			"name" => "Lombardia"
		],

		9 => [ "id" => 10,
			"name" => "Marche"
		],

		10 => [ "id" => 11,
			"name" => "Molise"
		],

		11 => [ "id" => 12,
			"name" => "Piemonte"
		],

		12 => [ "id" => 13,
			"name" => "Puglia"
		],

		13 => [ "id" => 14,
			"name" => "Sardegna"
		],

		14 => [ "id" => 15,
			"name" => "Sicilia"
		],

		15 => [ "id" => 16,
			"name" => "Toscana"
		],

		16 => [ "id" => 17,
			"name" => "Trentino"
		],

		17 => [ "id" => 18,
			"name" => "Umbria"
		],

		18 => [ "id" => 19,
			"name" => "Valle Aosta"
		],

		19 => [ "id" => 20,
			"name" => "Veneto"
		],



        ));
    }
}
