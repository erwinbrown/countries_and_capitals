<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class MainController extends Controller
{
    private $appdata;

    public function __construct()
    {
        $this->appdata = require(app_path('app_data.php'));
    }

    public function startGame(): View
    {
       return view('home');

    }

    public function prepareGame(Request $res)
    {
        $res->validate(
            [
              'total_questions' => 'required|integer|min:3|max:30'
            ],
            [

                'total_questions.required' => 'Cantidad es requerida',
                'total_questions.integer' => 'Es tipo númerico entero',
                'total_questions.min' => 'Minimo son 3 preguntas',
                'total_questions.max' => 'Maximo son 30 preguntas',

            ]

        );

        $total_questions = intval($res->input('total_questions'));

        $quiz = $this->prepareQuiz($total_questions);

        session()->put([

            'quiz' => $quiz,
            'total_questions' => $total_questions,
            'current_questions'=> 0,
            'correct_answers' => 0,
            'wrong_answers' => 0  

        ]);


        return redirect()->route('game');


    }


    private function prepareQuiz($totalquest)
    {

          $questions = [];
          $total_country = count($this->appdata);

        //  echo  $total_country;

        // index
        $indexe = range(0, $total_country -1);
        shuffle($indexe);
        $indexe = array_slice($indexe, 0, $totalquest);
       
        $cuestion_number = 1;
        foreach($indexe as $index)
        {

            $question['cuestion_number'] = $cuestion_number++;
            $question['country'] = $this->appdata[$index]['country'];
            $question['correct_answer'] = $this->appdata[$index]['capital'];


            $other_capitals = array_column($this->appdata, 'capital');
            $other_capitals = array_diff( $other_capitals, [$question['correct_answer']]);

            shuffle($other_capitals);

            $question['wrong_answers'] =  array_slice($other_capitals, 0, 3);

            $question['correct'] = null;

            $questions[] = $question;
        }

      // echo var_dump($indexe);
       return $questions;

    }


    public function game(): View
    {   

          $quiz = session('quiz');

         
         
          $total_questions = session('total_questions');
          $current_questions =  session('current_questions');

       
         
          $answers = $quiz[$current_questions]['wrong_answers'];
          $answers[] = $quiz[$current_questions]['correct_answer'];
        
        
          
          shuffle($answers);


          return view('game')->with([
                'country' => $quiz[$current_questions]['country'],
                'totalQuestions' => $total_questions,
                'currentQuestions' => $current_questions,
                'answers' => $answers,
          ]);
    }


    public function answer($enc_answer) 
    {
       

        try {
            
          $answer = Crypt::decryptString($enc_answer);

         } catch (\Exception $e) {

            return redirect()->route('game');

         }
         $quiz = session('quiz');
         $current_questions =  session('current_questions');
         $correct_answer = $quiz[$current_questions]['correct_answer'];

         $correct_answers = session('correct_answers');
         $wrong_answers = session('wrong_answers');

         if($answer == $correct_answer)
         {
            $correct_answers ++;
            $quiz[$current_questions]['correct'] = true;
         } else {

            $wrong_answers ++;
            $quiz[$current_questions]['correct'] = false;
         }

         session()->put([

            'quiz' => $quiz,
            'correct_answers' => $correct_answers,
            'wrong_answers' => $wrong_answers, 

        ]);
        $data = [
            'country' => $quiz[$current_questions]['country'],
            'correct_answer' =>  $correct_answer,
            'choice_answer' => $answer,
            'currentQuestions' =>  $current_questions,
            'totalQuestions' => session('total_questions'),
        
        ];


        return view('answer_result')->with($data);

    }

    public function nextQuestion()
    {
        $current_questions =  session('current_questions');
        $total_questions = session('total_questions') - 1;

    
       
        if($current_questions < $total_questions)
        {

            $current_questions ++;
            session()->put('current_questions', $current_questions);
            return redirect()->route('game');

        } else {

            return redirect()->route('show_results');

        }
    }

    public function showResults(): View
    {

      /* echo 'Mostrar los resultados';
       dd(session()->all());*/

         return view('final_results')->with([

             'correct_answers' => session('correct_answers'),
             'wrong_answers' => session('wrong_answers'),
             'total_questions' => session('total_questions'),
             'percentage' => round(session('correct_answers') / session('total_questions') * 100, 2),
         ]);

    }
}
