<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Lecturer;
use App\Models\Question;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('isLecturer')->only(['createQuestion', 'createAnswer']); // chi cho phep admin thuc hien nhung ham nay
    }

    public function createQuestion(Request $request) { // tao cac cau hoi
        $thisYear = date('Y');
        $validateInfo = $request->validate([
            'QuestionContent' => 'required',
            'QuestionImage' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'QuestionType' => 'required',
        ]);

    // Tu dong tao QuestionID
    $lastQuestion = Question::orderBy('QuestionID', 'desc')->first();
    // $studentCount1 = Student::count();
    $questionCount = 0;
    
        if ($lastQuestion) {
            $lastQuestionID = $lastQuestion->QuestionID;
            $lastNumber = (int) substr($lastQuestionID, 3);
            $questionCount = (int)$lastNumber;
            $newQuestionID = 'QT_' . str_pad($questionCount + 1, 6, '0', STR_PAD_LEFT);
        }
    
        else {
            $newQuestionID = 'QT_000001'; // question dau tien
        }
    
        $createQuestion = Question::create([
            'QuestionID' => $newQuestionID,
            'QuestionContent' => $validateStudentInfo['QuestionContent'],
            'QuestionImage' => $validateStudentInfo['QuestionImage'],
            'QuestionType' => $validateStudentInfo['QuestionType'],
        ]);

        if (!$createQuestion) {
            return response()->json([
                'message' => 'Loi ket noi du lieu',
            ], 500);
        }
    
        
        return response()->json([
            'message' => 'Da tao cau hoi',
        ], 200);
    }
    
    public function createAnswer(Request $request) { // tao dap an cho cau hoi
        // code here
        return response()->json([
            'message' => 'Da tao dap an',
        ], 200);
    }
}
