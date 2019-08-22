<?php
use Flynsarmy\CsvSeeder\CsvSeeder;


class DatabaseSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

        $this->call('MessageTypeSeeder');
        $this->call('OptinTypeSeeder');
        $this->call('StatusTypeSeeder');
        $this->call('TrackedEventSeeder');
        $this->call('RegionSeeder');
        $this->call('AreaSeeder');
        $this->call('RepresentativeSeeder');
        $this->call('TerritorySeeder');
        $this->call('ZipSeeder');

        //$this->command->info('table seeded!');
	}

}


class RegionSeeder extends CsvSeeder {

    public function __construct()
    {
        $this->table = 'region';
        $this->filename = app_path().'/database/resources/region.csv';
    }

    public function run()
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
        DB::table($this->table)->delete();

        parent::run();
    }
}

class AreaSeeder extends CsvSeeder {

    public function __construct()
    {
        $this->table = 'area';
        $this->filename = app_path().'/database/resources/area.csv';
    }

    public function run()
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
        DB::table($this->table)->delete();

        parent::run();
    }
}

class RepresentativeSeeder extends CsvSeeder {

    public function __construct()
    {
        $this->table = 'representative';
        $this->filename = app_path().'/database/resources/representative.csv';
    }

    public function run()
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
        DB::table($this->table)->delete();

        parent::run();
    }
}

class TerritorySeeder extends CsvSeeder {

    public function __construct()
    {
        $this->table = 'territory';
        $this->filename = app_path().'/database/resources/territory.csv';
    }

    public function run()
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
        DB::table($this->table)->delete();

        parent::run();
    }
}

class ZipSeeder extends CsvSeeder {

    public function __construct()
    {
        $this->table = 'zip_territory';
        $this->filename = app_path().'/database/resources/zip_territory.csv';
    }

    public function run()
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
        DB::table($this->table)->delete();

        parent::run();
    }
}

class MessageTypeSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('messagetype')->delete();

        DB::insert(DB::raw("INSERT INTO messagetype (id,name) VALUES  ('', 'fax')"));
        DB::insert(DB::raw("INSERT INTO messagetype (id,name) VALUES  ('', 'webservice')"));
        DB::insert(DB::raw("INSERT INTO messagetype (id,name) VALUES  ('', 'email')"));
	}

}


class OptinTypeSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('optintype')->delete();
        DB::insert(DB::raw("INSERT INTO optintype (id,name) VALUES  ('', 'toc')"));
        DB::insert(DB::raw("INSERT INTO optintype (id,name) VALUES  ('', 'a360')"));
        DB::insert(DB::raw("INSERT INTO optintype (id,name) VALUES  ('', 'cwc')"));

	}

}

class StatusTypeSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('statustype')->delete();

        DB::insert(DB::raw("INSERT INTO statustype (id,name) VALUES  ('', 'success')"));
        DB::insert(DB::raw("INSERT INTO statustype (id,name) VALUES  ('', 'fail')"));
        DB::insert(DB::raw("INSERT INTO statustype (id,name) VALUES  ('', 'queue')"));
        DB::insert(DB::raw("INSERT INTO statustype (id,name) VALUES  ('', 'sending')"));
	}

}
class TrackedEventSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trackedevent')->delete();
        DB::insert(DB::raw("INSERT INTO trackedevent (id,name) VALUES  ('', 'save_pdf')"));
        DB::insert(DB::raw("INSERT INTO trackedevent (id,name) VALUES  ('', 'print_pdf')"));
        DB::insert(DB::raw("INSERT INTO trackedevent (id,name) VALUES  ('', 'view_pdf')"));
    }

}
