<?php

namespace App\Jobs;

use App\Http\Crawler\Crawler;
use App\Models\Link;
use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Site $site)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $crawler = Crawler::crawler($this->site->url);

        $html = '<div>';

        if (count($crawler['headers']) > 0) {
            foreach ($crawler['headers'] as $k => $v) {
                $html .= '<p>' . $k . ': ' . implode(', ', $v) . '</p>';
            }
        }
        $html .= '</div>';

        $this->site->technologies = $html;
        $this->site->last_check = date('Y-m-d H:i:s');

        $this->site->update();

        if (count($crawler['links']) > 0) {

            foreach ($crawler['links'] as $k => $v) {

                $checkLink = Link::where('url', $v['url'])->where('site_id', $this->site->id)->first();

                if ($checkLink) {
                    $checkLink->last_check = date('Y-m-d H:i:s');
                    $checkLink->update();
                } else if (filter_var($v['url'], FILTER_VALIDATE_URL)) {
                    $newLink = Link::create([
                        'page' => $v['page'],
                        'url' => $v['url'],
                        'title' => $v['title'],
                        'status' => 'Aguardando',
                        'site_id' => $this->site->id,
                        'last_check' => date('Y-m-d H:i:s')
                    ]);

                    $newLink->save();

                    if ($this->extractDomain($v['url']) == $this->extractDomain($this->site->url)) {
                        LinkJob::dispatch($newLink, $this->site);
                    } else {
                        $newLink->status = 'Finalizado';
                        $newLink->update();
                    }
                }
            }
        }

        $this->site->last_check = date('Y-m-d H:i:s');
        $this->site->status = 'Finalizado';
        $this->site->update();
    }

    private function extractDomain($url)
    {
        return str_replace('www.', '', parse_url($url, PHP_URL_HOST));
    }
}
