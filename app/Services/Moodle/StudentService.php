<?php
namespace App\Services\Moodle;

use App\Models\Program;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class StudentService extends BaseService
{
    protected $model = Student::class;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create Subject On Moodle
     */
    public function create(Student $student, Request $request , $password)
    {
        try {

            // Create the data array for the POST request
            $courses = [
                [
                    'username' => preg_replace('/[^-.@_0-9a-z]/', '', strtolower($student->first_name . ' ' . $student->last_name)), // Hardcoded username
                    'firstname' => $student->first_name, // Hardcoded first name
                    'password' => $password.'#',
                    'lastname' => $student->last_name, // Hardcoded last name
                    'email' => $student->email, // Hardcoded email
                    'country' => $student->country ?? "", // Optional home country code
                    'middlename' => $student->father_name ?? "", // Optional
                    'idnumber' => $student->registration_no, // Default to ""
                    'institution' => 'Example Institution', // Optional
                    'department' => $student->program->title, // Optional
                    'phone1' => $student->phone, // Optional
                    'address' => $student->present_address, // Optional
                    'lang' => 'en', // Default language
                ]
            ];

            $this->params['users'] = $courses;
            $this->params['wsfunction'] = 'core_user_create_users';

            // Build the full URL with the parameters
            $this->url = $this->url . '?' . http_build_query($this->params);

            // Send the POST request with form parameters
            $response = $this->http->post(
                $this->url,
            )->json();
            return isset($response) && !isset($response['exception']) ? $response : throw new \Exception($response['exception']);
        } catch (Throwable $e) {
            logError(e: $e, method: __METHOD__, class: get_class($this));
        }
    }


    /**
     * Update Subject On Moodle
     */
    public function update(Subject $subject, Request $request)
    {
        try {
            // bring the selected program "category_on_moodle" title to found it on moodle so we can pass it's id :)
            $selected_moodle_program = Program::query()->select(['id', 'title', 'id_on_moodle'])->findOrFail($request->category_on_moodle);
            // Create the data array for the POST request
            $courses = [
                [
                    'id' => $subject->id_on_moodle,
                    'fullname' => $subject->title,
                    'shortname' => $subject->code,
                    'categoryid' => $selected_moodle_program->id_on_moodle,  // Assuming 1 is a valid category ID
                    'idnumber' => $subject->code,  // Optional
                    // 'summary' => 'This is a dummy course.',  // Optional
                    // 'summaryformat' => 1,  // Default to HTML
                    // 'format' => 'topics',  // Default to topics
                    // 'showgrades' => 1,  // Default to 1
                    // 'newsitems' => 5,  // Default to 5
                    // 'startdate' => time(),  // Optional, current timestamp
                    // 'enddate' => time() + (7 * 24 * 60 * 60),  // Optional, one week from now
                    // 'numsections' => 10,  // Optional
                    'maxbytes' => 1048576,  // 1MB, default to 0
                    // 'showreports' => 0,  // Default to 0
                    'visible' => $subject->status,  // Optional, default to 1
                    // 'hiddensections' => 0,  // Optional
                    // 'groupmode' => 0,  // Default to 0
                    // 'groupmodeforce' => 0,  // Default to 0
                    // 'defaultgroupingid' => 0,  // Default to 0
                    // 'enablecompletion' => 1,  // Optional, default to 1
                    // 'completionnotify' => 0,  // Optional, default to 0
                    'lang' => 'en',  // Optional
                ]
            ];

            $this->params['courses'] = $courses;
            $this->params['wsfunction'] = 'core_course_update_courses';

            // Build the full URL with the parameters
            $this->url = $this->url . '?' . http_build_query($this->params);

            // Send the POST request with form parameters
            $response = $this->http->post(
                $this->url,
            )->json();

            return isset($response) && !isset($response['exception']) ? $response : throw new \Exception($response['exception']);
        } catch (Throwable $e) {
            logError(e: $e, method: __METHOD__, class: get_class($this));
        }
    }


    /**
     * Get Category of the Course
     */
    public function category(Subject $subject): Program
    {
        $course_on_moodle = $this->findById($subject->id_on_moodle);
        $course_category = $this->program_service->findById($course_on_moodle['categoryid']);
        return Program::query()->where('title', $course_category['name'])->firstOrFail();
    }


    /**
     * Find Course In Moodle By It's 'ID ON MOODLE'
     */
    public function findById($id)
    {
        // Set the web service function name
        $this->params['wsfunction'] = 'core_course_get_courses';
        $this->params['options'] = [
            'ids' => [
                $id,
            ],
        ];
        $moodle_course = $this->http->get($this->url, $this->params)->json();
        if (count($moodle_course) != 1) {
            throw new \Exception('Found More Than One Program Or None');
        }

        return $moodle_course[0];
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
