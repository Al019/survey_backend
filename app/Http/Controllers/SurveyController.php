<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\Response;
use App\Models\Survey;
use App\Models\Type;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
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

    public function getResponse(Request $request)
    {
        $survey = Survey::where('uuid', $request->uuid)
            ->first();

        $response = Response::where('survey_id', $survey->id)
            ->with('answer.answer_option')
            ->get();

        return response()->json($response);
    }
}
