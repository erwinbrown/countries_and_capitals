<x-main-layout pagTitle="Countries & Capitals Quiz">
<div class="container">
   
    <x-question :country="$country" :currentQuestion="$currentQuestions" :totalQuestions="$totalQuestions" />

    <div class="row">

       @foreach ($answers as $answer)
           <x-answer :capital="$answer" />
       @endforeach

    </div>
    
</div>

<!-- cancel game -->
<div class="text-center mt-5">
    <a href="{{ route('start_game') }}" class="btn btn-outline-danger mt-3 px-5">CANCELAR JOGO</a>
</div>
</x-main-layout>