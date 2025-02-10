<?php

namespace App\Http\Controllers;

use App\Mail\PasswordMail;
use App\Models\Option;
use App\Models\Question;
use App\Models\Response;
use App\Models\Survey;
use App\Models\SurveyAssignment;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Str;

class AdminController extends Controller
{
    public function addEnumerator(Request $request)
    {
        $request->validate([
            'last_name' => ['required'],
            'first_name' => ['required'],
            'gender' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
        ]);

        $password = Str::random(8);

        User::create([
            "last_name" => $request->last_name,
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "gender" => $request->gender,
            "email" => $request->email,
            'password' => Hash::make($password),
            "role" => "enumerator",
        ]);

        Mail::to($request->email)->send(new PasswordMail($password));
    }

    public function getEnumerator(Request $request)
    {
        $enumerators = User::where("role", "enumerator")
            ->get();

        return response()->json($enumerators);
    }

    public function getEnumeratorInformation(Request $request)
    {
        $information = User::where('id', $request->enumerator_id)
            ->where('role', 'enumerator')
            ->first();

        return response()->json($information);
    }

    public function updateEnumeratorStatus(Request $request)
    {
        User::where('id', $request->enumerator_id)
            ->where('role', 'enumerator')
            ->update([
                'status' => $request->status
            ]);
    }

    public function createSurvey(Request $request)
    {
        $user_id = auth()->user()->id;

        $survey = Survey::create([
            "admin_id" => $user_id,
            "uuid" => $request->uuid,
            "title" => $request->survey["title"] ?? "Untitled form",
            "description" => $request->survey["description"],
            "limit" => $request->survey["limit"],
        ]);

        foreach ($request->survey["questions"] as $questionData) {
            $question = Question::create([
                'survey_id' => $survey->id,
                'text' => $questionData['text'],
                'type' => $questionData['type'],
                'required' => $questionData['required'],
            ]);

            foreach ($questionData["options"] as $optionData) {
                Option::create([
                    'question_id' => $question->id,
                    'text' => $optionData['text'],
                ]);
            }
        }
    }

    public function getSurvey()
    {
        $surveys = Survey::withCount('response')
            ->latest()
            ->get();

        return response()->json($surveys);
    }

    public function getSurveyQuestionnaire(Request $request)
    {
        $survey = Survey::where('uuid', $request->uuid)
            ->withCount('response')
            ->with('question.option')
            ->first();

        return response()->json($survey);
    }

    public function getSurveyResponse(Request $request)
    {
        $survey = Survey::where('uuid', $request->uuid)
            ->first();

        $response = Response::where('survey_id', $survey->id)
            ->with('answer.answer_option')
            ->get();

        return response()->json($response);
    }

    public function getAssignEnumerator(Request $request)
    {
        $survey_id = Survey::where('uuid', $request->uuid)
            ->first()->id;

        $enumerators = User::where('role', 'enumerator')
            ->whereDoesntHave('survey_assignment', function ($query) use ($survey_id) {
                $query->where('survey_id', $survey_id);
            })
            ->get();

        return response()->json($enumerators);
    }

    public function getAssignEnumeratorSurvey(Request $request)
    {
        $survey_id = Survey::where('uuid', $request->uuid)
            ->first()->id;

        $enumerators = User::where('role', 'enumerator')
            ->whereHas('survey_assignment', function ($query) use ($survey_id) {
                $query->where('survey_id', $survey_id);
            })
            ->withCount([
                'response' => function ($query) use ($survey_id) {
                    $query->where('survey_id', $survey_id);
                }
            ])
            ->get();

        return response()->json($enumerators);
    }

    public function assignEnumerator(Request $request)
    {
        SurveyAssignment::create([
            'survey_id' => $request->survey_id,
            'enumerator_id' => $request->enumerator_id,
        ]);
    }
}
