<?php

namespace Vo1\Seat\AwoxFinder\Http\Controllers;
use Seat\Web\Http\Controllers\Controller;

class AwoxController extends Controller
{
    public function list()
    {
        $list = [];
        return view('awox::list', compact('list'));
    }

    public function create()
    {
        return 'hahaha';
    }

    public function read()
    {
        $entry = [];
        return view('awox::read', compact('entry'));
    }

    public function update()
    {
        $entry = [];
        return view('awox::read', compact('entry'));
    }

    public function delete()
    {
        return 'ohohohoh';
    }
}
