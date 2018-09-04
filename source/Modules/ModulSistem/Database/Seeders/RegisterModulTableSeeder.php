<?php

namespace Modules\ModulSistem\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\ModulSistem\Entities\ModulSistem;

class RegisterModulTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $modul_pertama = ModulSistem::where('id', '=', '1')->value('nama');

        if($modul_pertama != 'modul sistem')
        {
            $temp = ModulSistem::findOrFail(1);

            ModulSistem::where('id', '=', '1')->delete();

            DB::table('modul_sistem')->insert([
                'id' => '1',
                'nama' => 'modul sistem',
                'modul' => config('modulsistem.name'),
                'rute_home' => 'modul.index',
                'nav_id' => 'modul_sistem',
                'icon' => 'fas fa-hdd'
            ]);

            $id_modul = ModulSistem::where('nama', '=', 'modul sistem')->value('id');

            DB::table('hak_akses_modul_sistem')->insert([
                'id_modul' => $id_modul,
                'id_jabatan' => '1'
            ]);

            $modul = new ModulSistem();
            $modul->nama = $temp->nama;
            $modul->modul = $temp->modul;
            $modul->rute_home = $temp->rute_home;
            $modul->nav_id = $temp->nav_id;
            $modul->icon = $temp->icon;
            $modul->save();
        }

        // $this->call("OthersTableSeeder");
    }
}
