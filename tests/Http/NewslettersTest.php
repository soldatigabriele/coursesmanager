<?php

namespace Tests\Feature;

use App\Course;
use App\Region;
use Faker\Factory;
use App\Newsletter;
use Tests\TestCase;
use App\Partecipant;
use App\ApplicationLog;
use App\Jobs\TelegramAlert;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewslettersTest extends TestCase
{
    use RefreshDatabase;

    public function test_newsletter_is_created_redirected_and_telegram_queued(){
        Queue::fake();
        $n = factory(Newsletter::class)->make();
        $res = $this->post(route('newsletter-store'), $n->toArray());
        Queue::assertPushed(TelegramAlert::class, 1);
        $newsletter = Newsletter::where('email', $n->email)->first();
        $this->assertInstanceOf(Newsletter::class, $newsletter);
        $this->assertContains($newsletter->slug, $res->getContent());
    }

    public function test_newsletter_show(){
        $news = factory(Newsletter::class, 10)->create();
        foreach($news as $n){
            $res = $this->get(route('newsletter-show', $n->slug));
            $this->assertContains($n->email, $res->getContent());
            $this->assertContains(htmlspecialchars($n->name, ENT_QUOTES), $res->getContent());
            $this->assertContains(htmlspecialchars($n->surname, ENT_QUOTES), $res->getContent());
        }
    }
}
