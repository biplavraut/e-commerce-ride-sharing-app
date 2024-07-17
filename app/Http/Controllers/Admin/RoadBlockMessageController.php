<?php

namespace App\Http\Controllers\Admin;

use App\RoadBlockMessage;
use Illuminate\Http\Request;
use App\Services\RoadBlockNotificationService;
use App\Http\Requests\Admin\RoadBlockNotificationRequest;
use App\Http\Resources\Admin\RoadBlockNotificationResource;

class RoadBlockMessageController extends CommonController
{
    /** @var RoadBlockNotificationService */
    private $RoadBlockNotificationService;
    
    
    public function __construct(RoadBlockNotificationService $RoadBlockNotificationService)
    {
        parent::__construct();
        $this->RoadBlockNotificationService = $RoadBlockNotificationService;
    }

    public function index()
    {
        $roadBlockNotification = $this->RoadBlockNotificationService->getForIndex(
            $this->paginationLimit
        );
        return RoadBlockNotificationResource::collection($roadBlockNotification);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RoadBlockMessage  $roadBlockMessage
     * @return \Illuminate\Http\Response
     */
    public function show($roadBlockId)
    {
        $roadBlockMessage = $this->RoadBlockNotificationService->findOrFail($roadBlockId);
        return new RoadBlockNotificationResource($roadBlockMessage);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RoadBlockMessage  $roadBlockMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(RoadBlockMessage $roadBlockMessage)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RoadBlockMessage  $roadBlockMessage
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, RoadBlockMessage $roadBlockMessage)
    // {
    //     //
    // }

    public function update(RoadBlockNotificationRequest $request, $roadBlockId)
    {
        $roadBlockMessage = $this->RoadBlockNotificationService->update($roadBlockId, $request->validated());
        return new RoadBlockNotificationResource($roadBlockMessage);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RoadBlockMessage  $roadBlockMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoadBlockMessage $roadBlockMessage)
    {
        //
    }

    public function search(Request $request)
    {
        $notifications = $this->RoadBlockNotificationService->query()->where('title', 'LIKE', $request->name . '%')->take(10)->get();
        dd($notifications);
        return RoadBlockNotificationResource::collection($notifications);
    }

}
