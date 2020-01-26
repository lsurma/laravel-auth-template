<?php

use App\UserAuth\Models\Blacklist;
use Illuminate\Database\Seeder;

class BlacklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = (new Blacklist())->getTable();

        // Seed blacklist database with forbidden passwords
        $forbiddenPasswords = [
            '123456', 'password', '12345678', 'qwerty', '123456789', 
            '12345', '1234', '111111', '1234567', 'dragon', 
            '123123', 'baseball', 'abc123', 'football', 'monkey', 
            'letmein', '696969', 'shadow', 'master', '666666', 
            'qwertyuiop', '123321', 'mustang', '1234567890', 'michael', 
            '654321', 'pussy', 'superman', '1qaz2wsx', '7777777', 
            'fuckyou', '121212', '000000', 'qazwsx', '123qwe', 
            'killer', 'trustno1', 'jordan', 'jennifer', 'zxcvbnm', 
            'asdfgh', 'hunter', 'buster', 'soccer', 'harley', 
            'batman', 'andrew', 'tigger', 'sunshine', 'iloveyou',
            'fuckme', '2000', 'charlie', 'robert', 'thomas', 
            'hockey', 'ranger', 'daniel', 'starwars', 'klaster', 
            '112233', 'george', 'asshole', 'computer', 'michelle', 
            'jessica', 'pepper', '1111', 'zxcvbn', '555555', 
            '11111111', '131313', 'freedom', '777777', 'pass', 
            'fuck', 'maggie', '159753', 'aaaaaa', 'ginger', 
            'princess', 'joshua', 'cheese', 'amanda', 'summer', 
            'love', 'ashley', '6969', 'nicole', 'chelsea', 
            'biteme', 'matthew', 'access', 'yankees', '987654321', 
            'dallas', 'austin', 'thunder', 'taylor', 'matrix'
        ];

        // Prepare flat array to bulk insert
        $passwords = array_map(function($password) {
            return ['type' => Blacklist::T_PASSWORD, 'value' => $password];
        }, $forbiddenPasswords);

        // Seed via insert ignore to prevent errors
        DB::table($table)->insertOrIgnore($passwords);
    }
}
