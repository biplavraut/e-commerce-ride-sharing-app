<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\pool; 
use App\User; 
Use App\UserVehicles;  
Use App\PoolUserRequest;  
use Illuminate\Http\Request;
use App\Http\Requests\Api\PoolRequest;
use Firebase\FirebaseLib;
use App\Http\Resources\Api\PoolResource;
use App\Http\Requests\Api\Uservehiclerequest;
use App\Http\Resources\Api\UserVehicleResource;
use App\Custom\PushNotification;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\UserResource;


class PoolController extends Controller
{
    private $user;
    protected $path = 'pool/';
    protected $firebase;
    protected $firebaseURL = 'https://gogo20-292702.firebaseio.com/';
    protected $firebaseSecret = 'jfdgoAhbPyGqzllfRbYFU8pdt1qI29XHRQKlRy3T';

    public function __construct()
    {
        $this->firebase = new FirebaseLib($this->firebaseURL, $this->firebaseSecret);
        $this->user = auth()->guard('api')->user();
        if (!$this->user) {
            return failureResponse("Token Expired.", 401, 401);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PoolResource::collection(Pool::orderBy('created_at', 'desc')->where(['status'=>0,'pool_type'=>'offer'])->paginate(10))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pool_id' => 'required|integer',
        ]);
        
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        $pool_id = $request->pool_id;
        $getPool = Pool::where(["status"=>0,"id"=>$pool_id,"pool_type"=>"offer"])->first();
        if(empty($getPool)){
            return failureResponse("Selected pool is not available", 422, 422);
        }else{
          $data = [
            "requested_user_id" => $this->user->id,
            "requested_pool_id" => $pool_id,
            "remarks" => "Pool request sending",
            "status" => 0,
          ];
          $pullrequest = PoolUserRequest::create($data);
          if($pullrequest){
            //   $getPool->update(["status"=>1]);
              //Sent notification to user for accepting pool request
              $token = isset($getPool->user->device->device_token)?$getPool->user->device->device_token:null;
              $this->triggerNotification([$token],$getPool,$this->user);
              $this->firebaseRTD($this->user, $getPool,$getPool->user);
              return successResponse("Pool request successfully send");
          }else{
              return failureResponse("Unable to request for pool", 422, 422);
          }
        }
    }

    private function triggerNotification(array $token, Pool $pool, User $user)
    {
        $notification = new PushNotification(
            $token,
            [
                'title' => 'Requested by for pool',
                'pool' => new PoolResource($pool),
                'user' => new UserResource($user),
                'type' => 'poolrequest'
            ]
        );
        $notification->send();
    }

    private function firebaseRTD(User $user, Pool $pool, User $rider)
    {
        $poolArray = [
            'user' => new UserResource($user),
            'pool' => new PoolResource($pool),
            'rider' => new UserResource($rider),
        ];
       $response = $this->firebase->set($this->path . $pool->id, $poolArray);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoolRequest $request)
    {
        if($request->pool_type == "Request" || $request->pool_type == "request"){
            $getMatchedVechicle = Pool::all();
            // $getMatchedVechicle = Pool::where(["vechical_type"=>$request->vechical_type,"required_seat"=>$request->required_seat])->get();
            return PoolResource::collection($getMatchedVechicle)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        }else if($request->pool_type == "offer" || $request->pool_type == "Offer"){
            $getUserVechicle = $this->user->vehicles->where("id",$request->vehicle_id);
            if($getUserVechicle->count() < 1){
                return failureResponse("Selected vehicle not found", 422, 422);
            }
            $allRequest = $request->all();
            $allRequest['user_id'] =  $this->user->id;
            $allRequest['user_id'] =  $this->user->id;
            $allRequest['vehicle_id'] = $request->vehicle_id;
            $saveRecord = Pool::create($allRequest);
            if($saveRecord){
                return successResponse("Pool request successfully created");
            }else{
                return failureResponse("Unable to process", 422, 422);
            }
        }
    }

    public function poolAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pool_id' => 'required|integer',
            'action' => 'required|string|in:accept,reject,cancel',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $getPool =  pool::findorFail($request->pool_id);
        $requestedData =  PoolUserRequest::where("requested_pool_id",$request->pool_id)->first();
        $remarks = $msg = "";
        $status = 0;
        switch ($request->action) {
            case 'accept':
                $remarks = "Pool request accepted by rider";
                $msg = "Your pool request is accepted";
                $status = 1;
                break;
            case 'reject':
                $remarks = "Pool request rejected by rider";
                $msg = "Your pool request is rejected";
                $status = 2;
                break;
            case 'cancel':
                $remarks = "Pool request canceled by rider";
                $msg = "Your pool request is canceled";
                $status = 3;
                break;
        }
        $getPool->update(["status"=>0]);
        $requestedData->update(["status"=>$status,"remarks"=>$remarks]);
        $token = isset($getPool->user->device->device_token)?$getPool->user->device->device_token:null;
        //Notify user about action as well
        $notification = new PushNotification(
            [$token],
            [
                'title' => $msg,
                'type' => 'poolrequest'
            ]
        );
        $notification->send();
        return successResponse("Pool request ".$request->action." successfully");
    }

    public function getmyvechiclelist()
    {
        return UserVehicleResource::collection($this->user->vehicles)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function addVehicle(Uservehiclerequest $request){
        $allRequest = $request->all();
        $allRequest['user_id'] =  $this->user->id;
        $allRequest['is_verified'] = 1;
        $savevechicle = UserVehicles::create($allRequest);
        if($savevechicle){
            return successResponse("Vehicle added successfully");
        }else{
            return failureResponse("Unable to process", 422, 422);
        }
    }

    public function cancel(PoolRequest $request)
    {
        $poolId = $request->pool_id;
        $details = Pool::findorFail($poolId);
        $details->status = 2; ///Set cancel flag
        if($details->save()){
            return successResponse("Pool request cancelled successfully");
        }
        return failureResponse("Unable to process", 422, 422);
    }

    /**
     * Display the specified resource.
     * @param  \App\pool  $pool
     * @return \Illuminate\Http\Response
     */
    public function show(pool $pool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\pool  $pool
     * @return \Illuminate\Http\Response
     */
    public function edit(pool $pool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\pool  $pool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, pool $pool)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\pool  $pool
     * @return \Illuminate\Http\Response
     */
    public function destroy(pool $pool)
    {
        //
    }
}
