<?php
/**
 * ToDoList fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\ToDoList;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ToDoListFixtures.
 */
class ToDoListFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof \Doctrine\Persistence\ObjectManager || !$this->faker instanceof \Faker\Generator) {
            return;
        }

        $this->createMany(100, 'todolists', function (int $i) {
            $todolist = new ToDoList();
            $todolist->setTitle($this->faker->sentence);
            $todolist->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $todolist->setDeadline(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-10 days', '10 days')
                )
            );
            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $todolist->setCategory($category);

            return $todolist;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
