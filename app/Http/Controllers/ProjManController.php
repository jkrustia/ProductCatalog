<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjManController extends Controller
{
    // // Dummy data
    // private $prodman = [
    //     [
    //         'id' => 1,
    //         'name' => 'David Miller',
    //         'username' => 'david',
    //         'email' => 'david@example.com',
    //         'password' => 'pm12345',
    //         'permissions' => ['View Products', 'Edit Products', 'Assign Tasks'],
    //     ],
    //     [
    //         'id' => 2,
    //         'name' => 'Emma Wilson',
    //         'username' => 'emma',
    //         'email' => 'emma@example.com',
    //         'password' => 'pm23456',
    //         'permissions' => ['View Products', 'Limited Access'],
    //     ],
    //     [
    //         'id' => 3,
    //         'name' => 'Frank Harris',
    //         'username' => 'frank',
    //         'email' => 'frank@example.com',
    //         'password' => 'pm34567',
    //         'permissions' => ['Read Only'],
    //     ],
    // ];

    public function index()
    {
        return redirect()->route('productmanager.index'); // Redirects to UsersController@productManagerList
    }

    public function show($id)
    {
        $prodman = collect($this->prodman)->firstWhere('id', $id);
        if (!$prodman) {
            abort(404);
        }
        return view('superadmin.productmanager.show', compact('prodman'));
    }

    public function edit($id)
    {
        $prodman = collect($this->prodman)->firstWhere('id', $id);
        if (!$prodman) {
            abort(404);
        }
        return view('superadmin.productmanager.edit', compact('prodman'));
    }

    public function create()
    {
        return view('superadmin.productmanager.create');
    }

    public function destroy($id)
    {
        // Dummy delete logic
        return redirect()->route('productmanager.index');
    }
    public function dashboard()
    {
        return view('productmanager.dashboard');
    }
}