<?php
namespace App\Services\Moodle;

use App\Models\Program;
use App\Models\Session;
use App\Models\StudentEnroll;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class RoleService extends BaseService
{
    /**
     * Used To Get Roles From moodle.
     *
     * get array of categories "Programs"
     */
    public function getAllRoles()
    {
        #enrol_manual_enrol_users
        $this->params['wsfunction'] = 'local_wsgetroles_get_roles';
        $response = $this->http->get($this->url, $this->params);
        return $response->json();
    }

    /**
     * Get Student Role Id From Moodle
     */
    public function getStudentRoleId()
    {
        $roles = $this->getAllRoles();
        foreach($roles as $role)
        {
            if(@$role['shortname'] == 'student' || @$role['archetype'] == 'student')
            {
                return $role['id'];
            }
        }
        throw new \Exception('Student Role On Not Found');
    }
}
