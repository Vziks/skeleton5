<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Sonata\MediaBundle\Entity\MediaManager;

class ArticleFixture extends BaseFixture
{
    protected MediaManager $mediaManager;
    private array $arrayImages = [
        '1.jpeg',
        '2.jpeg',
        '3.jpeg',
    ];

    public function __construct(MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 10;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(100, 'main_news', function () {
            $news = new Article();

            $news
                ->setName($this->faker->realText(50))
                ->setDescription($this->faker->realText(70))
                ->setDatePublication($this->faker->dateTimeBetween('-21 days', '-1 days'))
                ->setText($this->faker->realText(2500))
                ->setImage($this->fakeUploadImage($this->getShortClassName($news), $this->arrayImages, 'default'))
            ;

            return $news;
        });

        $manager->flush();
    }
}
