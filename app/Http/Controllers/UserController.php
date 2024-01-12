<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UserController extends Controller
{
    private $database;
    private $firebaseAuth;

    public function __construct()
    {
        $this->database = \App\Services\FirebaseService::connect();
        $this->firebaseAuth = Firebase::auth();
    }

    public function registerView() {
        return view('register');
    }

    public function loginView() {
        return view('login');
    }

    public function register(Request $request){

        $valid = $request->validate([
            'email' => 'required', // Check uniqueness in the usernames index
            'password' => 'required|min:6',
            // 'name'     => 'required',
        ]);

        // $uuid = Str::uuid()->toString();

        // $existUser = !is_null($this->database->getReference('econnect/usernames/' . $valid['username'])->getValue());

        // if($existUser){
        //     return redirect('/register')->with('status', 'Username already exits!');
        // }

        // $this->database
        // ->getReference('econnect/users/' . $uuid)
        // ->set([
        //     'username' => $valid['username'],
        //     'password' => Hash::make($valid['password']),
        //     'name' => $valid['name']
        // ]);

        // // Save username to UUID mapping in the usernames index
        // $this->database
        // ->getReference('econnect/usernames/' . $valid['username'])
        // ->set($uuid);

        try {
            $this->firebaseAuth->createUserWithEmailAndPassword($valid['email'], $valid['password']);
            $request->session()->flash('success', "Register berhasil");
        } catch (Exception  $e){
            $request->session()->flash('error', "Register gagal");
        }


        return redirect('/');
    }

    public function login(Request $request){
        
        // $userData = $this->getUserDataByUsername($request['username']);

        // if (!$userData) {
        //     return redirect('/login')
        //         ->withErrors(['username' => 'Invalid username or password.'])
        //         ->withInput();
        // }

        // if (!Hash::check($request['password'], $userData['password'])) {
        //     return redirect('/login')
        //         ->withErrors(['username' => 'Invalid username or password.'])
        //         ->withInput();
        // }

        // // Authentication successful, log in the user
        // Auth::loginUsingId($userData['id']); // Adjust 'id' based on your user structure

        // return redirect('/'); // Redirect to the dashboard or any desired page after login

        try {
            $firebaseAuth = Firebase::auth();
            $login = $firebaseAuth->signInWithEmailAndPassword($request['email'], $request['password']);
            $user = $login->data();

            return redirect('/');
        }
        catch (Exception  $e){
            $request->session()->flash('error', "login gagal");
        }

    }

    private function getUserDataByUsername($username)
    {
        $snapshot = $this->database
            ->getReference('econnect/users')
            ->orderByChild('username')
            ->equalTo($username)
            ->getSnapshot();

        if (!$snapshot->exists()) {
            return null;
        }

        $userData = $snapshot->getValue();

        // Assuming each user has a unique ID field in the database
        $userId = key($userData);

        // Include the user's ID in the data array
        $userData[$userId]['id'] = $userId;

        return $userData[$userId];
    }
}
