<?php

namespace App\Http\Controllers;

use App\ClientCompany;
use App\ClientCompanyContactPerson;
use App\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exception;

class ClientCompanyController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company_name' => 'required|string|max:190',
            'remarks' => 'nullable|string',
            'industry_id'=>'required|integer'
        ]);
    }

    protected function ClientValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:190',
            'designation'=>'required|string|max:190',
            'phone' => 'required|string|max:40',
            'email' => 'required|string|email|max:100',
            'remarks' => 'nullable|string'
        ]);
    }

    public function index(){
        $clientCompanies =  ClientCompany::orderBy('created_at','DESC')->paginate(10);
        return View("clientCompanies.list",["clientCompanies"=>$clientCompanies]);
    }

    public function search(){
        $q = request()->query('q');
        if( $q ){
            $clientCompanies =  ClientCompany::Where('company_name','LIKE', '%' . $q . '%')->orWhere('remarks','LIKE','%' . $q . '%')->paginate(10);
            if(count($clientCompanies) > 0 ){
                return View("clientCompanies.list",["clientCompanies"=>$clientCompanies,"q"=>$q])->withQuery($q);
            }else{
                return View("clientCompanies.list",["msg"=>'No Details found. Try to search again !'])->withQuery($q);
            }
        }
    }

    public function create(){
        $industries = Industry::all();
        return View('clientCompanies.create',[ 'industries' => $industries ]);
    }

    public function show(ClientCompany $clientCompany){
       // dd($company);
        return View('clientCompanies.show', [ 'clientCompany' => $clientCompany ]);
    }

    public function store()
    {
        $inputs = request()->input();
        $clients = array();

        $client_names = request()->input('name');
        $client_designations = request()->input('designation');
        $client_emails = request()->input('email');
        $client_phones = request()->input('phone');
        if( is_array($client_names) && count($client_names) > 0 ){
            foreach( $client_names as $index=>$name ){
                $clients[$index]['name']= $name;
                $clients[$index]['designation']= $client_designations[$index];
                $clients[$index]['email']= $client_emails[$index];
                $clients[$index]['phone']= $client_phones[$index];
            }
        }


        $company_inputs['company_name'] = $inputs['company_name'];
        $company_inputs['industry_id'] = $inputs['industry_id'];
        $company_inputs['remarks'] = $inputs['remarks'];

        $this->validator($company_inputs)->validate();
        foreach($clients as $client){
            $this->ClientValidator($client);
        }

        try{

            if( count($clients) > 0 ){
                $company = ClientCompany::create($company_inputs);
                if( $company && $company->exists() ){
                    $company->clientCompanyContactPersons()->createMany($clients);
                    return redirect()
                        ->route('clientCompanies.show', $company )
                        ->with("status", true)
                        ->with("message", $this->savedMessage);
                }else{
                    return redirect()
                        ->route('clientCompanies.create')
                        ->with("status", true)
                        ->with("message", $this->savedFailed);
                }
            }else{
                return redirect()
                    ->route('clientCompanies.create')
                    ->with("status", true)
                    ->with("message", "Client Company should have one Contact Person.");
            }

        }catch(Exception $ex){
            return redirect()
                ->route('clientCompanies.create')
                ->with("status", true)
                ->with("message", $this->savedFailed);
        }
    }

    public function edit(ClientCompany $clientCompany)
    {
        $industries = Industry::all();
        return View('clientCompanies.edit', [ 'clientCompany' => $clientCompany, 'industries' => $industries ]);
    }

    public function update(ClientCompany $clientCompany)
    {
        $inputs = request()->input();

        $client_names = request()->input('name');
        $client_designations = request()->input('designation');
        $client_emails = request()->input('email');
        $client_phones = request()->input('phone');

        $client_names_new = request()->input('name_new');
        $client_designations_new = request()->input('designation_new');
        $client_emails_new = request()->input('email_new');
        $client_phones_new = request()->input('phone_new');

        $client_persons = request()->input('contact_persons');
        $client_persons = is_array($client_persons) && count($client_persons)>0 ? $client_persons: array();
        $client_persons_old = request()->input('contact_persons_old');
        $client_persons_old = is_array($client_persons_old) && count($client_persons_old) > 0 ? $client_persons_old: array();

        $clients_update = array();
        if(is_array($client_names) && count($client_names) > 0 ){
            foreach( $client_names as $index=>$name ){
                $clients_update[$index]= array(
                    "name"=>$name,
                    "designation"=>$client_designations[$index],
                    "email" => $client_emails[$index],
                    "phone" =>$client_phones[$index]
                );
            }
        }

        $clients_new = array();
        if(is_array($client_names_new) && count($client_names_new) > 0 ){
            foreach( $client_names_new as $index=>$name ){
                $clients_new[]= array(
                    "name"=>$name,
                    "designation"=>$client_designations_new[$index],
                    "email" => $client_emails_new[$index],
                    "phone" =>$client_phones_new[$index]
                );
            }
        }


        $company_inputs['company_name'] = $inputs['company_name'];
        $company_inputs['industry_id'] = $inputs['industry_id'];
        $company_inputs['remarks'] = $inputs['remarks'];

        $this->validator($company_inputs)->validate();
        foreach($clients_update as $client){
            $this->ClientValidator($client)->validate();
        }
        foreach($clients_new as $client){
            $this->ClientValidator($client)->validate();
        }


        if(  count($clients_update) > 0 || count($clients_new) > 0 ){
            $clientCompany->update($company_inputs);
            foreach($clients_update as $contact_id=>$contact_details ){
                $clientCompany->clientCompanyContactPersons()->where('id',$contact_id)->update($contact_details);
            }

            $deleted_ids = array_diff($client_persons_old,$client_persons);
            if( count($deleted_ids) > 0 ){
                $clientCompany->clientCompanyContactPersons()->whereIn('id',$deleted_ids)->delete();
            }



            $clientCompany->clientCompanyContactPersons()->createMany($clients_new);

            return redirect()
                ->route('clientCompanies.show', $clientCompany)
                ->with("status", true)
                ->with("message", $this->updatedMessage);

        }else{
            return redirect()
                ->route('clientCompanies.edit', $clientCompany)
                ->with("status", false)
                ->with("message", "Client Company should have one Contact Person.");

        }
    }

    public function delete(ClientCompany $clientCompany)
    {
        return View('clientCompanies.delete', [ 'clientCompany' => $clientCompany ]);
    }

    public function confirmDelete(ClientCompany $clientCompany)
    {
        $messages = [];
        if($clientCompany->clientCompanyContactPersons->count() > 0){
            $messages[] = "You have contact person information for this company";
        }
        if($clientCompany->meetings->count() > 0){
            $messages[] = "You have meeting information for this company";
        }

        if(count($messages) == 0){
            $clientCompany->delete();

            return redirect()
                ->route('clientCompanies')
                ->with("status", true)
                ->with("message", $this->deletedMessage);
        }else{
            $messageStr = "";
            foreach($messages as $key => $message){
                $messageStr .= ($key+1) . '. ' . $message . '.<br />';
            }

            return redirect()
                ->route('clientCompanies.delete', ['id' => $clientCompany->id])
                ->with("status", false)
                ->with("message", $messageStr . "** Please delete those information first **");
        }
    }

    public function confirmDeleteAjax(ClientCompany $clientCompany)
    {
        $messages = [];
        if($clientCompany->clientCompanyContactPersons->count() > 0){

            $messages[] = "You have contact person information for this company";
        }
        if($clientCompany->meetings->count() > 0){
            $meeting_string = "";
            $limit = 15;
            $total = $clientCompany->meetings()->count();
            foreach( $clientCompany->meetings()->take($limit)->get() as $meeting ){
                if( $meeting_string == "" ){
                    $meeting_string = '<a style="color: red;" target="_blank" href="'.route('meetings.show', ['id' => $meeting->id]).'">'.$meeting->title.'</a>';
                }else{
                    $meeting_string .= ', <a style="color: red;" target="_blank" href="'.route('meetings.show', ['id' => $meeting->id]).'">'.$meeting->title.'</a>';
                }
            }
            if( $total > $limit ){
                $restCount =  $total-$limit;
                $meeting_string = $meeting_string.'... (and '.$restCount.' more)';
            }
            $messages[] = "You have following meeting for this client company.<br/>Meetings: ".$meeting_string;
        }

        if(count($messages) == 0){
            $clientCompany->delete();
            return collect(array("status"=>"OK","message"=>$this->deletedMessage))->toJson();
        }else{
            $messageStr = "";
            foreach($messages as $key => $message){
                $messageStr .= ($key+1) . '. ' . $message . '.<br />';
            }
            $messageStr = $messageStr . "** Please delete those information first **";
            return collect(array("status"=>"NOK","message"=>$messageStr))->toJson();
        }
    }
}
