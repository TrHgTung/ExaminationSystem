<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function createExam() { // tu dong tao de thi
        // code here
        return response()->json([
            'message' => 'Da tao dap an',
        ], 200);
    }
}
