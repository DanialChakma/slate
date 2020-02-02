<?php
namespace App\Http\Controllers;
use App\Jobs\ProcessSurveyResponse;
use App\ClientCompany;
use App\ClientCompanyContactPerson;
use App\Department;
use App\Jobs\SendFirstSurveyJob;
use App\Jobs\SendFirstSurveyViaEmailJob;
use App\Meeting;
use App\Project;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\GraphEvent;
use App\TokenStore\TokenCache;
use Mockery\Exception;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    protected $currentUser = null;
    protected $graph = null;

    public function __construct()
    {
        if(config('app.is_graph_api_enabled')) {
            $this->setCurrentOffice365User();
        };
    }

    /**
     * Sets Office 365 User instance
     */
    public function setCurrentOffice365User()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();

        $this->graph = new Graph();

        $tokenCache = new TokenCache();
        $token = $tokenCache->getAccessToken();
        if(empty($token)) return null;
        $this->graph->setAccessToken($token);

        try{
            $this->currentUser = $this->graph->createRequest("get", "/me")
                ->setReturnType(Model\User::class)
                ->execute();
        }catch(Exception $e){
        }
    }

    /**
     * SO THAT USER CAN BE REDIRECTED TO RIGHT PAGE FROM OFFICE 365
     *
     * @param $action
     * @param $id
     */
    public function setBackActionAndId($action, $id){
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $_SESSION['backAction'] = $action;
        $_SESSION['backMeetingId'] = $id;
    }

    protected function validator(array $data) {
        return Validator::make($data, [
            'title' => 'required|string|max:190',
            'remarks' => 'nullable|string',
            'location' => 'string|max:255',
            'project_id' => 'required|integer',
            'client_company_id' => 'required|integer',
            'date'  =>  'required|date_format:"d M, Y"',
            'start_time' => 'required|date_format:"h:i A"|before:end_time',
            'end_time' => 'required|date_format:"h:i A"|after:start_time'
        ]);
    }


    public function index() {
        $perPage = request()->input('perPage');
        $perPage = isset($perPage) && is_numeric($perPage) ? intval($perPage) : null;
        if(empty($perPage)) $perPage = 0;
        if(auth()->user()->isAdmin()){
            if($perPage > 0){
                $meetings = Meeting::orderBy('created_at','DESC')->paginate($perPage);
            }else{
                $meetings = Meeting::orderBy('created_at','DESC')->paginate(10);
            }
        }
        if(auth()->user()->isSupervisor()){
            $fieldStuffIds = User::where('supervisor_id', auth()->user()->id)
                ->select(["id"])->get()->map(function ($item, $key) {
                    return $item->id;
                })->toArray();
            array_push($fieldStuffIds, auth()->user()->id);
            $meetings = Meeting::whereHas('staffs',function($query) use($fieldStuffIds){
                return $query->whereIn('user_id',$fieldStuffIds);
            })->orderBy('created_at','DESC')->paginate(10);
        }elseif(auth()->user()->isFieldStuff()){
            $user_id = auth()->user()->id;
            $meetings = Meeting::whereHas('staffs',function($query) use($user_id){
                return $query->where('user_id',$user_id);
            })->orderBy('created_at','DESC')->paginate(10);
        }

        return View('meetings.list', [ 'meetings' => $meetings ]);
    }

    public function edit(Meeting $meeting) {
        if(config('app.is_graph_api_enabled')) {
            $this->setBackActionAndId('edit', $meeting->id);
            if(empty($this->currentUser)) return redirect()->route('oauth');
        };

        if($meeting->status == 'Completed' || $meeting->status == 'Cancelled'){
            return redirect()->back()
                ->with("status", false)
                ->with("message", 'You cannot edit a Completed or Cancelled meeting.');
        }

        return View('meetings.edit', [
            'meeting' => $meeting,
            'staffs' => $this->getStaffs()
        ]);
    }

    public function update(Meeting $meeting) {
        if(config('app.is_graph_api_enabled')) {
            $this->setBackActionAndId('edit', $meeting->id);
            if(empty($this->currentUser)) return redirect()->route('oauth');
        };

        $inputs = request()->input();

        $meeting_inputs['title'] = $inputs['title'];
        $meeting_inputs['remarks'] = $inputs['remarks'];

        if(empty($inputs['remarks'])) {
            $meeting_inputs['remarks'] = 'Details not added.';
        }else{
            $meeting_inputs['remarks'] = $inputs['remarks'];
        }

        $meeting_inputs['project_id'] = $inputs['project_id'];

        $meeting_inputs['start_time'] = $inputs['start_time'];
        $meeting_inputs['end_time'] = $inputs['end_time'];
        $meeting_inputs['date'] = $inputs['date'];

        $meeting_inputs['client_company_id'] = $inputs['client_company_id'];
        $meeting_inputs['location'] = $inputs['location'];


        $this->validator($meeting_inputs)->validate();
        //$inputs['date'] = Carbon::parse($inputs['date'])->format("Y-m-d");

        $inputs['date'] = Carbon::createFromFormat('d M, Y',$inputs['date'])->format("Y-m-d");
        $meeting_inputs['start_time']   = $inputs['date']." ".$inputs['start_time'];
        $meeting_inputs['end_time']     = $inputs['date']." ".$inputs['end_time'];
        $meeting_inputs['start_time'] = Carbon::parse($meeting_inputs['start_time'])->format("Y-m-d H:i:s");
        $meeting_inputs['end_time'] = Carbon::parse($meeting_inputs['end_time'])->format("Y-m-d H:i:s");

        $contacts = request()->has('contact_persons') ? request()->get('contact_persons'): null;
        $staffs = request()->has('user_id') ? request()->get('user_id'): null;
        if( !is_array($staffs) || count($staffs) <= 0 ){
            return redirect()->route('meetings.edit',$meeting)
                ->with("status", false)
                ->with("message","Please select a staff name.");
        }
        if( is_array($contacts) && count($contacts) >0 ){
            unset($meeting_inputs['date']);
            $isUpdated = $meeting->update($meeting_inputs);
            if( $isUpdated ) {
                foreach ($contacts as $index => $id) {
                    $contacts[$index] = is_numeric($id) ? intval($id) : 0;
                }
                $meeting->clientCompanyContactPersons()->sync($contacts);
                foreach ($staffs as $index => $id) {
                    $staffs[$index] = is_numeric($id) ? intval($id) : 0;
                }
                $meeting->staffs()->sync($staffs);
                //OFFICE 365 CODE STARTS
                if(config('app.is_graph_api_enabled')) {
                    $attendees = [];

                    foreach ($meeting->staffs as $staff) {
                        $attendees[] = [
                            'name' => $staff->name,
                            'address' => $staff->email
                        ];
                    }

                    foreach ($meeting->clientCompanyContactPersons as $clientCompanyContactPerson) {
                        $attendees[] = [
                            'name' => $clientCompanyContactPerson->name,
                            'address' => $clientCompanyContactPerson->email
                        ];
                    }

                    $start_time = new Carbon($meeting->start_time);
                    $end_time = $meeting->end_time == null ? new Carbon($meeting->end_time) : ((new Carbon($meeting->start_time))->addHour(1));
                    $this->graphEvent = new GraphEvent(
                        15,
                        true,
                        $meeting->title,
                        true,
                        true,
                        'singleInstance',
                        $meeting->remarks ?? $meeting->title,
                        // $start_time->format('Y-m-d'). 'T' . $start_time->format('h:i:s'),
                        // $end_time->format('Y-m-d'). 'T' . $end_time->format('h:i:s'),
                        $start_time->toIso8601String(),
                        $end_time->toIso8601String(),
                        $meeting->location ?? "Can we discuss over phone?",
                        $attendees,
                        auth()->user()->name,
                        auth()->user()->email);

//                if(!empty($meeting->outlook_event_id)){
//                    $reqObj = $this->graph->createRequest('DELETE', '/me/events/' . $meeting->outlook_event_id)
//                        ->addHeaders(array ('X-AnchorMailbox' => $this->currentUser->getMail()))
//                        ->attachBody(json_encode($this->graphEvent))
//                        ->setReturnType(Model\Event::class);
//
//                    $reqObj->execute();
//                };

                    $reqObj = $this->graph->createRequest('POST', '/me/events')
                        ->addHeaders(array ('X-AnchorMailbox' => $this->currentUser->getMail()))
                        ->attachBody(json_encode($this->graphEvent))
                        ->setReturnType(Model\Event::class);

                    $reqObj = $reqObj->execute();
                    $meeting->outlook_event_id = $reqObj->getId();
                    $meeting->save();
                }
                //OFFICE 364 CODE ENDS

                return redirect()->route('meetings.show', $meeting)
                    ->with("status", true)
                    ->with("message", $this->updatedMessage);
            }else{
                return redirect()->route('meetings.edit',$meeting)
                    ->with("status", false)
                    ->with("message", $this->failedToUpdateMessage);
                //->withInput($inputs);
            }
        }else{
            // dd($inputs);
            return redirect()->route('meetings.edit',$meeting)
                ->with("status", false)
                ->with("message","Please select a Contact Person");
            // ->withInput($inputs);
        }
    }

    public function show(Meeting $meeting) {
        return View('meetings.details', ['meeting' => $meeting]);
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
        $contacts = ClientCompanyContactPerson::all(["id", "name", "designation", "email", "phone"])->where("id", $id)->toJson();
        return $contacts;
    }

    private function  getStaffs(){
        if(auth()->user()->isAdmin()){
            $staffs = User::whereIn("role_id", [ 2, 3 ])
                ->select(["id", "name", "role_id"])
                ->orderBy('name')
                ->get();
            $staffs->push(auth()->user());
            return $staffs;
        }elseif(auth()->user()->isSupervisor()){
            $staffs = User::where('supervisor_id',auth()->user()->id)
                ->select(["id", "name", "role_id"])
                ->orderBy('name')
                ->get();
            $staffs->push(auth()->user());
            return $staffs;
        }else{
            return User::where('id', auth()->user()->id)
                ->select(["id", "name", "role_id"])
                ->orderBy('name')
                ->get();
        }
    }

    public function create() {
        if(config('app.is_graph_api_enabled')) {
            if(empty($this->currentUser)) return redirect()->route('oauth');
        };

        $clientCompanies = ClientCompany::all([ "id", "company_name" ]);


        // if(auth()->user()->isAdmin()){
        //     $quannStaffs = User::whereIn("role_id", [ 2, 3 ])
        //     ->select(["id", "name", "role_id"])
        //     ->orderBy('name')
        //     ->get();
        //     $quannStaffs->push(auth()->user());
        // }elseif(auth()->user()->isSupervisor()){
        //     $quannStaffs = User::where('supervisor_id',auth()->user()->id)
        //     ->select(["id", "name", "role_id"])
        //     ->orderBy('name')
        //     ->get();
        //     $quannStaffs->push(auth()->user());
        // }else{
        //     $quannStaffs = User::where('id', auth()->user()->id)
        //     ->select(["id", "name", "role_id"])
        //     ->orderBy('name')
        //     ->get();
        // }
        $quannStaffs = $this->getStaffs();

        if(auth()->user()->isAdmin()){
            $departments = Department::all(["id", "name"]);
        }else{
            $departments = Department::where('id', auth()->user()->department_id)->select(["id", "name"])->get();
        }

        $projects = Project::where('department_id', $departments->first()->id)->get();
        return View('meetings.create', [
            "departments" => $departments,
            "projects" => $projects,
            "clientCompanies" => $clientCompanies,
            "quannStaffs" => $quannStaffs
        ]);
    }

    public function delete(Meeting $meeting) {
        if(config('app.is_graph_api_enabled')) {
            $this->setBackActionAndId('delete', $meeting->id);
            if(empty($this->currentUser)) return redirect()->route('oauth');
        };

        return View('meetings.delete', ['meeting' => $meeting]);
    }

    public function confirmDelete(Meeting $meeting) {
        if(config('app.is_graph_api_enabled')) {
            $this->setBackActionAndId('delete', $meeting->id);
            if(empty($this->currentUser)) return redirect()->route('oauth');
        };

        $messages = [];
        if($meeting->meetingSurveryResults->count() > 0){
            $messages[] = "You have survey results for this meeting";
        }
        if(count($messages) == 0){
            //OFFICE 365 CODE STARTS
            if(config('app.is_graph_api_enabled')){
                $reqObj = $this->graph->createRequest('DELETE', '/me/events/' . $meeting->outlook_event_id)
                    ->addHeaders(array ('X-AnchorMailbox' => $this->currentUser->getMail()));
            }
            //OFFICE 365 CODE ENDS

            $reqObj->execute();
            $meeting->staffs()->detach();
            $meeting->clientCompanyContactPersons()->detach();
            $meeting->delete();
            return redirect()
                ->route('meetings')
                ->with("status", true)
                ->with("message", $this->deletedMessage);
        }else{
            $messageStr = "";
            foreach($messages as $key => $message){
                $messageStr .= ($key+1) . '. ' . $message . '.<br />';
            }

            return redirect()
                ->route('meetings.delete', ['id' => $meeting->id])
                ->with("status", false)
                ->with("message", $messageStr . "** Please delete those information first **");
        }
    }

    public function confirmDeleteAjax(Meeting $meeting) {
        if(config('app.is_graph_api_enabled')) {
            $this->setBackActionAndId('delete', $meeting->id);
            if(empty($this->currentUser)) return redirect()->route('oauth');
        };

        $messages = [];
        if($meeting->meetingSurveryResults->count() > 0){
            $messages[] = "You have survey results for this meeting. You can never delete it";
        }
        if(count($messages) == 0){

            //OFFICE 365 CODE STARTS
            if(config('app.is_graph_api_enabled')){
                $reqObj = $this->graph->createRequest('DELETE', '/me/events/' . $meeting->outlook_event_id)
                    ->addHeaders(array ('X-AnchorMailbox' => $this->currentUser->getMail()));
            }
            //OFFICE 365 CODE ENDS
            $meeting->staffs()->detach();
            $meeting->clientCompanyContactPersons()->detach();
            $meeting->delete();
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

    public function store()
    {
        if(config('app.is_graph_api_enabled')) {
            if(empty($this->currentUser)) return redirect()->route('oauth');
        };

        $inputs = request()->input();
        $meeting_inputs['title'] = $inputs['title'];

        if(empty($inputs['remarks'])) {
            $meeting_inputs['remarks'] = 'Details not added.';
        }else{
            $meeting_inputs['remarks'] = $inputs['remarks'];
        }

        $meeting_inputs['project_id'] = $inputs['project_id'];

        $meeting_inputs['start_time'] = trim($inputs['start_time']);
        $meeting_inputs['end_time'] = trim($inputs['end_time']);
        $meeting_inputs['date'] = $inputs['date'];


        $meeting_inputs['client_company_id'] = $inputs['client_company_id'];
        $meeting_inputs['location'] = $inputs['location'];

        if(empty($inputs['description'])) {
            $inputs['description'] = 'Details not added.';
        }

        $this->validator($meeting_inputs)->validate();


        //$inputs['date'] = Carbon::parse($inputs['date'])->format("Y-m-d");
        $inputs['date'] = Carbon::createFromFormat('d M, Y',$inputs['date'])->format("Y-m-d");
        $meeting_inputs['start_time']   = $inputs['date']." ".$inputs['start_time'];
        $meeting_inputs['end_time']     = $inputs['date']." ".$inputs['end_time'];
        $meeting_inputs['start_time'] = Carbon::parse($meeting_inputs['start_time'])->format("Y-m-d H:i:s");
        $meeting_inputs['end_time'] = Carbon::parse($meeting_inputs['end_time'])->format("Y-m-d H:i:s");

        if(($meeting_inputs['start_time'] == $meeting_inputs['end_time']) || ($meeting_inputs['start_time'] > $meeting_inputs['end_time']))
        {
            return redirect()->route('meetings.create')->with("status", false)
                ->with("message","Meeting start time cannot be equal or greater than end time.");
        }

        $contacts = request()->has('contact_persons') ? request()->get('contact_persons'): null;
        $staffs = request()->has('user_id') ? request()->get('user_id'): null;
        if( !is_array($staffs) || count($staffs) <= 0 ){
            return redirect()->route('meetings.create')
                ->with("status", false)
                ->with("message","Please select a staff name");
        }
        if( is_array($contacts) && count($contacts) >0 ){
            unset($meeting_inputs['date']);
            $meetingModel = Meeting::create($meeting_inputs);

            if($meetingModel && $meetingModel->exists()){
                foreach($contacts as $index=>$id){
                    $contacts[$index] = is_numeric($id) ? intval($id): 0;
                }
                $meetingModel->clientCompanyContactPersons()->attach($contacts);
                foreach( $staffs as $index=>$staff ){
                    $staffs[$index] = is_numeric($staff) ? intval($staff): 0;
                }
                $meetingModel->staffs()->attach($staffs);
                //OFFICE 365 CODE STARTS
                if(config('app.is_graph_api_enabled')) {
                    $attendees = [];

                    foreach ($meetingModel->staffs as $staff) {
                        $attendees[] = [
                            'name' => $staff->name,
                            'address' => $staff->email
                        ];
                    }

                    foreach ($meetingModel->clientCompanyContactPersons as $clientCompanyContactPerson) {
                        $attendees[] = [
                            'name' => $clientCompanyContactPerson->name,
                            'address' => $clientCompanyContactPerson->email
                        ];
                    }
                    $start_time = new Carbon($meetingModel->start_time);
                    $end_time = $meetingModel->end_time == null ? new Carbon($meetingModel->end_time) : ((new Carbon($meetingModel->start_time))->addHour(1));
                    $this->graphEvent = new GraphEvent(
                        15,
                        true,
                        $meetingModel->title,
                        true,
                        true,
                        'singleInstance',
                        $meetingModel->remarks ?? $meetingModel->title,
                        // $start_time->format('Y-m-d'). 'T' . $start_time->format('h:i:s'),
                        // $end_time->format('Y-m-d'). 'T' . $end_time->format('h:i:s'),
                        $start_time->toIso8601String(),
                        $end_time->toIso8601String(),
                        $meetingModel->location ?? "",
                        $attendees,
                        auth()->user()->name,
                        auth()->user()->email);


                    //dd($start_time->format('Y-m-d'). 'T' . $start_time->format('h:i:s'),$start_time->toIso8601String(), $end_time->format('Y-m-d'). 'T' . $end_time->format('h:i:s'), $end_time->toIso8601String());


                    //dd(Carbon::now()->format('Y-d-m'). 'T' . Carbon::now()->format('h:m:i'));

                    $reqObj = $this->graph->createRequest('POST', '/me/events')
                        ->addHeaders(array ('X-AnchorMailbox' => $this->currentUser->getMail()))
                        ->attachBody(json_encode($this->graphEvent))
                        ->setReturnType(Model\Event::class);

                    $reqObj = $reqObj->execute();
                    $meetingModel->outlook_event_id = $reqObj->getId();
                    $meetingModel->save();
                }
                //OFFICE 364 CODE ENDS


                return redirect()->route('meetings.show', $meetingModel)
                    ->with("status", true)
                    ->with("message", $this->savedMessage);
            }else{
                return redirect()->route('meetings.create')
                    ->with("status", false)
                    ->with("message", $this->savedFailed);
                //->withInput($inputs);
            }
        }else{
            return redirect()->route('meetings.create')
                ->with("status", false)
                ->with("message","Please select a Contact Person");
            // ->withInput($inputs);
        }
    }

    public function search() {
        $q = request()->input('q');
        if ($q) {
            $meetings = Meeting::Where('title', 'LIKE', '%' . $q . '%')->orWhere('remarks', 'LIKE', '%' . $q . '%')->paginate(10);
            if (count($meetings) > 0) {
                return View("meetings.search", ["q"=>$q,"meetings" => $meetings])->withQuery($q);
            } else {
                return View("meetings.search", ["msg" => 'No Details found. Try to search again !'])->withQuery($q);
            }
        } else {
            return View("meetings.search", ["msg" => 'Please, Enter Something to Search.'])->withQuery($q);
        }
    }

    public function meetingsOfFieldStuffs(){
        $meetings = Meeting::where('user_id',auth()->user()->id)
            ->whereIn('status',['Initiated','Rescheduled'])
            ->orderBy('start_time','DESC')->paginate(10);
        return View('meetings.meetings', [ 'meetings' => $meetings ]);
    }

    public function changeStatus(Meeting $meeting){

        if( config('app.is_graph_api_enabled') ) {
            $this->setBackActionAndId('edit', $meeting->id);
            if(empty($this->currentUser)) return redirect()->route('oauth');
        };
        return View('meetings.changeStatus', [ 'meeting' => $meeting ]);
    }

    public function meetingCompleteAction(){
        $meeting_id = request()->input('meeting');
        $survey = request()->input('survey');
        $meeting = Meeting::find($meeting_id);
        if( $meeting && $meeting->status == "Completed" ){
            return collect(array(
                "status"=>"FAILED",
                "msg"=>"Meeting Already Completed."
            ))->toJson();
        }

        $meeting_users = array();
        foreach( $meeting->staffs as $staff ){
            $meeting_users[] = $staff->id;
            $meeting_users[] = $staff->supervisor_id;
        }

        if( in_array( auth()->user()->id,$meeting_users) ){

            if( $survey == "send" ){
                if( $meeting->project->surveys->count() > 0 ){
                    $sv = $meeting->project->surveys->first();
                    if(!empty($sv)){
                        $meeting->survey_id = $sv->id;
                    }else{
                        $meeting->survey_id = null;
                    }
                }
            } else if( $survey == "notsend" ){
                $remarks = request()->input('remarks');
                $meeting->survey_id = null;
                $meeting->remarks = empty($meeting->remarks)? $remarks:$meeting->remarks."\n".$remarks;
            }

            $meeting->status = "Completed";
            if( $meeting->save() ){
                if( $survey == "send" && !empty($meeting->survey_id) ){
                    // SendFirstSurveyJob::dispatch($meeting)->onConnection('database')->onQueue('FirstSurveySend');
                    SendFirstSurveyViaEmailJob::dispatch($meeting)->onConnection('sync')->onQueue('default');
                }

                return array(
                    "status"=>"OK",
                    "msg"=>"Meeting status successfully Updated."
                );
            }else{
                return array(
                    "status"=>"FAILED",
                    "msg"=>"Meeting status update failed."
                );
            }
        }else{
            return array(
                "status"=>"FAILED",
                "msg"=>"You have no permission to Change Meeting Status."
            );
        }
    }

    public function meetingRescheduleAction(){
        $inputs = request()->input();
        $meeting_id = request()->input('meeting');
        //$date = request()->input('date');
        //$starttime = request()->input('starttime');
        //$endtime = request()->input('endtime');


        $remarks = request()->input('remarks');
        //$startDate = Carbon::parse($date." ".$starttime);
        //$endDate = Carbon::parse($date." ".$endtime);


        $date = Carbon::parse($inputs['date'])->format("Y-m-d");
        $starttime   = $date ." ".$inputs['starttime'];
        $endtime     = $date ." ".$inputs['endtime'];
        //return $date . '|' . $starttime . '|' . $endtime . '|' . $remarks; 
        $startDate = Carbon::parse($starttime)->format("Y-m-d H:i:s");
        $endDate = Carbon::parse($endtime)->format("Y-m-d H:i:s");
        $meeting = Meeting::where('id','=',$meeting_id)->first();
        if( $meeting && $meeting->staffs()->where('id',auth()->user()->id ) ){
            $meeting->start_time = $startDate;
            $meeting->end_time = $endDate;
            if(empty($meeting->remarks)) {
                $meeting->remarks = "Rescheduled for: ". $remarks;
            }else{
                $meeting->remarks = empty($meeting->remarks)? $remarks:$meeting->remarks.", Rescheduled for: ".$remarks;
            }
            $meeting->status = "Rescheduled";
            if( $meeting->save() ){
                return array(
                    "status"=>"OK",
                    "msg"=>"Meeting Rescheduled successfully."
                );
            }else{
                return array(
                    "status"=>"FAILED",
                    "msg"=>"Meeting Reschedule failed."
                );
            }
        }else{
            return array(
                "status"=>"FAILED",
                "msg"=>"You have no permission to Change Meeting Status."
            );
        }
    }

    public function meetingCancelAction(){
        $meeting_id = request()->input('meeting');
        $remarks = request()->input('remarks');
        $meeting = Meeting::where('id','=',$meeting_id)->first();
        if( $meeting && $meeting->staffs()->where('id',auth()->user()->id) ){
            if(empty($meeting->remarks)) {
                $meeting->remarks = "Cancelled for: ". $remarks;
            }else{
                $meeting->remarks = empty($meeting->remarks)? $remarks:$meeting->remarks.", Cancelled for: ".$remarks;
            }
            $meeting->status = "Cancelled";
            if( $meeting->save() ){
                return array(
                    "status"=>"OK",
                    "msg"=>"Meeting Cancelled successfully."
                );
            }else{
                return array(
                    "status"=>"FAILED",
                    "msg"=>"Meeting Cancelleing failed."
                );
            }
        }else{
            return array(
                "status"=>"FAILED",
                "msg"=>"You have no permission to Change Meeting Status."
            );
        }
    }


    public function getUpcomingMeetings(){
        $start_date = Carbon::now()->format('Y-m-d H:i:s');
        $notification_minutes = config('app.notification_minutes',360);
        $end_date = Carbon::parse($start_date)->addMinutes($notification_minutes)->format('Y-m-d H:i:s');
        $dates = [$start_date,$end_date];

        if( auth()->user()->isAdmin() ){
            $meetings = Meeting::whereBetween('start_time',$dates)->whereNotIn('status', ['Completed', 'Cancelled'])
                ->orderBy('start_time')
                ->get();

        }elseif( auth()->user()->isFieldStuff() ){
            $user_id = auth()->user()->id;
            $meetings = Meeting::whereBetween('start_time',$dates)->whereNotIn('status', ['Completed', 'Cancelled'])
                ->whereHas('staffs',function($query) use($user_id){
                    return $query->where('user_id',$user_id);
                })
                ->orderBy('start_time')
                ->get();

        }elseif(auth()->user()->isSupervisor()){
            $filterUserIDs = array();
            $users = User::where('supervisor_id',auth()->user()->id)->get();
            foreach($users as $user){
                $filterUserIDs[]=$user->id;
            }
            $filterUserIDs[] = auth()->user()->id;
            $meetings = Meeting::whereBetween('start_time',$dates)->whereNotIn('status', ['Completed', 'Cancelled'])
                ->whereHas('staffs',function($query) use($filterUserIDs){
                    return $query->whereIn('user_id',$filterUserIDs);
                })
                ->orderBy('start_time')
                ->get();
        }else{
            $user_id = auth()->user()->id;
            $meetings = Meeting::whereBetween('start_time',$dates)->whereNotIn('status', ['Completed', 'Cancelled'])
                ->whereHas('staffs',function($query) use($user_id){
                    return $query->where('user_id',$user_id);
                })
                ->orderBy('start_time')
                ->get();
        }

        $data = array();
        $i=0;
        foreach($meetings as $meeting){
            $project = $meeting->project->name;
            $department = $meeting->project->department->name;
            $data[$i]['id'] = $meeting->id;
            $data[$i]['eventName'] = $meeting->title;
            $data[$i]['desc'] = $meeting->remarks;
            $data[$i]['time'] = Carbon::parse($meeting->start_time)->format('h:i A')."-".Carbon::parse($meeting->end_time)->format('h:i A');
            $data[$i]['department'] = $department;
            $data[$i]['project'] = $project;
            $data[$i]['location'] = trim($meeting->location);
            $data[$i]['client'] = $meeting->clientCompany->company_name;
            $data[$i]['date'] = date('Y-m-d',strtotime($meeting->start_time));
            $i++;
        }

        return collect($data)->toJson();
    }

    public function getMeetings(){
        $start_date = request()->input('start_date')." "."00:00:00";;
        $end_date = request()->input('end_date')." "."23:59:59";
        $start_date = Carbon::parse($start_date)->format('Y-m-d H:i:s');
        $end_date = Carbon::parse($end_date)->format('Y-m-d H:i:s');
        $dates = [$start_date,$end_date];
        if( auth()->user()->isAdmin() ){
            $meetings = Meeting::whereBetween('start_time',$dates)
                ->orderBy('start_time')
                ->get();

        }elseif( auth()->user()->isFieldStuff() ){
            $user_id = auth()->user()->id;
            $meetings = Meeting::whereBetween('start_time',$dates)
                ->whereHas('staffs',function($query) use($user_id){
                    return $query->where('user_id',$user_id);
                })
                ->orderBy('start_time')
                ->get();

        }elseif(auth()->user()->isSupervisor()){
            $filterUserIDs = array();
            $users = User::where('supervisor_id',auth()->user()->id)->get();
            foreach($users as $user){
                $filterUserIDs[]=$user->id;
            }
            $filterUserIDs[] = auth()->user()->id;
            $meetings = Meeting::whereBetween('start_time',$dates)
                ->whereHas('staffs',function($query) use($filterUserIDs){
                    return $query->whereIn('user_id',$filterUserIDs);
                })
                ->orderBy('start_time')
                ->get();
        }else{
            $user_id = auth()->user()->id;
            $meetings = Meeting::whereBetween('start_time',$dates)
                ->whereHas('staffs',function($query) use($user_id){
                    return $query->where('user_id',$user_id);
                })
                ->orderBy('start_time')
                ->get();
        }


        $data = array();
        $i=0;
        foreach($meetings as $meeting){
            $data[$i]['id'] = $meeting->id;
            $data[$i]['eventName'] = $meeting->title;
            $data[$i]['desc'] = $meeting->remarks;
            $department = $meeting->project->department->name;
            $project = $meeting->project->name;
            $data[$i]['time'] = Carbon::parse($meeting->start_time)->format('H:i A')."-".Carbon::parse($meeting->end_time)->format('H:i A')."|".$department."|".$project;
            $data[$i]['location'] = " ".trim($meeting->location);
            $data[$i]['date'] = date('Y-m-d',strtotime($meeting->start_time));
            $i++;
        }

        return collect($data)->toJson();
    }

    public function get_meetings_per_day(){
        $start_day_of_month = request()->get('start_date')." "."00:00:00";
        $end_day_of_month = request()->get('end_date')." "."23:59:59";
        $filterUserIDs = array();
        $users = User::where('supervisor_id',auth()->user()->id)->get();

        foreach($users as $user){
            $filterUserIDs[]=$user->id;
        }
        $filterUserIDs[] = auth()->user()->id;
        $start_day_of_month = Carbon::parse($start_day_of_month)->format('Y-m-d H:i:s');
        $end_day_of_month = Carbon::parse($end_day_of_month)->format('Y-m-d H:i:s');
        $dates = [$start_day_of_month,$end_day_of_month];

        if( auth()->user()->isAdmin() ){
            $meetings = Meeting::whereBetween('start_time',$dates)
                ->orderBy('start_time')
                ->get();
        }elseif( auth()->user()->isFieldStuff() ){
            $user_id = auth()->user()->id;
            $meetings = Meeting::whereBetween('start_time',$dates)
                ->whereHas( 'staffs',function($query) use($user_id){
                    $query->where('user_id',$user_id);
                })
                ->orderBy('start_time')
                ->get();
        }elseif(auth()->user()->isSupervisor()){
            $users = User::where('supervisor_id',auth()->user()->id)->get();
            foreach($users as $user){
                $filterUserIDs[]=$user->id;
            }
            $filterUserIDs[] = auth()->user()->id;
            $meetings = Meeting::whereBetween('start_time',$dates)
                ->whereHas('staffs',function($query) use($filterUserIDs){
                    $query->whereIn('user_id',$filterUserIDs);
                })
                ->orderBy('start_time')
                ->get();
        }else{
            $user_id = auth()->user()->id;
            $meetings = Meeting::whereBetween('start_time',$dates)
                ->whereHas('staffs',function($query)use($user_id){
                    $query->where('user_id',$user_id);
                })
                ->orderBy('start_time')
                ->get();
        }

        $data = array();
        foreach($meetings as $meeting){
            $start_date = Carbon::parse($meeting->start_time)->format('Y-m-d');
            if(isset($data[$start_date])){
                $data[$start_date]++;
            }else{
                $data[$start_date]=1;
            }
        }

        $ReturnData = array();
        $date = new Carbon($start_day_of_month);
        $end_date = new Carbon($end_day_of_month);
        $i=0;
        for(;$date->lessThanOrEqualTo($end_date); $date->addDay()) {
            $date_string = $date->format('Y-m-d');
            if( array_key_exists($date_string,$data) ){
                $ReturnData[$i]['date'] = $date_string;
                $ReturnData[$i]['meeting_count'] = '('.$data[$date_string].')';
            }else{
                $ReturnData[$i]['date'] = $date_string;
            }
            $i++;
        }
        return collect($ReturnData)->toJson();
    }


    public function surveySmsReplyReceive(){
        $params = request()->input();
        $param_number = count($params);
        if( $param_number <= 1 ||  $param_number >2 ){
            $response_array = array(
                "status"=>"FAILED", "message"=>"Incorrect request: Incorrect number of parameter."
            );
            return collect($response_array)->toJson();
        }
        if( ! request()->has('msisdn') || !request()->has('answer') ){
            $response_array = array(
                "status"=>"FAILED", "message"=>"Incorrect request. Missing required parameter."
            );
            return collect($response_array)->toJson();
        }
        $msisdn = request()->input('msisdn');
        $answer = request()->input('answer');
        $msisdn = urldecode($msisdn);
        $answer = urldecode($answer);
        $msisdn = trim($msisdn);
        $answer = trim($answer);
        if( empty($msisdn)||empty($answer) ){
            $response_array = array(
                "status"=>"FAILED", "message"=>"Incorrect request. Missing required parameter values."
            );
            return collect($response_array)->toJson();
        }else{
            ProcessSurveyResponse::dispatch($msisdn,$answer)->onConnection('sync');
            $response_array = array(
                "status"=>"OK", "message"=>"Thanks,for giving us response."
            );
            return collect($response_array)->toJson();
        }
    }

    private function meetingEntries(string $for = 'fs', int $year, int $month, array $userIds)
    {
        switch($for){
            case 'admin':
                return Meeting::whereYear('start_time',$year)
                    ->whereMonth('start_time',$month);
                break;
            default:
                return Meeting::whereHas('staffs',function($query)use($userIds){
                    return $query->whereIn('user_id',$userIds);
                })
                    ->whereYear('start_time',$year)
                    ->whereMonth('start_time',$month);
                break;
        }
    }

    private function meetingEntriesWithStatuses(string $for = 'fs', int $year, int $month, array $userIds, array $statuses)
    {
        return $this->meetingEntries($for, $year, $month, $userIds)->whereIn('status', $statuses);
    }

    private function countEntry(string $for = 'fs', string $fieldName, int $year, int $month, array $userIds)
    {
        return $this->meetingEntries($for, $year, $month, $userIds)
            ->distinct($fieldName)
            ->count($fieldName);
    }

    private function completedMeetingCount(string $for, int $year, int $month, array $userIds){
        switch($for){
            case 'admin':
                return Meeting::whereIn('status',['Completed'])
                    ->whereYear('start_time',$year)
                    ->whereMonth('start_time',$month)
                    ->count();
                break;
            default:
                return Meeting::whereHas('staffs',function($query)use($userIds){
                    return $query->whereIn('user_id',$userIds);
                })
                    ->whereYear('start_time',$year)
                    ->whereMonth('start_time',$month)
                    ->whereIn('status',['Completed'])
                    ->count();
                break;
        }
    }

    private function upcomingMeetings(string $for, array $userIds){
        switch($for){
            case 'admin':
                return Meeting::whereIn('status',['Initiated','Rescheduled'])
                    ->where('start_time','>',Carbon::now()->format('Y-m-d H:i:s'));
                break;
            default:
                return Meeting::whereHas('staffs',function($query)use($userIds){
                    return $query->whereIn('user_id',$userIds);
                })
                    ->whereIn('status',['Initiated','Rescheduled'])
                    ->where('start_time','>',Carbon::now()->format('Y-m-d H:i:s'));
                break;
        }
    }

    private function upcomingMeetingCount(string $for, array $userIds){
        return $this->upcomingMeetings($for, $userIds)->count();
    }

    private function getYears($clearCache = true){
        $key = 'years';
        if($clearCache) {
            if(cache()->has($key)) cache()->forget($key);
        }

        if(cache()->has($key)){
            $result = cache()->get($key);
            if(empty($result)){
                cache()->forget($key);
            }

            return $result;
        }else{
            $years =  Meeting::select(DB::raw('MAX(YEAR(start_time)) as max, MIN(YEAR(start_time)) as min'))->get();

            if(!$years->isEmpty()){
                $result = $years->first();
                cache()->put($key, $result, 1440);
            }
            else{
                $result = ['min' => date('Y'), 'max' => date('Y')];
            }
            return $result;
        }
    }

    private function getUserRole(){
        if(auth()->user()->isAdmin()){
            return 'admin';
        }else if(auth()->user()->isSupervisor()){
            return 'sv';
        } else {
            return 'fs';
        }
    }

    private function getRelevantUserIds(){
        if(auth()->user()->isAdmin()){
            return [];
        }else if(auth()->user()->isSupervisor()){
            $filterUserIDs[] = auth()->user()->id;
            foreach(User::where('supervisor_id',auth()->user()->id)->get() as $user){
                $filterUserIDs[]=$user->id;
            }
            return $filterUserIDs;
        } else {
            return [ auth()->user()->id ];
        }
    }

    private function getMeetingStaffNames(Meeting $meeting){
        $user_names = "";
        foreach($meeting->staffs as $staff){
            $user_names .= ($user_names == "") ? $staff->name : (", " . $staff->name);
        }
        return $user_names;
    }

    public function listAccount(){
        $user_id = auth()->user()->id;
        $criteria = request()->input('criteria', "ALL");
        $year = request()->input('year', date('Y'));
        $month = request()->input('month', date('m'));

        $role = $this->getUserRole();;
        $filterUserIDs = $this->getRelevantUserIds();

        if($criteria == "ALL" || $criteria == "Companies"){
            $userClientCompanyCount = $this->countEntry($role,'client_company_id',$year, $month, $filterUserIDs);
        }

        if($criteria == "ALL" || $criteria == "Projects"){
            $userClientProjectCount = $this->countEntry($role,'project_id',$year, $month, $filterUserIDs);
        }

        if($criteria == "ALL" || $criteria == "CompleteMeetings"){
            $CompletedMeetingCount = $this->completedMeetingCount($role, $year, $month, $filterUserIDs);
        }

        if($criteria == "ALL" || $criteria == "upcomingMeetings"){
            $upcomingMeetingCount   = $this->upcomingMeetingCount($role, $filterUserIDs);
        }

        return View('meetings.list_account',[
            'userCompanyCount'=>isset($userClientCompanyCount) ? $userClientCompanyCount : null,
            'userProjectCount'=>isset($userClientProjectCount)? $userClientProjectCount : null,
            'upComingMeeting'=>isset($upcomingMeetingCount)? $upcomingMeetingCount : null,
            'completedMeetingCount'=>isset($CompletedMeetingCount) ? $CompletedMeetingCount : null,
            'criteria' => isset($criteria) ? $criteria : null,
            'year' => isset($year) ? $year : null,
            'month' => isset($month) ? $month : null,
            'years' => $this->getYears(),
        ]);
    }

    public function listCompanyByUser(){
        $criteria = request()->input('criteria', "ALL");
        $year = request()->input('year', date('Y'));
        $month = request()->input('month', date('m'));
        $user_id = auth()->user()->id;

        $role = $this->getUserRole();;
        $filterUserIDs = $this->getRelevantUserIds();

        $meetings = $this->meetingEntries($role,$year,$month,$filterUserIDs)->get();

        $return_data = array();
        if(count($meetings) > 0 ){
            $return_data['Header'] = array("Company Name","Attending " . config('app.company_name') . " Staffs");
            $return_data['status'] = "OK";
            foreach($meetings as $meeting){
                $return_data['data'][] = [
                    "company_name"=>$meeting->clientCompany->company_name,
                    "user"=>$this->getMeetingStaffNames($meeting),
                ];
            }
            return collect($return_data)->toJson();
        }else{
            $return_data['status'] = "NOK";
            $return_data['message'] = "No Company data found.";
            return collect($return_data)->toJson();
        }
    }

    public function exportCompanyList(){
        $criteria = request()->input('criteria', "ALL");
        $year = request()->input('year', date('Y'));
        $month = request()->input('month', date('m'));
        $user_id = auth()->user()->id;

        $role = $this->getUserRole();;
        $filterUserIDs = $this->getRelevantUserIds();

        $meetings = $this->meetingEntries($role,$year, $month, $filterUserIDs)->get();

        $filename = "client_company_".date("Y-m-d H:i:s").".xls"; // File Name
        //Download file
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        $export_data = array();
        foreach($meetings as $meeting){
            $export_data[] = [
                "company_name"=>$meeting->clientCompany->company_name,
                "user"=>$this->getMeetingStaffNames($meeting)
            ];
        }

        echo implode("\t",array("Company Name","Attending " . config('app.company_name') . " Staffs"))."\r\n";// Heading for data
        foreach($export_data as $data){
            $values = array_values($data);
            echo implode("\t",$values)."\r\n";
        }
    }

    public function listProjectByUser(){
        $criteria = request()->input('criteria', "ALL");
        $year = request()->input('year', date('Y'));
        $month = request()->input('month', date('m'));
        $user_id = auth()->user()->id;

        $role = $this->getUserRole();;
        $filterUserIDs = $this->getRelevantUserIds();

        $meetings = $this->meetingEntries($role,$year, $month, $filterUserIDs)->get();

        $return_data = array();
        if( $meetings->count() > 0 ){
            $return_data['Header'] = array("Project Name","Field Staff","Client","Status");
            foreach( $meetings as $meeting ){
                $return_data['data'][] = [
                    "Project"=>$meeting->project->name,
                    "Staff"=>$this->getMeetingStaffNames($meeting),
                    "CompanyName"=>$meeting->clientCompany->company_name,
                    "Status"=>$meeting->status
                ];
            }
            $return_data['status'] = "OK";
            return collect($return_data)->toJson();
        }else{
            $return_data['status'] = "NOK";// not ok
            $return_data['message'] = "No project data found";
            return collect($return_data)->toJson();
        }
    }

    public function exportProjectList(){
        $criteria = request()->input('criteria', "ALL");
        $year = request()->input('year', date('Y'));
        $month = request()->input('month', date('m'));
        $user_id = auth()->user()->id;

        $role = $this->getUserRole();;
        $filterUserIDs = $this->getRelevantUserIds();

        $meetings = $this->meetingEntries($role,$year, $month, $filterUserIDs)->get();

        $filename = "project_list_".date("Y-m-d H:i:s").".xls"; // File Name
        //Download file
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        $export_data = array();
        foreach( $meetings as $meeting ){
            $export_data[] = array($meeting->project->name,$this->getMeetingStaffNames($meeting),$meeting->clientCompany->company_name,$meeting->status);
        }

        echo implode("\t",array("Project Name","Field Staff","Client","Status"))."\r\n";// Heading for data
        foreach($export_data as $data){
            $values = array_values($data);
            echo implode("\t",$values)."\r\n";
        }
    }

    public function listCompletedMeetingByUser(){
        $criteria = request()->input('criteria', "ALL");
        $year = request()->input('year', date('Y'));
        $month = request()->input('month', date('m'));
        $user_id = auth()->user()->id;

        $role = $this->getUserRole();;
        $filterUserIDs = $this->getRelevantUserIds();

        $meetings = $this->meetingEntriesWithStatuses($role,$year, $month, $filterUserIDs, ['Completed'])->get();

        $return_data = array();
        if( $meetings->count() > 0 ){
            $return_data['Header'] = array("Project Name","Field Staff","Client","Date","Time");
            foreach( $meetings as $meeting ){
                $start_time = new Carbon($meeting->start_time);
                $return_data['data'][] = [
                    "Project"=>$meeting->project->name,
                    "Staff"=>$this->getMeetingStaffNames($meeting),
                    "CompanyName"=>$meeting->clientCompany->company_name,
                    "Date"=> $start_time->format("Y-m-d"),
                    "Time"=> $start_time->format("h:i A")
                ];
            }
            $return_data['status'] = "OK";
            return collect($return_data)->toJson();
        }else{
            $return_data['status'] = "NOK";// not ok
            $return_data['message'] = "No data found";
            return collect($return_data)->toJson();
        }
    }

    public function exportCompletedMeetingList(){
        $criteria = request()->input('criteria', "ALL");
        $year = request()->input('year', date('Y'));
        $month = request()->input('month', date('m'));
        $user_id = auth()->user()->id;

        $role = $this->getUserRole();;
        $filterUserIDs = $this->getRelevantUserIds();

        $meetings = $this->meetingEntriesWithStatuses($role,$year, $month, $filterUserIDs, ['Completed'])->get();

        $filename = "completed_meeting_list_".date("Y-m-d H:i:s").".xls"; // File Name
        //Download file
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        $export_data = array();
        foreach( $meetings as $meeting ){
            $start_time = new Carbon($meeting->start_time);
            $export_data[] = array(
                $meeting->project->name,
                $this->getMeetingStaffNames($meeting),
                $meeting->clientCompany->company_name,
                $start_time->format("Y-m-d"),
                $start_time->format("h:i A"));
        }

        echo implode("\t",array("Project Name","Field Staff","Client","Date","Time"))."\r\n";// Heading for data
        foreach($export_data as $data){
            $values = array_values($data);
            echo implode("\t",$values)."\r\n";
        }
    }

    public function listUpcomingMeetingByUser(){
        $criteria = request()->input('criteria', "ALL");
        $year = request()->input('year', date('Y'));
        $month = request()->input('month', date('m'));
        $user_id = auth()->user()->id;

        $role = $this->getUserRole();;
        $filterUserIDs = $this->getRelevantUserIds();

        $meetings = $this->upcomingMeetings($role,$filterUserIDs)->get();

        $return_data = array();
        if( $meetings->count() > 0 ){
            $return_data['Header'] = array("Project Name","Field Staff","Client","Date","Time");
            foreach( $meetings as $meeting ){
                $start_time = new Carbon($meeting->start_time);
                $return_data['data'][] = [
                    "Project"=>$meeting->project->name,
                    "Staff"=>$this->getMeetingStaffNames($meeting),
                    "CompanyName"=>$meeting->clientCompany->company_name,
                    "Date"=> $start_time->format("Y-m-d"),
                    "Time"=> $start_time->format("h:i A")
                ];
            }
            $return_data['status'] = "OK";
            return collect($return_data)->toJson();
        }else{
            $return_data['status'] = "NOK";// not ok
            $return_data['message'] = "No data found";
            return collect($return_data)->toJson();
        }
    }

    public function exportUpcomingMeetingList(){
        $criteria = request()->input('criteria', "ALL");
        $year = request()->input('year', date('Y'));
        $month = request()->input('month', date('m'));
        $user_id = auth()->user()->id;

        $role = $this->getUserRole();;
        $filterUserIDs = $this->getRelevantUserIds();

        $meetings = $this->upcomingMeetings($role,$filterUserIDs)->get();

        $filename = "upcoming_meeting_list_".date("Y-m-d H:i:s").".xls"; // File Name
        //Download file
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        $export_data = array();
        foreach( $meetings as $meeting ){
            $start_time = new Carbon($meeting->start_time);
            $export_data[] = array(
                $meeting->project->name,
                $this->getMeetingStaffNames($meeting),
                $meeting->clientCompany->company_name,
                $start_time->format("Y-m-d"),
                $start_time->format("h:i A"));
        }

        echo implode("\t",array("Project Name","Field Staff","Client","Date","Time"))."\r\n";// Heading for data
        foreach($export_data as $data){
            $values = array_values($data);
            echo implode("\t",$values)."\r\n";
        }
    }
}