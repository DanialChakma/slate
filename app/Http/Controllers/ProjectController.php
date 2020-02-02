<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
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
            'description' => 'nullable|string',
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $page = request()->has('page') ? request()->get('page') : 0;
        $projects = Cache::remember('projects_page_' . $page, 3, function() {
            return Project::orderBy('created_at','DESC')->paginate(10);
        });

        return View('projects.list', [ 'projects' => $projects ]);
    }

    /**
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Project $project){
        return View('projects.show', [ 'project' => $project ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::all(['id', 'name']);
        return View('projects.create', ['departments' => $departments]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $inputs = request()->input();
        $this->validator($inputs)->validate();
        if(empty($inputs['description'])) {
            $inputs['description'] = 'Not added.';
        }

        return redirect()
            ->route('projects.show', Project::create($inputs))
            ->with("status", true)
            ->with("message", 'Project Type Successfully Added.');
    }

    /**
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Project $project)
    {
        $departments = Department::all(['id', 'name']);
        return View('projects.edit', [ 'project' => $project, 'departments' => $departments]);
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Project $project)
    {
        $inputs = request()->input();
        $this->validator($inputs)->validate();
        if(empty($inputs['description'])) {
            $inputs['description'] = 'Not added.';
        }
        $project->update($inputs);

        return redirect()
            ->route('projects.show', $project)
            ->with("status", true)
            ->with("message", 'Project Type Successfully Updated.');
    }

    /**
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Project $project)
    {
        return View('projects.delete', [ 'project' => $project ]);
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */

    public function confirmDelete(Project $project){
        $messages = [];
        if($project->meetings->count() > 0){
            $messages[] = "You have meeting information for this project";
        }
        if($project->surveys->count() > 0){
            $messages[] = "You have survey information for this project";
        }
        if(count($messages) == 0){
            $project->delete();

            return redirect()
                ->route('projects')
                ->with("status", true)
                ->with("message", $this->deletedMessage);
        }else{
            $messageStr = "";
            foreach($messages as $key => $message){
                $messageStr .= ($key+1) . '. ' . $message . '.<br />';
            }

            return redirect()
                ->route('projects.delete', ['id' => $project->id])
                ->with("status", false)
                ->with("message", $messageStr . "** Please delete those information first **");
        }
    }

    public function confirmDeleteAjax(Project $project){
        $messages = [];
        if($project->meetings->count() > 0){
            $meeting_string = "";
            $limit = 15;
            $total = $project->meetings->count();
            foreach( $project->meetings()->take($limit)->get() as $meeting){
                if( $meeting_string == "" ){
                    $meeting_string = '<a style="color: red;" target="_blank" href="'.route('meetings.show', ['id' => $meeting->id]).'">'.$meeting->title.'</a>';
                }else{
                    $meeting_string .= '<a style="color: red;" target="_blank" href="'.route('meetings.show', ['id' => $meeting->id]).'">'.$meeting->title.'</a>';
                }
            }
            if( $total > $limit ){
                $restCount =  $total-$limit;
                $meeting_string = $meeting_string.'... (and '.$restCount.' more)';
            }
            $messages[] = "You have following meeting for this project.<br/>Meetings: ".$meeting_string;
        }
        if($project->surveys->count() > 0){
            $survey_string = "";
            $limit = 15;
            $total = $project->surveys->count();
            foreach( $project->surveys()->take($limit)->get() as $survey){
                if( $survey_string == "" ){
                    $survey_string = '<a style="color: red;" target="_blank" href="'.route('surveys.show', ['id' => $survey->id]).'">'.$survey->name.'</a>';
                }else{
                    $survey_string .= '<a style="color: red;" target="_blank" href="'.route('surveys.show', ['id' => $survey->id]).'">'.$survey->name.'</a>';
                }
            }
            if( $total > $limit ){
                $restCount =  $total-$limit;
                $survey_string = $survey_string.'... (and '.$restCount.' more)';
            }
            $messages[] = "You have following survey for this project.<br/>Surveys: ".$survey_string;
        }
        if( count($messages) == 0 ){
            $project->delete();
            return collect(array("status"=>"OK","message"=>$this->deletedMessage))->toJson();
        }else{
            $messageStr = "";
            foreach($messages as $key => $message){
                $messageStr .= ($key+1) . '. ' . $message . '.<br />';
            }
            $messageStr = $messageStr . "** Please delete those information first **";
            return collect(array("status"=>"NOK","message"=>$messageStr));
        }
    }

    public function search(){
        $q = request()->get('q');
        if($q){
            $projects = Project::where('name','LIKE','%'.$q.'%')->orWhere('description','LIKE','%'.$q.'%')->paginate(10);
            if($projects->count() > 0 ){
                return View('projects.search',["q"=>$q,'projects'=>$projects]);
            }else{
                return View('projects.search',['msg'=>'No Details found. Try to search again !']);
            }

        }else{
            return View('projects.search',['msg'=>"Please,Enter something to search for."]);
        }
    }
}
