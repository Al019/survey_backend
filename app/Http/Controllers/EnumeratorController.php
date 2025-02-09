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
    public function getSurvey()
    {
        $user_id = auth()->user()->id;

        $surveys = Survey::whereHas('survey_assignment', function ($query) use ($user_id) {
            $query->where('enumerator_id', $user_id);
        })
            ->withCount('response')
            ->latest()
            ->get();

        return response()->json($surveys);
    }

    public function submitSurvey(Request $request)
    {
        $user_id = auth()->user()->id;

        $survey = Survey::where('uuid', $request->uuid)
            ->first();

        $surveyLimit = $survey->limit !== null ? (int) $survey->limit : null;

        $responseCount = Response::where('survey_id', $survey->id)->count();

        if ($surveyLimit !== null && $responseCount === $surveyLimit) {
            return response()->noContent();
        }

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

    public function getSurveyResponse(Request $request)
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
