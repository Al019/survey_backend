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
            "user_id" => $user_id,
            "uuid" => $request->uuid,
            "title" => "Untitled form",
            "description" => "Form description",
        ]);

        $question = Question::create([
            "survey_id" => $survey->id,
            "text" => "Question",
            "type" => "multiple_choice",
            "required" => 0,
        ]);

        Option::create([
            "question_id" => $question->id,
            "text" => "Option 1",
        ]);

        return response()->json([
            "uuid" => $survey->uuid,
        ]);
    }

    public function getForm(Request $request)
    {
        $form = Survey::with('question.option')
            ->where('uuid', $request->uuid)
            ->first();

        return response()->json($form);
    }

    public function editForm(Request $request)
    {
        $user_id = auth()->user()->id;

        $survey = Survey::where('user_id', $user_id)
            ->where('uuid', $request->uuid)
            ->first();

        $survey->update([
            'title' => $request->title ?? $survey->title,
            'description' => $request->description ?? $survey->description,
        ]);

        foreach ($request['question'] as $questionData) {
            $question = Question::updateOrCreate(
                ['id' => $questionData['id'], 'survey_id' => $survey->id],
                [
                    'text' => $questionData['text'] ?? 'Question',
                    'type' => $questionData['type'] ?? 'multiple_choice',
                    'required' => $questionData['required'] ?? 0,
                ]
            );

            foreach ($questionData['option'] as $optionData) {
                Option::updateOrCreate(
                    ['id' => $optionData['id'], 'question_id' => $question->id],
                    ['text' => $optionData['text'] ?? 'Option']
                );
            }
        }
    }
}
