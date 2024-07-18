<?php

use MunicipalitySeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// // company
		// factory(\App\Company::class)->create();

		// // superadmin
		// factory(\App\Admin::class)->create([
		// 	'name'  => 'Superadmin',
		// 	'email' => 'sunbi.mac@gmail.com',
		// 	'type'  => 'superadmin',
		// ]);

		// // admin
		// factory(\App\Admin::class)->create([
		// 	'name'  => 'Admin',
		// 	'email' => 'admin@gmail.com',
		// ]);

		// // user
		// factory(\App\User::class)->create([
		// 	'first_name'  => 'Normal',
		// 	'last_name'  => 'User',
		// 	'email' => 'user@gmail.com',
		// ]);

		// // categories
		// factory(\App\Category::class, 5)->create();

		// // testimonials
		// factory(\App\Testimonial::class, 3)->create();

		// // news
		// factory(\App\News::class, 3)->create();

		// // socials
		// factory(\App\Social::class, 3)->create();

		// Artisan::call('db:seed', [
		// 	'--class' => MunicipalitySeeder::class,
		// ]);

		$this->runArtisanClearCommands();
	}

	private function runArtisanClearCommands(): void
	{
		\Illuminate\Support\Facades\Artisan::call('cache:clear');
		\Illuminate\Support\Facades\Artisan::call('config:clear');
		\Illuminate\Support\Facades\Artisan::call('route:clear');
		\Illuminate\Support\Facades\Artisan::call('view:clear');
	}
}
