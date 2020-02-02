<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
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
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
        ]);
    }

    public function index(){
         $departments =  Department::orderBy('created_at','DESC')->paginate(10);        
	 return View("departments.list",["departments"=>$departments]);
    }

    public function create(){
        return View('departments.create');
    }

    public function show(Department $department){
        return View('departments.show', [ 'department' => $department ]);
    }

    public function store()
    {
        $inputs = request()->input();
        $this->validator($inputs)->validate();

        return redirect()
            ->route('departments.show', Department::create($inputs))
            ->with("status", true)
            ->with("message", $this->savedMessage);
    }

    public function edit(Department $department)
    {
        return View('departments.edit', [ 'department' => $department ]);
    }

    public function update(Department $department)
    {
        $inputs = request()->input();
        $this->validator($inputs)->validate();
        $department->update($inputs);

        return redirect()
            ->route('departments.show', $department)
            ->with("status", true)
            ->with("message", $this->updatedMessage);
    }

    public function delete(Department $department)
    {
        return View('departments.delete', [ 'department' => $department ]);
    }

    public function confirmDelete(Department $department){
        $messages = [];
        if($department->users->count() > 0){
            $messages[] .= "You have user information under this department";
        }
        if($department->projects->count() > 0){
            $messages[] .= "You have project information under this department";
        }
        if($department->surveys->count() > 0){
            $messages[] .= "You have survey information under this department";
        }

        if(count($messages) == 0){
            $department->delete();

            return redirect()
                ->route('departments')
                ->with("status", true)
                ->with("message", $this->deletedMessage);
        }else{
            $messageStr = "";
            foreach($messages as $key => $message){
                $messageStr .= ($key+1) . '. ' . $message . '.<br />';
            }

            return redirect()
                ->route('departments.delete', ['id' => $department->id])
                ->with("status", false)
                ->with("message", $messageStr . "** Please delete those information first **");
        }
    }

    public function confirmDeleteAjax(Department $department){

        $messages = [];
        if($department->users->count() > 0){
            $user_string = "";
            $limit = 15;
            $total = $department->users()->count();
            foreach($department->users()->take($limit)->get() as $user){
                if( $user_string == "" )
                $user_string = '<a style="color: red;" target="_blank" href="'.route('users.show', ['id' => $user->id]).'">'.$user->name.'</a>';
                else{
                    $user_string .= ', <a style="color: red;" target="_blank" href="'.route('users.show', ['id' => $user->id]).'">'.$user->name.'</a>';
                }
            }
            if( $total > $limit ){
                $restCount =  $total-$limit;
                $user_string = $user_string.'... (and '.$restCount.' more)';
            }
            $messages[] = "You have following users under this department.<br/>Users: ".$user_string;
        }
        if($department->projects->count() > 0){
            $project_string = "";
            $limit = 15;
            $total = $department->projects()->count();
            foreach($department->projects()->take($limit)->get() as $project){
                if( $project_string == "" )
                    $project_string = '<a style="color: red;" target="_blank" href="'.route('projects.show', ['id' => $project->id]).'">'.$project->name.'</a>';
                else{
                    $project_string .= ', <a style="color: red;" target="_blank" href="'.route('projects.show', ['id' => $project->id]).'">'.$project->name.'</a>';
                }
            }
            if( $total > $limit ){
                $restCount =  $total-$limit;
                $user_string = $user_string.'... (and '.$restCount.' more)';
            }
            $messages[] = "You have following project under this department.<br/>Projects: ".$project_string;
        }
        if($department->surveys->count() > 0){
            $survey_string = "";
            $limit = 15;
            $total = $department->surveys()->count();
            foreach($department->surveys()->take($limit)->get() as $survey){
                if( $survey_string == "" )
                    $survey_string = '<a style="color: red;" target="_blank" href="'.route('surveys.show', ['id' => $survey->id]).'">'.$survey->name.'</a>';
                else{
                    $survey_string .= ', <a style="color: red;" target="_blank" href="'.route('surveys.show', ['id' => $survey->id]).'">'.$survey->name.'</a>';
                }
            }
            if( $total > $limit ){
                $restCount =  $total-$limit;
                $survey_string = $survey_string.'... (and '.$restCount.' more)';
            }
            $messages[] = "You have following survey under this department.<br/>Surveys: ".$survey_string;
        }

        if(count($messages) == 0){
            $department->delete();
            return collect( array("status"=>"OK","message"=>$this->deletedMessage) )->toJson();
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