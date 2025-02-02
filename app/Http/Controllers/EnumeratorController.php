<?php

namespace App\Http\Controllers;

use App\Mail\PasswordMail;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Response;
use App\Models\Survey;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Str;

class EnumeratorController extends Controller
{
    public function addEnumerator(Request $request)
    {
        $request->validate([
            'last_name' => ['required'],
            'first_name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
        ]);

        // $password = Str::random(8);
        $password = "password";

        User::create([
            "last_name" => $request->last_name,
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "email" => $request->email,
            'password' => Hash::make($password),
            "role" => "enumerator",
        ]);

        // Mail::to($request->email)->send(new PasswordMail($password));
    }

    public function getEnumerator(Request $request)
    {
        $enumerators = User::where("role", "enumerator")
            ->get();

        return response()->json($enumerators);
    }

    public function getSurvey()
    {
        $surveys = Survey::latest()->get();

        return response()->json($surveys);
    }

    public function getSurveyQuestionnaire(Request $request)
    {
        $survey = Survey::where('uuid', $request->uuid)
            ->with('question.option')
            ->first();

        return response()->json($survey);
    }
}
