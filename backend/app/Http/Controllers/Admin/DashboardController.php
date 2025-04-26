<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('isLecturer')->only(['getQuestion', 'getAnswer', 'getExamList']); // chi cho phep admin thuc hien nhung ham nay
    }

    public function getExamList() { // lay danh sach de thi
        $getAuthor = auth()->user()->Email;
        $getAllExams = Exam::where('Author', $getAuthor)->get();
        if (!$getAllExams) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac chua co de thi nao (Lien he Admin)',
            ], 404);
        }

        return response()->json([
            'examList' => $getAllExams,
        ], 200);
    }

    public function getQuestion() { // lay danh sach cau hoi
        // $getAuthor = auth()->user()->Email;
        $getAllQuestions = Question::all();
        if (!$getAllQuestions) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac ban chua tao cau hoi nao',
            ], 404);
        }

        return response()->json([
            'questions' => $getAllQuestions,
        ], 200);
    }

    public function getAnswer() { // lay danh sach dap an
        // $getAuthor = auth()->user()->Email;
        $getAllAnswers = Answer::all();
        if (!$getAllAnswers) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac ban chua tao dap an nao',
            ], 404);
        }

        return response()->json([
            'answers' => $getAllAnswers,
        ], 200);
    }

    public function getStudentList() { // lay danh sach sinh vien
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

    public function getLecturerList() { // lay danh sach giang vien
        $getAllLecturers = Lecturer::all();
        if (!$getAllLecturers) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac ban chua tao dap an nao',
            ], 404);
        }

        return response()->json([
            'lecturers' => $getAllLecturers,
        ], 200);
    }

    public function removeExam($examID) { // xoa de thi -> xoa luon cac cau hoi lien quan -> xoa luon cac dap an lien quan
        $getExam = Exam::where('ExamID', $examID)->first();
        $relatedQuestions = QuestionExam::where('ExamID', $examID)->get();
        if (!$getExam || !$relatedQuestions) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac ban chua tao dap an nao',
            ], 404);
        }
        $getAllRelatedQuestionIDs = $relatedQuestions->pluck('QuestionID');
        
        $getExam->delete(); // xoa de thi
        Question::whereIn('QuestionID', $getAllRelatedQuestionIDs)->delete(); // xoa luon cac cau hoi lien quan de thi
        Answer::whereIn('QuestionID', $getAllRelatedQuestionIDs)->delete(); // xoa luon cac dap an lien quan cau hoi bi xoa

        return response()->json([
            'message' => 'Da xoa de thi',
        ], 200);
    }

    public function removeLecturer($lecturerID) { // xoa giang vien
        $getLecturer = Lecturer::where('LecturerID', $lecturerID)->first();
        if (!$getLecturer) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac ban chua tao dap an nao',
            ], 404);
        }
        $getLecturer->delete(); // xoa giang vien

        return response()->json([
            'message' => 'Da xoa giang vien',
        ], 200);
    }

    public function removeStudent($studentID) { // xoa sinh vien
        $getStudent = Student::where('StudentID', $studentID)->first();
        if (!$getStudent) {
            return response()->json([
                'message' => 'Loi ket noi du lieu hoac ban chua tao dap an nao',
            ], 404);
        }
        $getStudent->delete(); // xoa sinh vien

        return response()->json([
            'message' => 'Da xoa sinh vien',
        ], 200);
    }

}
