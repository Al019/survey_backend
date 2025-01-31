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
            "title" => "Untitled form",
            "description" => "Form description",
        ]);

        return response()->json([
            "uuid" => $survey->uuid,
        ]);
    }

    public function getHeader(Request $request)
    {
        $user_id = auth()->user()->id;

        $header = Survey::where('admin_id', $user_id)
            ->where('uuid', $request->uuid)
            ->first();

        return response()->json($header);
    }

    public function editHeader(Request $request)
    {
        $user_id = auth()->user()->id;

        Survey::where('admin_id', $user_id)
            ->where('uuid', $request->uuid)
            ->update([
                'title' => $request->title ?? 'Untitled form',
                'description' => $request->description ?? 'Form description',
            ]);
    }

    public function getQuestion(Request $request)
    {
        $user_id = auth()->user()->id;

        $survey = Survey::where('admin_id', $user_id)
            ->where('uuid', $request->uuid)
            ->first();

        $questions = Question::with('option')
            ->where('survey_id', $survey->id)
            ->get();

        return response()->json($questions);
    }

    public function addQuestion(Request $request)
    {
        $user_id = auth()->user()->id;

        $survey = Survey::where('admin_id', $user_id)
            ->where('uuid', $request->uuid)
            ->first();

        $question = Question::create([
            'survey_id' => $survey->id,
            'text' => $request->text,
            'type' => $request->type,
            'required' => $request->required,
        ]);

        foreach ($request->option as $option) {
            Option::create([
                'question_id' => $question->id,
                'text' => $option['text'],
            ]);
        }
    }

    public function editQuestion(Request $request)
    {
        $user_id = auth()->user()->id;

        $survey = Survey::where('admin_id', $user_id)
            ->where('uuid', $request->uuid)
            ->first();

        Question::where('id', $request->question['id'])
            ->where('survey_id', $survey->id)
            ->update([
                'text' => $request->question['text'] ?? 'Untitled question',
                'type' => $request->question['type'],
                'required' => $request->question['required'],
            ]);

        if ($request->question['type'] === 'text') {
            Option::where('question_id', $request->question['id'])->delete();

            Option::create([
                'question_id' => $request->question['id'],
                'text' => 'Text',
            ]);
        } elseif (in_array($request->question['type'], ['multiple_choice', 'checkboxes', 'dropdown'])) {
            Option::where('question_id', $request->question['id'])->delete();

            Option::create([
                'question_id' => $request->question['id'],
                'text' => 'Question option',
            ]);
        }
    }

    public function deleteQuestion(Request $request)
    {
        Question::where('id', $request->id)
            ->where('survey_id', $request->survey_id)
            ->delete();
    }

    public function addOption(Request $request)
    {
        Option::create([
            'question_id' => $request->question_id,
            'text' => $request->option['text'],
        ]);
    }

    public function editOption(Request $request)
    {
        Option::where('id', $request->id)
            ->where('question_id', $request->question_id)
            ->update([
                'text' => $request->text
            ]);
    }

    public function deleteOption(Request $request)
    {
        Option::where('id', $request->id)
            ->where('question_id', $request->question_id)
            ->delete();
    }

    public function getSurvey()
    {
        $surveys = Survey::get();

        return response()->json($surveys);
    }

    public function publishSurvey(Request $request)
    {
        Survey::where('uuid', $request->uuid)
            ->update([
                'status' => 'publish',
                'published_at' => now()
            ]);
    }
}
