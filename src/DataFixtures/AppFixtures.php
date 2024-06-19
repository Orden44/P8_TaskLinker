<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;

class AppFixtures extends Fixture
{
    /** @var Project[]  */
    private array $projects = [];

    public function load(ObjectManager $manager): void
    {
        $this->loadEmployees($manager, 11);
        $this->loadProjects($manager, 11);
        
        // create 3 status!
        $label = ['To Do', 'Doing', 'Done'];
        for ($i = 0; $i < 3; $i++) {
            $status = new Status();
            $status->setLabel($label[$i]);

            $manager->persist($status);
        }

        foreach ($this->projects as $project) {
            $this->loadTasks($manager, $project, $status);
        }

        $manager->flush();
    }

    // create 10 employees!
    public function loadEmployees(ObjectManager $manager, int $count): void
    {
        $faker = (new \Faker\Factory())::create('fr_FR');

        $contract = ['CDI', 'CDD', 'Freelance', 'Alternance', 'Stagiaire'];
        for ($i = 1; $i < $count; $i++) {
            $employee = (new Employee())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->freeEmail())
                ->setArrivalDate(new \DateTimeImmutable('now +' . $i . ' day'))
                ->setStatus($contract[mt_rand(0,4)])
                ->setActive(1)
                ->setJob(0);
            $employee->setPassword(hash('sha256', $employee->getLastName()));

            $manager->persist($employee);
        }
    }

    // create 10 projects!
    public function loadProjects(ObjectManager $manager, int $count): void
    {
        $faker = (new \Faker\Factory())::create('fr_FR');

        for ($i = 1; $i < $count; $i++) {
            $project = new Project();
            $project->setName($faker->sentence())
                ->setStartDate(new \DateTimeImmutable('now -' . $i . ' day'))
                ->setDeadline(new \DateTime('now +' . $i . ' day'))
                ->setArchive($i % 2 === 0);

            $manager->persist($project);
        }
    }
    
    // create 5 tasks!
    public function loadTasks(ObjectManager $manager, Project $project, Status $status): void
    {
        $faker = (new \Faker\Factory())::create('fr_FR');

        for ($i = 1; $i < 6; $i++) {
            $task = (new Task())
                ->setTitle($faker->sentence())
                ->setDescription($faker->paragraph())
                ->setDeadline(new \DateTime('now +' . $i . ' day'))
                ->setProject($this->getReference($faker->randomElement($project)))
                ->setStatus($this->getReference($faker->randomElement($status)));

            $manager->persist($task);
        }
    }
}
