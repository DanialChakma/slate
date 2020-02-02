<?php

namespace App\Http\Controllers;

use App\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndustryController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:190',
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $industries =  Industry::orderBy('created_at','DESC')->paginate(10);        
	    return View("industries.list",[ "industries" => $industries ]);
    }

    /**
     * @return mixed
     */
	public function search(){
        $q = request()->get('q');
        if( $q != "" ){
            $industries = Industry::Where('name','LIKE', '%' . $q . '%')->paginate(10);
            if(count($industries) > 0 ){
                return View("industries.list",[ "q"=>$q, "industries"=>$industries ])->withQuery($q);
            }else{
                return View("industries.list",[ "msg"=>"No Details found. Try to search again !" ] )->withQuery($q);
            }
        }else{
            return View("industries.list",[ "msg"=>"Please,Enter something to search for." ]);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return View('industries.create');
    }

    /**
     * @param Industry $industry
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Industry $industry){
        return View('industries.show', [ 'industry' => $industry ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $inputs = request()->input();
        $this->validator($inputs)->validate();

        return redirect()
            ->route('industries.show', Industry::create($inputs))
            ->with("status", true)
            ->with("message", $this->savedMessage);
    }

    /**
     * @param Industry $industry
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Industry $industry)
    {
        return View('industries.edit', [ 'industry' => $industry ]);
    }

    /**
     * @param Industry $industry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Industry $industry)
    {
        $inputs = request()->input();
        $this->validator($inputs)->validate();
        $industry->update($inputs);

        return redirect()
            ->route('industries.show', $industry)
            ->with("status", true)
            ->with("message", $this->updatedMessage);
    }

    /**
     * @param Industry $industry
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Industry $industry)
    {
        return View('industries.delete', [ 'industry' => $industry ]);
    }

    /**
     * @param Industry $industry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmDelete(Industry $industry)
    {
        $messages = [];
        if($industry->clientCompanies->count() > 0){
            $client_string = "";
            $limit = 15;
            $total = $industry->clientCompanies->count();
            foreach( $industry->clientCompanies()->take($limit)->get() as $company){
                if( $client_string == "" ){
                    $client_string = '<a style="color: red;" target="_blank" href="'.route('clientCompanies.show', ['id' => $company->id]).'">'.$company->company_name.'</a>';
                }else{
                    $client_string .= '<a style="color: red;" target="_blank" href="'.route('clientCompanies.show', ['id' => $company->id]).'">'.$company->company_name.'</a>';
                }
            }
            if( $total > $limit ){
                $restCount =  $total-$limit;
                $client_string = $client_string.'... (and '.$restCount.' more)';
            }
            $messages[] = "You have following client companies for this industry.<br/>".$client_string;
        }
        if(count($messages) == 0){
            $industry->delete();

            return redirect()
                ->route('industries')
                ->with("status", true)
                ->with("message", $this->deletedMessage);
        }else{
            $messageStr = "";
            foreach($messages as $key => $message){
                $messageStr .= ($key+1) . '. ' . $message . '.<br />';
            }

            return redirect()
                ->route('industries.delete', [ 'id' => $industry->id ])
                ->with("status", false)
                ->with("message", $messageStr . "** Please delete those information first **");
        }
    }

	  /**
     * @param Industry $industry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmDeleteAjax(Industry $industry)
    {
        $messages = [];
        if( $industry->clientCompanies->count() > 0 ){
            $messages[] = "You have client companies information for this industry";
        }
        if( count($messages) == 0 ){
            $industry->delete();
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
