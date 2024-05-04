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
            $bbb->setTimeOut(180);
            $response = $bbb->getRecordings($recordingParams);
            $data['recordings'] = [];
            $data['title'] = $this->title;
            $data['route'] = $this->route;
            $data['path'] = $this->path;
            if ($response->getReturnCode() == 'SUCCESS') {
                foreach($response->getRawXml()->recordings->recording as $recording)
                {
                    $obj = new stdClass;
                    $obj->name = (string) $recording->name;
                    $obj->meetingId = isset($recording->metadata->meetingId) ? (string) $recording->metadata->meetingId : null;
                    $obj->start_time = date('Y-m-d H:i:s', (int) ($recording->startTime / 1000));
                    $obj->preview_img = isset($recording->playback->format->preview->images->image[0]) ? (string) $recording->playback->format->preview->images->image[0] : asset('public/dashboard/images/placeholder.jpg');
                    $obj->state = (string) $recording->state;
                }
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
