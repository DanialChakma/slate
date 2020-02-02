<?php

namespace App\Http\Controllers;

use App\AnswerOption;
use App\Question;
use App\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    //
    protected function QuestionValidator(array $data) {
        return Validator::make($data,   [   'key' => 'required|string',
                                            'body' => 'required|string',
                                            'question_id' => 'required' ]
                            );
    }

    protected function SurveyValidator(array $data){
        return Validator::make($data, ['body' => 'required|string', 'type' => 'required|string'] );
    }

    public function index(){
        $questions = Question::orderBy('created_at', 'desc')->paginate(10);
        return View('questions.list', [ 'questions' => $questions ] );
    }

    public function edit(Question $question) {
        return View('questions.edit', ['question' => $question]);
    }

    public function update(Question $question) {
        $inputs = request()->input();
        $question_inputs['body'] = $inputs['body'];
        $question_type = $question_inputs['type'] = $inputs['type'];
        $this->SurveyValidator($question_inputs)->validate();
        // dd($inputs);
        // $questionObject->save();
        $keyValueArr = array();
        foreach($inputs as $key=>$value){
            if( strpos($key,"key") !== false ){
                $keyparts = explode("_",$key);
                $index = trim($keyparts[1]);
                $keyValueArr[$index]['key'] = $value;
            }else if(strpos($key,"value") !== false){
                $keyparts = explode("_",$key);
                $index = trim($keyparts[1]);
                $keyValueArr[$index]['value'] = $value;
            }
        }

        if( $question_type == "Open-text" ){
            $messages = [];
            if( $question->meetingSurveryResults->count() > 0 ){
                $messages[] = "You have survey result under this question";
            }
            if( count($messages) == 0 ){
                $question->update($question_inputs);
                $question->answerOptions()->delete();
                return redirect()
                    ->route('questions.show', $question)
                    ->with("status", true)
                    ->with("message", $this->updatedMessage);
            }else{
                $messageStr = "";
                foreach($messages as $key => $message){
                    $messageStr .= ($key+1) . '. ' . $message . '.<br />';
                }

                return redirect()
                    ->route('questions.edit', ['id' => $question->id])
                    ->with("status", false)
                    ->with("message", $messageStr . "** Please delete those information first **");
            }
        }else{
            if(count($keyValueArr) > 1){

                $messages = [];
                if( $question->meetingSurveryResults->count() > 0 ){
                    $messages[] = "You have survey result under this question";
                }
                if( count($messages) == 0 ){
                    $question->update($question_inputs);
                    $answerOptionInputs = array();
                    // $question->answerOptions()->delete();
                    foreach($keyValueArr as $key=>$values){
                        $key = $values['key'];
                        $value = $values['value'];
                        $answerOptionInputs[] = $answerOption = array(
                            'key'=>$key,
                            'body'=>$value,
                            'question_id'=>$question->id
                        );
                        $this->QuestionValidator($answerOption)->validate();
                        //$answerOption->save();
                        //  unset($answerOptionInputs);
                    }

                    $currentOptionsCount = count($answerOptionInputs);
                    $dbOptionsCount = $question->answerOptions()->count();

                    if( $currentOptionsCount > $dbOptionsCount ){
                        // update and insert
                        $dbOptionsKeys = $question->answerOptions()->get(['id'])->toArray();
                        $dbOptionsCountKey = count($dbOptionsKeys);
                        foreach($answerOptionInputs as $index=>$option ){
                            if( $index < $dbOptionsCountKey ){
                                $question->answerOptions()
                                    ->where('id',$dbOptionsKeys[$index]['id'])
                                    ->limit(1)
                                    ->update($option);
                            }else{
                                $question->answerOptions()->create($option);
                            }
                        }
                    }elseif($currentOptionsCount == $dbOptionsCount){
                        // update
                        $dbOptionsKeys = $question->answerOptions()->get(['id'])->toArray();
                        $dbOptionsCountKey = count($dbOptionsKeys);
                        foreach($answerOptionInputs as $index=>$option ){
                            if( $index < $dbOptionsCountKey ){
                                $question->answerOptions()
                                    ->where('id',$dbOptionsKeys[$index]['id'])
                                    ->limit(1)
                                    ->update($option);
                            }
                        }
                    }else{
                        // update and delete
                        $dbOptionsKeys = $question->answerOptions()->get(['id'])->toArray();
                        //dd($dbOptionsKeys);
                        $currentOptions = count($answerOptionInputs);
                        foreach($question->answerOptions()->get(['id'])->toArray() as $index=>$option){
                            if($index < $currentOptions){
                                $question->answerOptions()
                                    ->where('id',$dbOptionsKeys[$index]['id'])
                                    ->limit(1)
                                    ->update($answerOptionInputs[$index]);
                            }else{
                                $question->answerOptions()
                                    ->where('id',$dbOptionsKeys[$index]['id'])
                                    ->delete();
                            }
                        }
                    }

                    return redirect()
                        ->route('questions.show', $question)
                        ->with("status", true)
                        ->with("message", $this->updatedMessage);

                }else{
                    $messageStr = "";
                    foreach($messages as $key => $message){
                        $messageStr .= ($key+1) . '. ' . $message . '.<br />';
                    }

                    return redirect()
                        ->route('questions.edit', ['id' => $question->id])
                        ->with("status", false)
                        ->with("message", $messageStr . "** Please delete those information first **");
                }

            }else{
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("status", false)
                    ->with("message", 'Number of answer options must be greater than 1.');
            }

        }

    }

    public function show(Question $question) {
        return View('questions.show', ['question' => $question]);
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

    public function create() {
        return View('questions.create');
    }

    public function delete(Question $question) {
        return View('questions.delete', ['question' => $question]);
    }

    public function confirmDelete(Question $question) {
        $messages = [];
        if($question->meetingSurveryResults->count() > 0){
            $messages[] = "You have survey result under this question";
        }
        if($question->surveys()->count() > 0){
            $messages[] = "You have survey associated with this question";
        }

        if( count($messages) == 0 ){
            $question->delete();
            return redirect()
                ->route('questions')
                ->with("status", true)
                ->with("message", $this->deletedMessage);
        }else{
            $messageStr = "";
            foreach($messages as $key => $message){
                $messageStr .= ($key+1) . '. ' . $message . '.<br />';
            }

            return redirect()
                ->route('questions.delete', ['id' => $question->id])
                ->with("status", false)
                ->with("message", $messageStr . "** Please delete those information first **");
        }
    }

    public function confirmDeleteAjax(Question $question) {
        $messages = [];
        if($question->meetingSurveryResults->count() > 0){
            $messages[] = "You have survey result for this question. You can never delete it.";
        }
        if($question->surveys()->count() > 0){
            $survey_string = "";
            $limit = 15;
            $total = $question->surveys()->count();
            foreach( $question->surveys()->take($limit)->get() as $survey ){
                if( $survey_string == "" ){
                    $survey_string = '<a style="color: red;" target="_blank" href="'.route('surveys.show', ['id' => $survey->id]).'">'.$survey->name.'</a>';
                }else{
                    $survey_string .= ', <a style="color: red;" target="_blank" href="'.route('surveys.show', ['id' => $survey->id]).'">'.$survey->name.'</a>';
                }
            }
            if( $total > $limit ){
                $restCount =  $total-$limit;
                $survey_string = $survey_string.'... (and '.$restCount.' more)';
            }
            $messages[] = "You have following surveys associated with this question.<br/>Surveys: ".$survey_string;
        }

        if( count($messages) == 0 ){
            $question->delete();
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
        //dd($inputs);
        $question_inputs['body'] = $inputs['body'];
        $question_type = $question_inputs['type'] = $inputs['type'];
       // $question_inputs['survey_id'] = $inputs['survey_id'];
        $this->SurveyValidator($question_inputs)->validate();

        // dd($inputs);
        // $questionObject->save();

        //$num_options = intval($inputs['num_options']);
        $keyValueArr = array();
        foreach($inputs as $key=>$value){
            if( strpos($key,"key") !== false ){
                $keyparts = explode("_",$key);
                $index = trim($keyparts[1]);
                $keyValueArr[$index]['key'] = $value;
            }else if(strpos($key,"value") !== false){
                $keyparts = explode("_",$key);
                $index = trim($keyparts[1]);
                $keyValueArr[$index]['value'] = $value;
            }
        }


        if( $question_type == "Open-text" ){
            $questionObject = Question::create($question_inputs);
            return redirect()
                ->route('questions.show', $questionObject)
                ->with("status", true)
                ->with("message", $this->savedMessage);
        }else{

            if(count($keyValueArr) > 1){
                $questionObject = Question::create($question_inputs);
                foreach($keyValueArr as $key=>$values){
                    $key = $values['key'];
                    $value = $values['value'];
                    $answerOptionInputs = array();
                    $answerOptionInputs['key'] =  $key;
                    $answerOptionInputs['body'] =  $value;
                    $answerOptionInputs['question_id'] =  $questionObject->id;
                    // dd($answerOptionInputs);
                    $this->QuestionValidator($answerOptionInputs)->validate();
                    $answerOption = AnswerOption::create($answerOptionInputs);
                    //$answerOption->save();
                    unset($answerOptionInputs);
                }

                return redirect()
                    ->route('questions.show', $questionObject)
                    ->with("status", true)
                    ->with("message", $this->savedMessage);
            }else{
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("status", false)
                    ->with("message", 'Number of answer options must be greater than 1.');
            }

        }

    }

    public function search() {
        $q = request()->input('q');
        if ($q) {
            $questions = Question::where('type', 'LIKE', '%' . $q . '%')->orWhere('body', 'LIKE', '%' . $q . '%')->paginate(10);
           // dd($questions);
            if (count($questions) > 0) {
                return View("questions.search", ["questions"=>$questions,"q"=>$q])->withQuery($q);
            } else {
                return View("questions.search", ["msg" => 'No Details found. Try to search again !'])->withQuery($q);
            }
        } else {
            return View("questions.search", ["msg" => 'Please, Enter Something to Search.'])->withQuery($q);
        }
    }
}
