<?php

namespace Vo1\Seat\AwoxFinder\Http\Controllers;

use Illuminate\Http\Request;
use Seat\Api\Http\Controllers\Api\v2\ApiController;
use Vo1\Seat\AwoxFinder\Jobs\Universe\Ids as Name2Id;

class AwoxApiController extends ApiController
{
    /**
     * @param $name
     * @return mixed
     */
    public function find($name)
    {
        $job = new Name2Id();
        $job->setNames([$name]);
        $chars = $job->handle();
        return $chars->toArray();
    }
}
