<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Question extends Component
{
     public string $country;
     public string $currentQuestion;
     public string $totalQuestions;

    public function __construct($country, $currentQuestion, $totalQuestions)
    {
        $this->country = $country;
        $this->currentQuestion = $currentQuestion;
        $this->totalQuestions = $totalQuestions;
    }

   
    public function render(): View|Closure|string
    {
        return view('components.question');
    }
}
