<?php namespace App;

use App\Controller;
use App\Request;
use App\Flash;

/**
* UserController
*/
class UserController extends Controller
{

    public function index()
    {
        $users = QB::table('users')->get();

        return view('users/index', compact('users'));
    }

    public function create()
    {
        return view('users/create', compact('users'));
    }

    public function store()
    {
        if (Request::method() == 'POST') {

            $data = Request::all();

            Session::put('old', $data);

            $rules = [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ];

            $messages = [
                'email' => [
                    'required' => 'Eposta alanı gereklidir.',
                    'email' => 'Eposta alanı geçerli bir eposta adresi olmalıdır.',
                ],
            ];

            $validator = Validation::make($data, $rules, $messages);

            if ($validator->fails()) {
                Flash::put('errors', $validator->errors());

                Message::set('warning', "Please check your errors below.");

                return redirect('/user/create');
            }

            $data = [
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            QB::table('users')->insert($data);

            Message::set('success', "User created was successfully.");

            return redirect('/user/create');
        }
    }

    public function edit($id)
    {      
        $user = QB::table('users')->find($id);

        return view('users/edit', compact('user'));
    }

    public function update($id)
    {
        if (Request::method() == 'POST') {

            $data = Request::all();

            Session::put('old', $data);

            $rules = [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ];

            $messages = [
                'email' => [
                    'required' => 'Eposta alanı gereklidir.',
                    'email' => 'Eposta alanı geçerli bir eposta adresi olmalıdır.',
                ],
            ];

            $validator = Validation::make($data, $rules, $messages);

            if ($validator->fails()) {
                Flash::put('errors', $validator->errors());

                Message::set('warning', "Please check your errors below.");

                return redirect('/user/edit/' . $id);
            }

            $data = [
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'updated_at' => now(),
            ];

            QB::table('users')->update($data, $id);

            Message::set('success', "User updated was successfully.");

            return redirect('/user');
        }
    }
}
