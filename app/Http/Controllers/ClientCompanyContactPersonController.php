<?php

namespace App\Http\Controllers;
use \Illuminate\Support\Facades\View;
use App\ClientCompanyContactPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ClientCompany;
class ClientCompanyContactPersonController extends Controller
{
    //

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'client_company_id' => 'required|int',
            'name' => 'required|string|max:190',
            'designation'=>'required|string|max:190',
            'phone' => 'required|string|max:40',
            'email' => 'required|string|max:100',
            'remarks' => 'nullable|string'
        ]);
    }



    public function index(ClientCompany $clientCompany){
        $clientCompanyContactPersons =  ClientCompanyContactPerson::Where('client_company_id','=',$clientCompany->id)->orderBy('created_at','desc')->paginate(10);
        return View("clientCompaniesContacts.list",["company_id"=>$clientCompany->id,"clientCompanyContactPersons"=>$clientCompanyContactPersons]);
    }

    public function search(){
        $q = request()->input('q');
        $company_id = request()->input('company_id');
        if( $q ){
            $clientCompanyContactPersons = ClientCompanyContactPerson::Where('client_company_id','=',$company_id)
                                            ->where(
                                                    function($query)use($q){
                                                        $query->Where('name','LIKE', '%' . $q . '%')->orWhere('designation','LIKE','%'.$q.'%')->orWhere('email','LIKE','%'.$q.'%')->orWhere('phone','LIKE','%'.$q.'%')->orWhere('remarks','LIKE','%'.$q.'%')->orderBy('created_at','DESC');
                                                    }
                                            )->paginate(10);

            if(count($clientCompanyContactPersons) > 0 ){
                return View("clientCompaniesContacts.search",["q"=>$q,"company_id"=>$company_id,"clientCompanyContactPersons"=>$clientCompanyContactPersons])->withQuery($q);
            }else{
                return View("clientCompaniesContacts.search",["company_id"=>$company_id,"msg"=>'No Details found. Try to search again !'])->withQuery($q);
            }
        }else{
            return View("clientCompaniesContacts.search",["company_id"=>$company_id,"msg"=>'Please, Enter Something to Search.'])->withQuery($q);
        }
    }

    public function create(ClientCompany $clientCompany){
      //  $industries = Industry::all();
        return View('clientCompaniesContacts.create',[ 'clientCompany' => $clientCompany ]);
    }

    public function show(ClientCompanyContactPerson $clientCompanyContactPerson){
        //dd($clientCompanyContactPerson);
        return View('clientCompaniesContacts.show', [ 'clientCompanyContactPerson' => $clientCompanyContactPerson ]);
    }

    public function store()
    {
        $inputs = request()->input();
       // dd($inputs);
        $this->validator($inputs)->validate();

        return redirect()
            ->route('clientCompaniesContacts',['id'=>ClientCompanyContactPerson::create($inputs)->client_company_id])
            ->with("status", true)
            ->with("message", $this->savedMessage);
    }

    public function edit(ClientCompanyContactPerson $clientCompanyContactPerson)
    {
        //dd($clientCompanyContactPerson);
        return View('clientCompaniesContacts.edit', [ 'clientCompanyContactPerson' => $clientCompanyContactPerson ]);
    }

    public function update(ClientCompanyContactPerson $clientCompanyContactPerson)
    {
        $inputs = request()->input();
        $this->validator($inputs)->validate();
        $clientCompanyContactPerson->update($inputs);

        return redirect()
            ->route('clientCompaniesContacts.show', $clientCompanyContactPerson)
            ->with("status", true)
            ->with("message", $this->updatedMessage);
    }

    public function delete(ClientCompanyContactPerson $clientCompanyContactPerson)
    {
        return View('clientCompaniesContacts.delete', [ 'clientCompanyContactPerson' => $clientCompanyContactPerson ]);
    }

    public function confirmDelete(ClientCompanyContactPerson $clientCompanyContactPerson)
    {
        $client_company_id = $clientCompanyContactPerson->client_company_id;
        $clientCompanyContactPerson->delete();

        return redirect()
            ->route('clientCompaniesContacts',['id'=>$client_company_id])
            ->with("status", true)
            ->with("message", $this->deletedMessage);
    }
}
