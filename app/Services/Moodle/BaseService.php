<?php
namespace App\Services\Moodle;

use Illuminate\Support\Facades\Http;

class BaseService
{
    /**
     * Moodle Service To Handle Webservices connection with moodle
     * REST API Protocol
     */
    protected $configs, $params , $url, $http;
    public function __construct()
    {
        $this->configs = config('moodle');
        $this->params = [
            'wstoken' => $this->configs['token'],
            'moodlewsrestformat' => 'json',
        ];
        $this->url = $this->configs['host'].'/'.$this->configs['uri'];
        $this->http = Http::timeout($this->configs['timeout']);
    }

    public function getCourses()
    {
        $this->params['wsfunction'] = 'core_course_get_courses';
        $response = $this->http->get($this->url, $this->params);
        return $response;
    }


    public function core_course_get_categories()
    {
        $this->params['wsfunction'] = 'core_course_get_categories';
        $response = $this->http->get($this->url, $this->params);
        return $response->json();
    }
}
