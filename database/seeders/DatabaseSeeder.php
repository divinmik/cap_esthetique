<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
        // 1) Un compte admin de test (exemple)
        User::create([
            'code'               => 'ADM001',
            'firstname'          => 'Mikangamani',
            'lastname'           => 'Divin',
            'birthdate'          => '1990-01-01',
            'birthplace'         => 'Brazzaville',
            'address'            => 'Centre-ville',
            'phone'              => '+242068409872',
            'email'              => 'mikdivin@gmail.com',
            'city'               => 'Brazzaville',
            'level'              => 'Licence',
            'contact_salon'      => 0,
            'program'            => 'Esthétique',
            'message'            => 'Compte administrateur de test',
            'type_inscription'   => 'en_ligne',
            'type_formation'     => 'certifiante',
            'profile_photo_path' => 'profiles/admin.png',
            'is_valide'          => 1,
            'is_actif'          => 1,
            'email_verified_at'  => now(),
            'password'           => bcrypt('secret123'), // change en prod
            'statut'             => 'admin',
        ]);
        
        User::create([
            'code'               => 'ADM002',
            'firstname'          => 'Meking BOMATHA ',
            'lastname'           => 'Nidi-Valeur',
            'birthdate'          => '1990-01-01',
            'birthplace'         => 'Brazzaville',
            'address'            => 'Centre-ville',
            'phone'              => '+242068239029',
            'email'              => 'mekingnidi@gmail.com',
            'city'               => 'Brazzaville',
            'level'              => 'Licence',
            'contact_salon'      => 0,
            'program'            => 'Esthétique',
            'message'            => 'Compte administrateur de test',
            'type_inscription'   => 'en_ligne',
            'type_formation'     => 'certifiante',
            'profile_photo_path' => 'profiles/admin.png',
            'is_valide'          => 1,
            'is_actif'          => 1,
            'email_verified_at'  => now(),
            'password'           => bcrypt('secret123'), // change en prod
            'statut'             => 'admin',
        ]);

        
    }
}
