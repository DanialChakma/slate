<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Role;
use App\Department;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{

    /**
     * @param array $data
     * @return mixed
     */

    protected function validator(array $data, $id = 0)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:190',
            'email' => [
                'required','string','email','max:190',
                Rule::unique('users')->ignore($id),
            ],
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer',
            'department_id' => 'required|integer',
            'supervisor_id' => 'nullable|integer'
        ]);
    }

    protected function updateValidator(array $data, $id = 0)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:190',
            'phone' => 'required|string',
            'role_id' => 'required|integer',
            'department_id' => 'required|integer',
            'supervisor_id' => 'nullable|integer'
        ]);
    }

    protected function passwordValidator(array $data)
    {
        return Validator::make($data, [
            'password' => 'required|min:6|confirmed',
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View('users.list', [ 'users' => User::orderBy('created_at','DESC')->paginate(10) ]);

    }

    public function show(User $user)
    {
        return View('users.show', [ 'user' => $user ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('users.create',['roles' => Role::all(),'supervisors' => User::all()->where('role_id',"<=", 2),'departments'=>Department::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = request()->input();
        $this->validator($inputs)->validate();
        $inputs['password'] = bcrypt($request->input('password'));

        //If not admin, supervisor is mandatory
        if($inputs['role_id'] != 1){
            if($inputs['supervisor_id'] == ""){
                return redirect()
                    ->back()
                    ->withInput($inputs)
                    ->with("status", false)
                    ->with("message", 'All user except Admin must have a supervisor.');
            }
        }
        $user = User::create($inputs);

        return redirect()
            ->route('users.show', $user)
            ->with("status", true)
            ->with("message", 'New User Successfully Added.');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */


    public function edit(User $user)
    {
        return View('users.edit', [
            'user' => $user,
            'roles' => Role::all(),
            'supervisors' => User::where('role_id',"<=",2)->where('id','<>',$user->id)->get(),
            'departments'=>Department::all() ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, Request $request)
    {
        $inputs = request()->input();
        $this->updateValidator($inputs, $user->id)->validate();

        //If not admin, supervisor is mandatory
        if($inputs['role_id'] != 1){
            if($inputs['supervisor_id'] == ""){
                return redirect()
                    ->back()
                    ->withInput($inputs)
                    ->with("status", false)
                    ->with("message", 'All user except Admin must have a supervisor.');
            }
        }

        $user->update($inputs);

        return redirect()
            ->route('users.show',$user)
            ->with("status", true)
            ->with("message", $this->updatedMessage);
    }

    public function updatePassword(User $user, Request $request)
    {
        $inputs = request()->input();
        $this->passwordValidator($inputs)->validate();
        $inputs['password'] = bcrypt($request->input('password'));
        //$inputs['password_confirmation'] = bcrypt($request->input('password_confirmation'));
        $user->update($inputs);
        return redirect()
            ->route('users.show',$user)
            ->with("status", true)
            ->with("message", 'User Successfully Updated.');
    }


    public function userChangePasswordView(User $user)
    {
        return View('users.changePassword', [ 'user' => $user ]);
    }

    public function userChangePassword (User $user) {

        $current_password = request()->input('current_password');
        $current_hashed_password = auth()->user()->getAuthPassword();

        if (Hash::check($current_password, $current_hashed_password)) {

            $inputs = request()->input();
            $this->passwordValidator($inputs)->validate();

            $user->password = bcrypt(request()->input('password'));
            $user->save();
            //Session::flash('message','Your account has been updated!');

            auth()->logout();
            return redirect()->route('login')
                ->with("status", true)
                ->with("message", "Your password has been updated. Please login again.");
        } else {
            return redirect()->route('users.userChangePasswordView', [ 'id' => auth()->user()->id])
                ->with("status", false)
                ->with("message", "Current password does not match!");
        }
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function delete(User $user)
    {
        return View('users.delete', [ 'user' => $user ]);
    }

    public function confirmDelete(User $user){
        $messages = [];
        if($user->meetings->count() > 0){
            $messages[] = "You have meeting information for this project";
        }
        if($user->employeesUnderHim->count() > 0){
            $messages[] = "You have survey information for this project";
        }
        if(count($messages) == 0){
            $user->delete();

            return redirect()
                ->route('users')
                ->with("status", true)
                ->with("message", $this->deletedMessage);
        }else{
            $messageStr = "";
            foreach($messages as $key => $message){
                $messageStr .= ($key+1) . '. ' . $message . '.<br />';
            }

            return redirect()
                ->route('users.delete', ['id' => $user->id])
                ->with("status", false)
                ->with("message", $messageStr . "** Please delete those information first **");
        }
    }

    public function confirmDeleteAjax(User $user){
        $messages = [];
        if($user->meetings->count() > 0){
            $messages[] = "You have meeting information for this project";
        }
        if($user->employeesUnderHim->count() > 0){
            $user_string = "";
            $limit = 15;
            $total = $user->employeesUnderHim()->count();
            foreach( $user->employeesUnderHim()->take($limit)->get() as $usr){
                if( $user_string == "" ){
                    $user_string = '<a style="color: red;" target="_blank" href="'.route('users.show', ['id' => $usr->id]).'">'.$usr->name.'</a>';
                }else{
                    $user_string .= ', <a style="color: red;" target="_blank" href="'.route('users.show', ['id' => $usr->id]).'">'.$usr->name.'</a>';
                }
            }
            if( $total > $limit ){
                $restCount =  $total-$limit;
                $user_string = $user_string.'... (and '.$restCount.' more)';
            }
            $messages[] = "You have following users under this user.<br/>Users: ".$user_string;
        }
        if(count($messages) == 0){
            $user->delete();
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
