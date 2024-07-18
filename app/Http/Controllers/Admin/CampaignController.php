<?php

namespace App\Http\Controllers\Admin;

use App\Driver;
use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Services\CampaignService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignRequest;
use App\Http\Resources\Admin\CampaignResource;
use App\Vendor;

class CampaignController  extends CommonController
{
    /** @var CampaignService */
    private $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        parent::__construct();
        $this->campaignService = $campaignService;
    }

    public function index()
    {
        $campaigns = $this->campaignService->getForIndex(
            $this->paginationLimit
        );

        return CampaignResource::collection($campaigns);
    }

    public function store(CampaignRequest $request)
    {
        DB::transaction(function () use ($request, &$campaign) {

            if (count($request->winners) > 0) {
                foreach ($request->winners as $key => $winner) {
                    if ($request->user_type == "rider") {
                        $user = Driver::where('phone', $winner)->first();
                        $user->myNotifications()->create(['title' => 'Prize Received', 'message' => 'You received ' . $request->prizes[$key] . ' from ' . $request->name . ' Campaign.', 'type' => $request->types[$key]]);
                    } else if ($request->user_type == "vendor") {
                        $user = Vendor::where('phone', $winner)->first();
                        $user->myNotifications()->create(['title' => 'Prize Received', 'message' => 'You received ' . $request->prizes[$key] . ' from ' . $request->name . ' Campaign.', 'type' => $request->types[$key]]);
                    } else {
                        $user = User::where('phone', $winner)->first();
                        if ($user && $request->types[$key] == "amount") {
                            if ($user->gogoWallet) {
                                $user->gogoWallet()->update(['amount' => $user->gogoWallet->amount + $request->prizes[$key]]);
                            } else {
                                $user->gogoWallet()->create(['amount' => $request->prizes[$key]]);
                            }
                            $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $request->prizes[$key], 'from' => $request->name . ' Campaign']);

                            $user->myNotifications()->create(['title' => 'Prize Received', 'message' => 'You received ' . $request->prizes[$key] . ' gogoPoint from ' . $request->name . ' Campaign.', 'type' => 'point']);
                        } else if ($user && $request->types[$key] != "amount") {
                            $user->myNotifications()->create(['title' => 'Prize Received', 'message' => 'You received ' . $request->prizes[$key] . ' from ' . $request->name . ' Campaign.', 'type' => 'belonging']);
                        }
                    }
                }
            }

            $campaign = $this->campaignService->store($request->validated());

            return new CampaignResource($campaign);
        });
    }

    public function show($campaignId)
    {
        $campaign = $this->campaignService->findOrFail($campaignId);

        return new CampaignResource($campaign);
    }

    public function update(CampaignRequest $request, $campaignId)
    {
        $campaign = $this->campaignService->update($campaignId, $request->validated());

        return new CampaignResource($campaign);
    }

    public function destroy($campaignId)
    {
        $campaign = $this->campaignService->delete($campaignId);

        return response('success');
    }

    public function search(Request $request)
    {
        return CampaignResource::collection($this->campaignService->query()->where('name', 'LIKE', $request->name . '%')->paginate($this->paginationLimit));
    }

    public function userSearch(Request $request)
    {
        if ($request->ajax() && strlen($request->name) > 0) {

            if ($request->type == "rider") {
                $users = Driver::select(['id', 'first_name', 'last_name', 'phone', 'email'])->where("first_name", 'LIKE', $request->name . "%")
                    ->orWhere("last_name", 'LIKE', $request->name . "%")
                    ->orWhere("phone", 'LIKE', $request->name . "%")
                    ->orWhere("email", 'LIKE', $request->name . "%")->take(10)->get();
            } else if ($request->type == "vendor") {
                $users = Vendor::select(['id', 'business_name', 'first_name', 'last_name', 'phone', 'email'])->where("first_name", 'LIKE', $request->name . "%")
                    ->orWhere("last_name", 'LIKE', $request->name . "%")
                    ->orWhere("business_name", 'LIKE', $request->name . "%")
                    ->orWhere("phone", 'LIKE', $request->name . "%")
                    ->orWhere("email", 'LIKE', $request->name . "%")->take(10)->get();
            } else {
                $users = User::select(['id', 'first_name', 'last_name', 'phone', 'email'])->where("first_name", 'LIKE', $request->name . "%")
                    ->orWhere("last_name", 'LIKE', $request->name . "%")
                    ->orWhere("phone", 'LIKE', $request->name . "%")
                    ->orWhere("email", 'LIKE', $request->name . "%")->take(10)->get();
            }
            return $users;
        }

        return [];
    }
}
