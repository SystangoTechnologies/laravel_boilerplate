<?php

namespace App\Console\Commands;

use App\User;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;


class unleashed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unleashed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unleash command will going to autocreate admin/user panels with a dummy user listed in them';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('ui',[
            'type' => 'bootstrap',
            '--auth' => 1
        ]);
        shell_exec('npm install && npm run dev');
        
        Artisan::call('migrate');
     
        Artisan::call('make:migration', [
            'name' => 'create_user_types',
            '--create' => 'user_types'
        ]);

        /*This is used for updating the users table with new column : STARTS*/
        Artisan::call('make:migration', [
            'name' => 'update_users_table',
        ]);

        Schema::table('users', function($table)
        {
            $table->string('user_type')->after('id');
        });
        /* ENDS*/

        Artisan::call('migrate');

        //This will update the column name position
        Schema::table('user_types', function (Blueprint $table) {
            $table->string('user_type_name')->unique()->after('id');
        });

        //Command to create User Type model
        Artisan::call('make:model', [
            'name' => 'User_Types'
        ]);
        //Creating an array of test users types
        $user_type = [
            ["user_type_name" => "admin"],
            ["user_type_name" => "user"]
        ];
        // Saving the user types in the user_types table
        DB::table('user_types')->insert($user_type);

        //Creating an array of test users
        $add_users = [
            [
                "user_type" => 1,
                "name"      => "Tony Stark",
                "email"     => "tony_stark@admin.com",
                "password"  => bcrypt('tony_stark'),
            ],
            [
                "user_type" => 2,
                "name"      => "Harry Potter",
                "email"     => "harry_potter@user.com",
                "password"  => bcrypt('harry_potter'),
            ],
        ];

        //Saving the users in the users table
        DB::table('users')->insert($add_users);

        //Downloading and saving the custom view blade in the resources folder
        $view_path = resource_path('views/home.blade.php');

        $ch = curl_init();
        $source = "http://localhost/home.blade.php";
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec ($ch);
        curl_close ($ch);

        $destination = $view_path;
        $file = fopen($destination, "w+");
        fputs($file, $data);
        fclose($file);
        exit();   
    }
}
