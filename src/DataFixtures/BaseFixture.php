<?php

namespace App\DataFixtures;

use App\DataFixtures\Helper\ResourceToMediaHelper;
use App\Entity\SonataMediaMedia;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Abstract class BaseFixture.
 *
 *
 * @author Anton Prokhorov <vziks@live.ru>
 */
abstract class BaseFixture extends Fixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    use ContainerAwareTrait;

    private ObjectManager $manager;

    protected Generator $faker;

    private array $referencesIndex = [];

    /**
     * @param ObjectManager $manager
     *
     * @return mixed
     */
    abstract protected function loadData(ObjectManager $manager);

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create('ru_RU');

        $this->loadData($manager);
    }

    /**
     * Create many objects at once:.
     *
     *      $this->createMany(10, function(int $i) {
     *          $user = new User();
     *          $user->setFirstName('Vziks');
     *
     *           return $user;
     *      });
     *
     * @param int      $count
     * @param string   $groupName tag these created objects with this group name,
     *                            and use this later with getRandomReference(s)
     *                            to fetch only from this specific group
     * @param callable $factory
     */
    protected function createMany(int $count, string $groupName, callable $factory): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $entity = $factory($i);

            if (null === $entity) {
                throw new \LogicException(
                    'Did you forget to return the entity object from your callback to BaseFixture::createMany()?'
                );
            }

            $this->manager->persist($entity);

            $this->addReference(sprintf('%s_%d', $groupName, $i), $entity);
        }
    }

    /**
     * @param string $groupName
     *
     * @return object
     */
    protected function getRandomReference(string $groupName): object
    {
        if (!isset($this->referencesIndex[$groupName])) {
            $this->referencesIndex[$groupName] = [];

            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (0 === strpos($key, $groupName . '_')) {
                    $this->referencesIndex[$groupName][] = $key;
                }
            }
        }

        if (empty($this->referencesIndex[$groupName])) {
            throw new \InvalidArgumentException(
                sprintf('Did not find any references saved with the group name "%s"', $groupName)
            );
        }

        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$groupName]);

        return $this->getReference($randomReferenceKey);
    }

    /**
     * @param string $className
     * @param int    $count
     *
     * @return array
     */
    protected function getRandomReferences(string $className, int $count): array
    {
        $references = [];
        while (count($references) < $count) {
            $references[] = $this->getRandomReference($className);
        }

        return $references;
    }

    /**
     * @param $folderName
     * @param $arrayImages
     *
     * @return SonataMediaMedia
     */
    protected function fakeUploadImage($folderName, $arrayImages, $context): SonataMediaMedia
    {
        return ResourceToMediaHelper::fromFile(
            __DIR__ . '/images/' . $folderName . '/' . $this->faker->randomElement($arrayImages),
            $this->mediaManager,
            $context
        );
    }


    /**
     * @param $class
     *
     * @return string
     */
    protected function getShortClassName($class): string
    {
        return (new \ReflectionClass($class))->getShortName();
    }
}
