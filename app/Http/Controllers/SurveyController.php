<?php

namespace App\Http\Controllers;
use App\Meeting;
use App\User;
use Carbon\Carbon;
use App\MeetingSurveryResult;
use App\Department;
use App\Project;
use App\Survey;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    //

    protected function validator(array $data) {
        return Validator::make($data,
            [   'name' => 'required|string|max:190',
                'remarks' => 'nullable|string|max:190',
                'project_id' => 'required|integer',
                'department_id' => 'required|integer'
            ]);
    }

    public function index() {
        $surveys = Survey::orderBy('created_at', 'desc')->paginate(10);
        return View('surveys.list', ['surveys' => $surveys]);
    }

    public function edit(Survey $survey) {

        $questions = Question::all();
        return View('surveys.edit', ['survey' => $survey,'questions'=>$questions]);
    }

    public function update(Survey $survey) {
        $inputs = request()->input();
        $department_id = $inputs['department_id'];
        $project_id = $inputs['project_id'];
        $question_ids = $inputs['selected_question'];

        $departmentModel = Department::find($department_id);
        $projectModel = Project::find($project_id);
        if( !$departmentModel ){
            return redirect()
                ->route('surveys.edit',$survey)
                ->with("status", false)
                ->with("message", "Please,Select Department.");
        }

        if( !$projectModel ){
            return redirect()
                ->route('surveys.edit',$survey)
                ->with("status", false)
                ->with("message", "Please,Select Project.");
        }
        if( is_array($question_ids) && count($question_ids) <= 0 ){
            return redirect()
                ->route('surveys.create')
                ->with("status", false)
                ->with("message", "Please,Select at least one question to create survey.");
        }

        $department_name = $departmentModel->name . ":" .$projectModel->name;
        $survey_inputs['department_id'] = $department_id;
        $survey_inputs['project_id'] = $project_id;
        $survey_inputs['name'] = $department_name;


        $this->validator($survey_inputs)->validate();
        if( $survey->update($survey_inputs) ){
            $survey->questions()->sync($question_ids);
            return redirect()
                    ->route('surveys.show', $survey)
                    ->with("status", true)
                    ->with("message", $this->updatedMessage);
        }else{
            return redirect()
                    ->route('surveys.edit',$survey)
                    ->with("status", false)
                    ->with("message", $this->failedToUpdateMessage);
        }
    }

    public function view(Survey $survey) {
        return View('surveys.show', [ 'survey' => $survey ]);
    }
    public function getProjectUnderDepartment() {
        $id = request()->input("department_id");
        $projects = Project::all(["id", "name", "department_id"])->where("department_id", $id)->toJson();
        return $projects;
    }
    public function getContactsUnderCompany() {
        $id = request()->input("company_id");
        $contacts = ClientCompanyContactPerson::all(["id", "name", "designation", "email", "phone", "client_company_id"])->where("client_company_id", "=", $id)->toJson();
        return $contacts;
    }
    public function getContactDetails() {
        $id = request()->input("contact_id");
        $contact = ClientCompanyContactPerson::all(["id", "name", "designation", "email", "phone"])->where("id", $id)->toJson();
        return $contact;
    }
   /*
    public function create() {
        $departments = Department::all(["id", "name"]);
        $projects = Project::where('department_id', $departments->first()->id)->get();
        return View('surveys.create', ["departments" => $departments, 'projects' => $projects]);
    } */
    public function create(){
        $departments = Department::all(["id", "name"]);
        $projects = Project::where('department_id', $departments->first()->id)->get();
        $questions = Question::all();
        return View('surveys.create', ["departments" => $departments, 'projects' => $projects,'questions'=>$questions]);
    }
    public function delete(Survey $survey) {
        return View('surveys.delete', [ 'survey' => $survey ]);
    }

    public function confirmDelete(Survey $survey) {
        $messages = [];
//        if($survey->meetings->count() > 0){
//            $messages[] .= "You have meeting information under this survey";
//        }
//        if($survey->questions->count() > 0){
//            $messages[] .= "You have questions under this survey";
//        }
        if($survey->meetingSurveryResults->count() > 0){
            $messages[] = "You have survery results under this survey";
        }

        if(count($messages) == 0){
            $survey->questions()->detach();
            $survey->delete();
            return redirect()
                    ->route('surveys')
                    ->with("status", true)
                    ->with("message", $this->deletedMessage);
        }else{
            $messageStr = "";
            foreach($messages as $key => $message){
                $messageStr .= ($key+1) . '. ' . $message . '.<br />';
            }
            return redirect()
                    ->route('surveys.delete', ['id' => $survey->id])
                    ->with("status", false)
                    ->with("message", $messageStr . "** Please delete those information first **");
        }
    }

    public function confirmDeleteAjax(Survey $survey) {
        $messages = [];
        if($survey->meetings->count() > 0){
            $messages[] = "You have meeting information under this survey";
        }
        if($survey->meetingSurveryResults->count() > 0){
            $messages[] = "You have survery results under this survey";
        }
        if(count($messages) == 0){
            $survey->questions()->detach();
            $survey->delete();
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

    public function store() {
        $inputs = request()->input();
        $department_id = $inputs['department_id'];
        $project_id = $inputs['project_id'];
        $question_ids =  $inputs['selected_question'];


        $departmentModel = Department::find($department_id);
        $projectModel = Project::find($project_id);
        if( !$departmentModel ){
            return redirect()
                    ->route('surveys.create')
                    ->with("status", false)
                    ->with("message", "Please,Select Department.");
        }

        if( !$projectModel ){
            return redirect()
                ->route('surveys.create')
                ->with("status", false)
                ->with("message", "Please,Select Project.");
        }

        if( is_array($question_ids) && count($question_ids) <= 0 ){
            return redirect()
                ->route('surveys.create')
                ->with("status", false)
                ->with("message", "Please,Select at least one question to create survey.");
        }

        $department_name = $departmentModel->name . ":" .$projectModel->name;

        $survey_inputs['department_id'] = $department_id;
        $survey_inputs['project_id'] = $project_id;
        $survey_inputs['name'] = $department_name;



        $this->validator($survey_inputs)->validate();

        $survey = Survey::create($survey_inputs);
        $survey->questions();
        if( $survey && $survey->exists() ){
            $survey->questions()->attach($question_ids);
            return redirect()
                ->route('surveys.show', $survey)
                ->with("status", true)
                ->with("message", $this->savedMessage);
        }else{
            return redirect()
                    ->route('surveys.create')
                    ->with("status", true)
                    ->with("message", $this->savedFailed);
        }
    }


  public function report(){
        $projects = Project::all();
        $ClientCompanyCount = Meeting::select('client_company_id')->count();
        $userClientProjectCount = Meeting::select('project_id')->count();
        $sendSurveyCount = Meeting::whereNotNull('survey_id')->whereIn( 'status',['Completed'] )->count();
        $CompletedWithoutSurveySendCount = Meeting::whereNull('survey_id')->whereIn( 'status', ['Completed'] )->count();
        $meetings = Meeting::whereNotNull('survey_id')->whereIn( 'status', ['Completed'] )->get(['id']);
        $SurveyRespondedCount = 0;
        $SurveyNotRespondedCount = 0;

        foreach( $meetings as $meeting ) {
            $NotNullAnswerOptions = MeetingSurveryResult::where('meeting_id', $meeting->id)
                                                                ->whereNotNull('answer_option_id')
                                                                    ->count();
            if( $NotNullAnswerOptions > 0 ){
                $SurveyRespondedCount++;
            }else{
                $SurveyNotRespondedCount++;
            }
        }


        $CompletedWithoutSurveySendCountPlusSurveyNotRespondedCount = $SurveyNotRespondedCount + $CompletedWithoutSurveySendCount;
        return View(    "surveys.report",
                        [   'projects'=>$projects,
                            'clientCompanyCount'=>$ClientCompanyCount,
                            'clientProjectCount'=>$userClientProjectCount,
                            'surveySendCount'=>$sendSurveyCount,
                            'surveyRespondedCount'=>$SurveyRespondedCount,
                            'surveyNotRespondedPlusCompletedWithoutSurveyCount' =>$CompletedWithoutSurveySendCountPlusSurveyNotRespondedCount
                        ]
                    );
    }

    public function getProjectStaffs(){
        $ProjectID = request()->get('ProjectID');
        $usersInMeeting = Meeting::where('project_id',$ProjectID)->get();
        $user_ids = array();
        foreach($usersInMeeting as $meeting){
            foreach( $meeting->staffs as $staff){
                $user_ids[] = $staff->pivot->user_id;
            }
        }

        $ProjectAssociatedStaffs = User::whereIn('id',$user_ids)->distinct()->get(['id','name'])->toJson();
        return $ProjectAssociatedStaffs;
    }

    public function getProjectReport(){

        $ProjectID = request()->get('ProjectID');
        $FieldStaff = request()->get('FieldStaff');
        $start_date = request()->get('start_date');
        $end_date = request()->get('end_date');

        $dateRanges[0] = Carbon::parse($start_date)->format('Y-m-d H:i:s');
        $dateRanges[1] = Carbon::parse($end_date)->format('Y-m-d H:i:s');
        $meetings = Meeting::where( 'project_id', $ProjectID )
                            ->whereHas( 'staffs', function($query)use($FieldStaff){
                                return $query->where('user_id',$FieldStaff);
                             })
                            ->whereBetween( 'start_time', $dateRanges )
                            ->get();

        $meeting_ids = array();
        foreach($meetings as $meeting){
            $meeting_ids[] = $meeting->id;
        }


        $result_sets = array();
        if( count($meeting_ids) >0 ){
            $meeting_resutls = MeetingSurveryResult::whereIn('meeting_id',$meeting_ids)->get(['id','question_id','answer_option_id']);
            $question_options = array();

            foreach($meeting_resutls as $result){
                if($result->question_id && $result->answer_option_id){

                    if( ! array_key_exists( $result->question_id,$question_options ) ){
                        $question_options[$result->question_id] = array();

                    }

                    if( !array_key_exists($result->answer_option_id,$question_options[$result->question_id]) ){
                        $question_options[$result->question_id][$result->answer_option_id] = 0;
                    }

                    if(empty( $question_options[$result->question_id][$result->answer_option_id])){
                        $question_options[$result->question_id][$result->answer_option_id] = 1;
                    }else{
                        $question_options[$result->question_id][$result->answer_option_id] = $question_options[$result->question_id][$result->answer_option_id] + 1;
                    }
                }
            }

            $result_sets = array();
            $result_questions = array();
            foreach($meeting_resutls as $result){
                if( !in_array($result->question_id,$result_questions) ){
                        $result_questions[] = $result->question_id;

                        $question = Question::find($result->question_id);
                        $option_array = array();
                        $options = $question->answerOptions()->get(['id','key','body']);
                        foreach($options as $index=>$option){
                            $option_array[$index]['key']= $option->key;
                            $option_array[$index]['body'] = $option->body;
                            if( array_key_exists($result->question_id,$question_options) && array_key_exists($option->id,$question_options[$result->question_id] ) ){
                                $option_array[$index]['count'] = $question_options[$result->question_id][$option->id];
                            }else{
                                $option_array[$index]['count'] = 0;
                            }
                        }

                        $result_sets[] = array(
                            "question"=>$question->body,
                            "options"=> $option_array
                        );
                    }
            }

            return collect($result_sets)->toJson();
        }else{
            return collect($result_sets)->toJson();
        }
    }

    public function search() {
        $q = request()->input('q');
        if ($q) {
            $surveys = Survey::Where('name', 'LIKE', '%' . $q . '%')->orWhere('remarks', 'LIKE', '%' . $q . '%')->paginate(10);
            if (count($surveys) > 0) {
                return View("surveys.search", ["surveys" => $surveys,"q"=>$q])->withQuery($q);
            } else {
                return View("surveys.search", ["msg" => 'No Details found. Try to search again !'])->withQuery($q);
            }
        } else {
            return View("surveys.search", ["msg" => 'Please, Enter Something to Search.'])->withQuery($q);
        }
    }
}
