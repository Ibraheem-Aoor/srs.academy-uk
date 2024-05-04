<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\AppHelper;
use App\Helpers\BBBhelper;
use App\Models\ClassRoutine;
use App\Models\District;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Enum\Role;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use stdClass;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use Yoeunes\Toastr\Facades\Toastr;

class BigBlueButtonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Module Data
        $this->title = __('module_bbb');
        $this->route = 'admin.bbb.';
        $this->view = 'admin.bbb.';
        $this->path = 'BigBlueButton';
        $this->access = 'bbb';


        $this->middleware('permission:' . $this->access . '-view', ['only' => ['index']]);
    }


    public function index()
    {
        try {
            $recordingParams = new GetRecordingsParameters();
            $bbb = new BigBlueButton();
            $response = $bbb->getRecordings($recordingParams);
            $data['recordings'] = [];
            if ($response->getReturnCode() == 'SUCCESS') {
                $data['recordings'] =  $response->getRawXml()->recordings;
            }
            return view($this->view . 'recordings.index', $data);
        } catch (Throwable $e) {
            dd($e);
            info('Error in ' . __METHOD__ . ' : ' . $e->getMessage());
            Toastr::error(__('general_error'));
            return back();
        }
    }
}
