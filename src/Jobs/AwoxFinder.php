<?php

namespace Vo1\Seat\AwoxFinder\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Seat\Eveapi\Jobs\AbstractJob;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Models\User;
use Vo1\Seat\AwoxFinder\Models\Awoxer;

class AwoxFinder extends AbstractJob
{
    const SETTINGS_DC_URLS = 'awox.settings.discord_urls';
    /**
     * @var string[]
     */
    protected $tags = ['contacts'];
    /**
     * @var Awoxer
     */
    protected $row;
    /**
     * @var int
     */
    protected $standing;
    /**
     * @param Awoxer $this->row
     * @param $standing
     */
    public function __construct(Awoxer $row, $standing)
    {
        $this->row = $row;
        $this->standing = $standing;
    }

    /**
     * Dispatches discord message
     */
    private function dispatchMessage()
    {
        $webhookUrls = setting(self::SETTINGS_DC_URLS, true) ?? [];
        $corporation = CorporationInfo::find($this->row->universe_name->affiliation->corporation_id);
        $alliance = isset($this->row->universe_name->affiliation->corporation_id)
            ? Alliance::find($this->row->universe_name->affiliation->alliance_id)
            : null;
        $allianceText = $alliance
            ? " of alliance **" . $alliance->name . "**"
            : '';
        $client = new Client(['timeout' => 5]);
        foreach ($webhookUrls as $webhookUrl) {
            $descriptionText = $this->row->description ? "Note: " . $this->row->description : '';
            try {
                $username = User::find($this->row->added_by)->main_character->name;
            } catch (\Throwable $e) {
                $username = 'NOT_FOUND';
            }
            $descriptionText .= sprintf("\nReason: **%s**.", $this->row->reason);
            $descriptionText .= sprintf("\nAffiliation: **%s**.", $this->row->affiliation);
            $descriptionText .= sprintf("\nAdded by **%s** at %s.", $username, $this->row->created_at);
            if ($this->row->created_at < $this->row->updated_at) {
                $descriptionText .= sprintf("\nLast update: %s", $this->row->updated_at);
            }
            $client->post(
                $webhookUrl,
                [
                    RequestOptions::JSON => [
                        'content' => sprintf(
                            "An awoxer **%s** is detected in friendly (+%s) corporation **%s**%s",
                            $this->row->universe_name->name,
                            $this->standing,
                            $corporation->name,
                            $allianceText
                        ),

                        'embeds' => [
                            [
                                'title' => "Information",
                                'description' => sprintf(
                                    "https://zkillboard.com/character/%s/\n%s",
                                    $this->row->id,
                                    $descriptionText
                                ),
                                'color' => '7506394',
                            ]
                        ],
                    ]
                ]
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function handle()
    {
        if (carbon($this->row->pinged_at)->add(12, 'hour')->lt(carbon())) {
            $this->dispatchMessage();
            $this->row->pinged_at = carbon();
            $this->row->save();
        }
    }
}
