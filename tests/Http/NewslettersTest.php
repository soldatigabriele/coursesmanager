<?php

namespace Tests\Feature;

use App\Newsletter;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewslettersTest extends TestCase
{
    use RefreshDatabase;

    public function test_newsletter_is_created_redirected_and_telegram_queued()
    {
        // Queue::fake();
        $n = factory(Newsletter::class)->make();
        $res = $this->post(route('newsletter.store'), $n->toArray());
        // Queue::assertPushed(TelegramAlert::class, 1);
        $newsletter = Newsletter::where('email', $n->email)->first();
        $this->assertInstanceOf(Newsletter::class, $newsletter);
        $this->assertContains($newsletter->slug, $res->getContent());
    }

    public function test_newsletter_show()
    {
        $news = factory(Newsletter::class, 10)->create();
        foreach ($news as $n) {
            $res = $this->get(route('newsletter.show', $n->slug));
            $this->assertContains($n->email, $res->getContent());
            $this->assertContains(htmlspecialchars($n->name, ENT_QUOTES), $res->getContent());
            $this->assertContains(htmlspecialchars($n->surname, ENT_QUOTES), $res->getContent());
        }
    }

    public function test_newsletter_index()
    {
        $n = factory(Newsletter::class)->create(['name' => 'gabriele', 'surname' => 'soldati', 'email' => 'solDati@tEst.com']);
        $user = factory('App\User')->create();
        $res = $this->actingAs($user)->get(route('newsletter.index', $n->slug));
        $this->assertContains('soldati@test.com', $res->getContent());
        $this->assertContains('Gabriele', $res->getContent());
        $this->assertContains('Soldati', $res->getContent());
    }
}
