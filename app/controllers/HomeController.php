<?php namespace App;

use App\Controller;

/**
* HomeController
*/
class HomeController extends Controller
{

    public function index()
    {
        $users = QB::table('users')->get();


        return view('home', compact('users'));

        // $users = QB::table('users')
        //                 ->orderBy('id', 'DESC')
        //                 ->take(3)
        //                 ->skip(1)
        //                 ->get(['email', 'id']);

        // $otherUsers = QB::table('users')
        //                 ->select('id', 'email')
        //                 ->where('id', '=', 2)
        //                 ->orWhere('email', '=', 'burak@burakbolat.com')
        //                 ->orderBy('id', 'DESC')
        //                 // ->toSql();
        //                 ->get();

        // $singleUser = QB::table('users')->find(3);

        // dd($users, $otherUsers, $singleUser);

        // $mutiData = [
        //     [
        //         'email' => 'burak@burakbolat.com',
        //         'password' => bcrypt('password'),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'email' => 'example@burakbolat.com',
        //         'password' => bcrypt('password'),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]
        // ];

        // $data = [
        //     'email' => 'mesut@burakbolat.com',
        //     'password' => bcrypt('password'),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ];


        // $inserted = QB::table('users')->insert($mutiData);
        // $inserted = QB::table('users')->insert($data);

        // dd($inserted);

        // $data = [
        //     'email' => 'example@burakbolat.com',
        //     'password' => bcrypt('password'),
        //     'updated_at' => now(),
        // ];

        // $updated = QB::table('users')->update($data, $id = 1, $key = 'id', $operator = '=');

        // dd($updated);

        // $deleted = QB::table('users')->delete($id = 10, $key = 'id', $operator = '>');

        // dd($deleted);


        // $users = User::get();

        // $query = DB::table('users')
        //                     // ->select(['id', 'email'])
        //                     ->select('id', 'email')
        //                     ->where('email', '=', 'burak@burakbolat.com')
        //                     ->orWhere('created_at', '=', '2015-04-18 12:26:40')
        //                     ->orderBy('id', 'asc')
        //                     ->skip(0)
        //                     ->take(10)
        //                     ->get();

        // $query = DB::table('users')->get();

        // $query = DB::table('users')->find(4);

        // $query = DB::table('users')->insert([
        //     'email' => 'example@burakbolat.com',
        //     'password' => bcrypt('password'),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        //
        // $query = DB::table('users')->insert([
        //     'email' => 'example@burakbolat.com',
        //     'password' => bcrypt('password'),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // dd($query);


        // $query = DB::table('users')->update([
        //     'email' => 'burakbolat@example.com',
        //     'password' => bcrypt('password')
        // ], 3);
        //

        // $query = DB::table('users')->delete(4, 'id', '>');

        // dd($query);

        // $db = new Database;

        // $params = array(
        //     ':email' => 'example@burakbolat.com',
        //     ':password' => bcrypt('password'),
        //     ':created_at' => now(),
        //     ':updated_at' => now(),
        //     'array' => array(),
        // );

        // dd(is_multi_array($params));

        // // array (size=4)
        // //   ':email' => string 'example@burakbolat.com' (length=22)
        // //   ':password' => string '$2y$11$xOoZqZVjm6mZxDldjztWW.bN8LRcpAPqTX7AcZHSuFPrl8C7dA/u2' (length=60)
        // //   ':created_at' => string '2015-04-26 12:58:40' (length=19)
        // //   ':updated_at' => string '2015-04-26 12:58:40' (length=19)

        // // dd($params);

        // $db->query('INSERT INTO users (email, password, created_at, updated_at) VALUES (:email, :password, :created_at, :updated_at)');

        // foreach ($params as $key => $value) {
        //     $db->bind($key, $value);
        // }

        // var_dump($db->execute()); exit();

    }
}
