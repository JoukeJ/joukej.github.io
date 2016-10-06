<?php
/**
 * Created by Luuk Holleman
 * Date: 18/06/15
 */

namespace TTC\Frontend\Profile;


use App\TTC\Jobs\Frontend\Profile\CreateProfileJob;
use App\TTC\Jobs\Frontend\Profile\UpdateProfileJob;
use App\TTC\Models\Language;
use App\TTC\Models\Profile;
use Test\TTC\BaseTTCTest;

class JobTest extends BaseTTCTest
{
    /**
     *
     */
    public function testCreateJob()
    {
        $language = factory(Language::class)->create();

        $profile = factory(Profile::class)->make([
            'language_id' => $language->id,
            'geo_country_id' => $this->createCountry()->id
        ]);

        $data = $profile->getAttributes();

        $job = \App::make(CreateProfileJob::class, [$data]);
        $profile = $job->handle();

        $this->assertTrue($profile->exists);
        $this->assertequals($profile->update_flag, 1);
    }

    /**
     *
     */
    public function testUpdateJob()
    {
        $language = factory(Language::class)->create();

        $profile = factory(Profile::class)->create([
            'language_id' => $language->id,
            'geo_country_id' => $this->createCountry()->id
        ]);

        $data = array_merge(['profile_id' => $profile->id], array_except($profile->getAttributes(), ['id']));

        $data['name'] = "I'm in love with the coco.";

        $job = \App::make(UpdateProfileJob::class, [$profile->identifier, $data]);
        $profile = $job->handle();

        $this->assertEquals($data['name'], $profile->name);
        $this->assertEquals($profile->update_flag, 1);
    }
}
