<?php

namespace App\Http\Controllers;

use App\Mail\PasswordMail;
use App\Models\Answer;
use App\Models\AnswerOption;
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
            'gender' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
        ]);

        // $password = Str::random(8);
        $password = "password";

        User::create([
            "last_name" => $request->last_name,
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "gender" => $request->gender,
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

    public function updateEnumeratorStatus(Request $request)
    {
        User::where('id', $request->enumerator_id)
            ->where('role', 'enumerator')
            ->update([
                'status' => $request->status
            ]);
    }

    public function getEnumeratorInfo(Request $request)
    {
        $information = User::where('id', $request->enumerator_id)
            ->where('role', 'enumerator')
            ->first();

        return response()->json($information);
    }

    public function submitSurvey(Request $request)
    {
        $user_id = auth()->user()->id;

        $survey = Survey::where('uuid', $request->uuid)
            ->first();

        $reponse = Response::create([
            'survey_id' => $survey->id,
            'enumerator_id' => $user_id,
        ]);

        foreach ($request['answer'] as $answerData) {
            $answer = Answer::create([
                'response_id' => $reponse->id,
                'question_id' => $answerData['questionId'],
                'text' => is_array($answerData['text']) ? implode(', ', $answerData['text']) : $answerData['text'],
            ]);

            foreach ($answerData['option'] as $answerOptionData) {
                AnswerOption::create([
                    'answer_id' => $answer->id,
                    'option_id' => $answerOptionData['optionId'],
                ]);
            }
        }
    }

    public function getResponse(Request $request)
    {
        $user_id = auth()->user()->id;

        $survey = Survey::where('uuid', $request->uuid)
            ->first();

        $response = Response::where('survey_id', $survey->id)
            ->where('enumerator_id', $user_id)
            ->with('answer.answer_option')
            ->get();

        return response()->json($response);
    }
}
