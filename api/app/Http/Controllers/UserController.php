<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin')->except(['show', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = !is_null($request->input('page')) ? User::orderBy('first_name')->paginate(10) : User::all();

        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'first_name'     => 'required',
            'last_name'      => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $password = request('password') ? bcrypt(request('password')) : '';

        $user = User::create([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'phone' => request('phone'),
            'password' => $password
        ]);

        if(request('company_id')) {
            $company = Company::find(request('company_id'));
            $user->company()->associate($company);
            $user->save();
        }

        $user->assignRole(request('role'));

        return $user;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user = User::findOrFail($user->id);

        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        request()->validate([
            'first_name'     => 'required',
            'last_name'      => 'required',
            'email' => 'required|email',
        ]);

        $data =[
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'phone' => request('phone'),
        ];

        if(request('company_id')) {
            $company = Company::find(request('company_id'));
            $user->company()->associate($company);
            $user->save();
        } else {
            if($user->company()->exists()) {
                $user->company()->dissociate();
            }
        }

        $user->update($data);
        $user->assignRole(request('role'));

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Throwable $th) {
            return response('There was an error deleting the user', Response::HTTP_BAD_REQUEST);
        }
    }
}
