<?php

namespace Database\Seeders;

use App\Models\Email;
use App\Models\Phone;
use App\models\SocialMedia;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SocialMedia::create(['type' => 'Facebook','link' => 'https://www.facebook.com/' ]);
        SocialMedia::create(['type' => 'YouTube','link' => 'https://www.youtube.com/' ]);
        SocialMedia::create(['type' => 'Instagram','link' => 'https://www.instagram.com/' ]);
        SocialMedia::create(['type' => 'Twitter','link' => 'https://www.Twitter.com/' ]);
        SocialMedia::create(['type' => 'LinkedIn','link' => 'https://www.linkedin.com/' ]);
        SocialMedia::create(['type' => 'GitHub','link' => 'https://www.gitHub.com/' ]);
        SocialMedia::create(['type' => 'Google','link' => 'https://www.google.com/' ]);
        SocialMedia::create(['type' => 'G-Mail','link' => 'https://www.gmail.com/' ]);
        SocialMedia::create(['type' => 'Stack Overflow','link' => 'https://www.stackoverflow.com/' ]);
        SocialMedia::create(['type' => 'Reddit','link' => 'https://www.reddit.com/' ]);
        SocialMedia::create(['type' => 'Vkontakte','link' => 'https://www.vkontakte.com/' ]);
        SocialMedia::create(['type' => 'Slack','link' => 'https://www.slack.com/' ]);
        SocialMedia::create(['type' => 'Dribbble','link' => 'https://www.dribbble.com/' ]);


        Email::create(['type' => 'E-Mail', 'email' => 'email@email_11.com' ]);
        Email::create(['type' => 'HR', 'email' => 'email@hr_11.com' ]);
        Email::create(['type' => 'E-Mail', 'email' => 'email@email_22.com' ]);
        Email::create(['type' => 'HR', 'email' => 'email@hr_22.com' ]);


        Phone::create(['type' => 'WhatsApp', 'number' => '123456789' ]);
        Phone::create(['type' => 'Phone', 'number' => '1234512345' ]);
        Phone::create(['type' => 'WhatsApp', 'number' => '987654321' ]);
        Phone::create(['type' => 'Phone', 'number' => '5432154321' ]);

    }
}
