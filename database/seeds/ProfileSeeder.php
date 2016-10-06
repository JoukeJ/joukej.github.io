<?php /* created by Rob van Bentem, 24/09/2015 */


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{

    public function run()
    {
        Model::unguard();

        // $this->call('UserTableSeeder');

        // TTC
        $this->call('TTC\Development\ProfileSeeder');
    }
}
