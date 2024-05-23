<?php
namespace App\Services\Moodle;

use App\Models\Program;
use App\Models\Subject;
use Illuminate\Support\Facades\Http;
use Throwable;

class CourseService extends BaseService
{
    protected $model = Subject::class;

    public function create(Subject $program)
    {
        try {
            // Set the web service function name
            $this->params['wsfunction'] = 'core_course_create_courses';

            // Create the data array for the POST request
            $data = [
                'courses' => [
                    [
                        'fullname' => 'Dummy Fullname',
                        'shortname' => 'dummy_shortname',
                        'categoryid' => 1,  // Assuming 1 is a valid category ID
                        'idnumber' => 'ID123',  // Optional
                        'summary' => 'This is a dummy course.',  // Optional
                        'summaryformat' => 1,  // Default to HTML
                        'format' => 'topics',  // Default to topics
                        'showgrades' => 1,  // Default to 1
                        'newsitems' => 5,  // Default to 5
                        'startdate' => time(),  // Optional, current timestamp
                        'enddate' => time() + (7 * 24 * 60 * 60),  // Optional, one week from now
                        'numsections' => 10,  // Optional
                        'maxbytes' => 1048576,  // 1MB, default to 0
                        'showreports' => 0,  // Default to 0
                        'visible' => 1,  // Optional, default to 1
                        'hiddensections' => 0,  // Optional
                        'groupmode' => 0,  // Default to 0
                        'groupmodeforce' => 0,  // Default to 0
                        'defaultgroupingid' => 0,  // Default to 0
                        'enablecompletion' => 1,  // Optional, default to 1
                        'completionnotify' => 0,  // Optional, default to 0
                        'lang' => 'en',  // Optional
                        'forcetheme' => 'clean',  // Optional
                        'courseformatoptions' => [  // Optional
                            [
                                'name' => 'numsections',
                                'value' => '10'
                            ]
                        ],
                        'customfields' => [  // Optional
                            [
                                'shortname' => 'customfield1',
                                'value' => 'customvalue1'
                            ]
                        ]
                    ]
                ]
            ];
            // Build the full URL with the parameters
            $this->url = $this->url . '?' . http_build_query($this->params);

            // Send the POST request with form parameters
            $response = $this->http->post(
                $this->url,
                $data
            );

            // Handle the response
            dd($response->body(), $response->json(), $response);
        } catch (Throwable $e) {
            dd($e);
            logError(e: $e, method: __METHOD__, class: get_class($this));
        }
    }



    /**
     * get array of categories "Programs"
     */
    public function get()
    {
        $this->params['wsfunction'] = 'core_course_get_categories';
        $response = $this->http->get($this->url, $this->params);
        return $response->json();
    }
}
