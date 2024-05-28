<?php
namespace App\Services\Moodle;

use App\Models\Program;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class CourseService extends BaseService
{
    protected $model = Subject::class;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create Subject On Moodle
     */
    public function store(Subject $subject, $category_id)
    {
        // Create the data array for the POST request
        $courses = [
            [
                'fullname' => $subject->title,
                'shortname' => $subject->code,
                'categoryid' => $category_id,  // Assuming 1 is a valid category ID
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

        $query_params['courses'] = $courses;
        $query_params['wsfunction'] = 'core_course_create_courses';
        return parent::create($query_params);
    }


    /**
     * Update Subject On Moodle
     */
    public function edit(Subject $subject, $category_id)
    {

        $courses = [
            [
                'id' => $subject->id_on_moodle,
                'fullname' => $subject->title,
                'shortname' => $subject->code,
                'categoryid' => $category_id,  // Assuming 1 is a valid category ID
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

        $query_params['courses'] = $courses;
        $query_params['wsfunction'] = 'core_course_update_courses';


        parent::update($query_params);
    }

    /**
     * delete Subject On Moodle
     */
    public function destroy(Subject $subject)
    {
        $query_params['courseids'][] = $subject->id_on_moodle;
        $query_params['wsfunction'] = 'core_course_delete_courses';
        parent::delete($query_params);
    }




}
