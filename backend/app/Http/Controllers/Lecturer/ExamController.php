<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lecturer;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Student;
use App\Models\Exam;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('isLecturer')->only(['createQuestion', 'createAnswer', 'getExamList']); // chi cho phep admin thuc hien nhung ham nay
    }

    public function getExamList() { // lay danh sach de thi
        $getAllExams = Exam::all();
        if (!$getAllExams) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac chua co de thi nao (Lien he Admin)',
            ], 404);
        }

        return response()->json([
            'examList' => $getAllExams,
        ], 200);
    }

    public function createQuestion($examID, Request $request) { // tao cac cau hoi
        // $getChosenExam = Exam::where('ExamID', $examID)->first();
        // if (!$getChosenExam) {
        //     return response()->json([
        //         'message' => 'Loi may chu',
        //     ], 500);
        // }
        $getLecturerEmail = auth()->user()->Email;

        $validateInfo = $request->validate([
            'QuestionContent' => 'required',
            'QuestionImage' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'QuestionType' => 'required',
        ]);

        // Tu dong tao QuestionID
        $lastQuestion = Question::orderBy('QuestionID', 'desc')->first();
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
    
        // tao du lieu trong Question
        $createQuestion = Question::create([
            'QuestionID' => $newQuestionID,
            'QuestionContent' => $validateInfo['QuestionContent'],
            'QuestionImage' => $validateInfo['QuestionImage'],
            'QuestionType' => $validateInfo['QuestionType'],
            'Author' => $getLecturerEmail,
        ]);
        // tao du lieu trong QuestionExam (QE))
        // tao QE ID
        $lastQE = QuestionExam::orderBy('QuestionExamID', 'desc')->first();
        $qECount = 0;
    
        if ($lastQE) {
            $lastQEID = $lastQE->QuestionExamID;
            $lastNumber = (int) substr($lastQEID, 3);
            $qECount = (int)$lastNumber;
            $newQEID = 'QE_' . str_pad($qECount + 1, 6, '0', STR_PAD_LEFT);
        }
    
        else {
            $newQEID = 'QE_000001'; // question exam dau tien
        }

        $createQuestionExam = QuestionExam::create([
            'QuestionExamID' => $newQEID,
            'QuesionID' => $newQuestionID,
            'ExamID' => $examID,
        ]);
        

        if (!$createQuestion || !$createQuestionExam) {
            return response()->json([
                'message' => 'Loi ket noi du lieu',
            ], 500);
        }
        
        return response()->json([
            'message' => 'Da tao cau hoi',
        ], 200);
    }
    
    public function createAnswer(Request $request) { // tao dap an cho cau hoi
        $validateInfo = $request->validate([
            'AnswerContent' => 'required',
            'AmswerImage' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'IsCorrect' => 'required|boolean',
            'QuestionID' => 'required',
        ]);

        // Tu dong tao AnswerID
        $lastAnswer = Answer::orderBy('AnswerID', 'desc')->first();
        $ansCount = 0;
    
        if ($lastAnswer) {
            $lastAnswerID = $lastAnswer->AnswerID;
            $lastNumber = (int) substr($lastAnswerID, 3);
            $ansCount = (int)$lastNumber;
            $newAnsID = 'AN_' . str_pad($ansCount + 1, 6, '0', STR_PAD_LEFT);
        }
    
        else {
            $newAnsID = 'AN_000001'; // dap an dau tien
        }
    
        $createAns = Question::create([
            'AnswerID' => $newAnsID,
            'AnswerContent' => $validateInfo['QuestionContent'],
            'AnswerImage' => $validateInfo['QuestionImage'],
            'IsCorrect' => $validateInfo['IsCorrect'],
            'QuestionID' => $validateInfo['QuestionID'],
        ]);

        if (!$createAns) {
            return response()->json([
                'message' => 'Loi ket noi du lieu',
            ], 500);
        }
    
        
        return response()->json([
            'message' => 'Da tao dap an',
        ], 200);
    }
}
