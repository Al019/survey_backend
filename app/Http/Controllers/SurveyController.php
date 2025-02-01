<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
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
        $surveys = Survey::latest()->get();

        return response()->json($surveys);
    }
}
