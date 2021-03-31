<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HasImageUploads;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    use HasImageUploads;

    public function __construct()
    {
        $this->middleware('role:admin')->except(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = !is_null($request->input('page')) ? Company::orderBy('name')->paginate(10) : Company::orderBy('name')->get();

        return $companies;
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
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'logo'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $company = Company::create([
            'name' => request('name'),
            'email' => request('email'),
        ]);

        if($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = Str::slug($request->input('name')).'_'.time();
            $folder = 'logos/';
            $filePath = $folder . $filename. '.' . $image->getClientOriginalExtension();
            $this->uploadFile($image, '/'.$folder, 'local', $filename);

            $company->logo = Storage::url($filePath);
            $company->save();
        } else {
            $company->logo = '/storage/logos/logo.png';
            $company->save();
        }

        return $company;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $company = Company::findOrFail($company->id)->loadMissing('users');

        return $company;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        request()->validate([
            'name' => 'required',
            'email'=> 'required|email',
            'logo' => !is_file(request('logo')) ? '' : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data =[
            'name' => request('name'),
            'email' => request('email'),
        ];

        $company->update($data);

        if($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = Str::slug($request->input('name')).'_'.time();
            $folder = 'logos/';
            $filePath = $folder . $filename. '.' . $image->getClientOriginalExtension();
            $this->uploadFile($image, '/'.$folder, 'local', $filename);

            $company->logo = Storage::url($filePath);
            $company->save();
        }

        return $company;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        try {
            $company->delete();
        } catch (\Throwable $th) {
            return response('There was an error deleting the company', Response::HTTP_BAD_REQUEST);
        }
    }
}
