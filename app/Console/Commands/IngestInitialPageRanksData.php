<?php

namespace App\Console\Commands;

use App\Models\PageRank;
use Illuminate\Console\Command;

class IngestInitialPageRanksData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ingest-initial-page-ranks-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ingests initial page ranks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $topSitesJson = json_decode(file_get_contents(storage_path('top-sites.json')));

        foreach ($topSitesJson as $site) {
            $pageRank = new PageRank();
            $pageRank->rank = $site->rank;
            $pageRank->root_domain = $site->rootDomain;
            $pageRank->linking_root_domains = $site->linkingRootDomains;
            $pageRank->domain_authority = $site->domainAuthority;
            $pageRank->save();
        }

        echo "Done";
    }
}
