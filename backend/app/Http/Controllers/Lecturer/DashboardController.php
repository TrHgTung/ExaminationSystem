<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('isLecturer')->only(['getQuestion', 'getAnswer', 'getExamList', 'getStudent']); // chi cho phep admin thuc hien nhung ham nay
    }

    // get
    public function getExamList() { // hien thi danh sach de thi - de thi khong hien thi cau hoi va dap an tuong ung de thi do nham bao mat
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

    // get
    public function getQuestion() { // hien thi danh sach cau hoi - nhung chi thuoc ve giang vien da tao
        $getAuthor = auth()->user()->Email;
        $getAllQuestions = Question::where('Author', $getAuthor)->get();
        if (!$getAllQuestions) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac ban chua tao cau hoi nao',
            ], 404);
        }

        return response()->json([
            'questions' => $getAllQuestions,
        ], 200);
    }

    // get
    public function getAnswer($questionId) { // hien thi danh sach dap an - nhung chi thuoc ve giang vien da tao
        $getAuthor = auth()->user()->Email;
        $getAllAnswers = Answer::where('Author', $getAuthor)->where('QuestionID', $questionId)->get();
        if (!$getAllAnswers) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac ban chua tao dap an nao',
            ], 404);
        }

        return response()->json([
            'answers' => $getAllAnswers,
        ], 200);
    }

    // get: view danh sach sinh vien - co ca diem
    public function getStudent() { // lay danh sach sinh vien
        $getAllStudents = Student::all();
        if (!$getAllStudents) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac ban chua tao dap an nao',
            ], 404);
        }

        return response()->json([
            'students' => $getAllStudents,
        ], 200);
    }
}
