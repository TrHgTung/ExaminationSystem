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
        $this->middleware('isLecturer')->only([
            'createQuestion', 'createAnswer', 'updateQuestion', 'updateAnswer'
        ]); // chi cho phep admin thuc hien nhung ham nay
    }

    // post
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
    
    // post
    public function createAnswer($questionID, Request $request) { // tao dap an cho cau hoi
        if(!$questionID) {
            return response()->json([
                'message' => 'Loi may chu',
            ], 500);
        }
        
        $validateInfo = $request->validate([
            'AnswerContent' => 'required',
            'AmswerImage' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'IsCorrect' => 'required|boolean',
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
    
        $createAns = Answer::create([
            'AnswerID' => $newAnsID,
            'AnswerContent' => $validateInfo['QuestionContent'],
            'AnswerImage' => $validateInfo['QuestionImage'],
            'IsCorrect' => $validateInfo['IsCorrect'],
            'QuestionID' => $questionID,
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

    // patch: update cau hoi
    public function updateQuestion(Request $request, $questionID) {
        $getQuestion = Question::where('QuestionID', $questionID)->first();
        if (!$getQuestion) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac ban chua tao dap an nao',
            ], 404);
        }

        $validateQuestion = $request->validate([
            'QuestionContent' => 'required',
        ]);

        $getQuestion->update(
            [
                'QuestionContent' => $validateQuestion['QuestionContent'],
                'QuestionImage' => $request->input('QuestionImage'),
                'QuestionType' => $request->input('QuestionType')
            ]
        );

        return response()->json([
            'message' => 'Da hoan tat cap nhat cau hoi',
        ], 200);
    }

    // patch: update dap an
    public function updateAnswer(Request $request, $answerID) {
        $getAnswer = Answer::where('AnswerID', $answerID)->first();
        if (!$getAnswer) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac ban chua tao dap an nao',
            ], 404);
        }

        $validate = $request->validate([
            'QuestionContent' => 'required',
            'IsCorrect' => 'required|boolean',
        ]);

        $getAnswer->update(
            [
                'AnswerContent' => $validate['AnswerContent'],
                'AnswerImage' => $request->input('AnswerImage'),
                'IsCorrect' => $validate['IsCorrect'],
            ]
        );

        return response()->json([
            'message' => 'Da hoan tat cap nhat dap an',
        ], 200);
    }
}
