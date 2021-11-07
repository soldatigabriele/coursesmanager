<?php

namespace Tests\Http;

use App\Course;
use App\Question;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a user can delete a partecipant from a course
     *
     * @return void
     */
    public function test_store()
    {
        $course = factory(Course::class)->create();
        $feedback = [
            'feedback-1' => 'first feedback data',
            'feedback-2' => 'second feedback data',
        ];
        $questions = [
            'question-1' => 'first question data',
            'question-2' => 'second question data',
            'question-3' => 'third question data',
        ];
        $this->post(route('questions-store'), [
            'courseId' => $course->id,
            'name' => 'john',
            'surname' => 'doe',
        ] + $feedback + $questions);

        $question = Question::first();
        $this->assertEquals($feedback, $question->feedback);
        $this->assertEquals($questions, $question->questions);
        $this->assertEquals('John Doe', $question->name);
        $this->assertSame($question->id, $course->questions()->first()->id);
    }
}
