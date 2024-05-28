<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;



class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = (new \Faker\Factory())::create('fr_FR');

        // create 5 employees!
        $contract = ['CDI', 'CDD', 'Freelance', 'Alternance', 'Stagiaire'];
        for ($i = 1; $i < 6; $i++) {
            $employee = new Employee();
            $employee->setFirstName($faker->firstName());
            $employee->setLastName($faker->lastName());
            $employee->setEmail($faker->freeEmail());
            // $employee->setArrivalDate($faker->date('d/m/Y', 'now'));
            $employee->setArrivalDate(new \DateTime());
            $employee->setStatus($contract[mt_rand(0,4)]);
            $employee->setPassword(hash('sha256', $employee->getFirstName()));
            $employee->setActive(1);
            $employee->setJob(0);

            $manager->persist($employee);
        }
        
        // create 5 projects!
        for ($i = 1; $i < 6; $i++) {
            $project = new Project();
            $project->setName($faker->sentence());
            $project->setArchive(0);

            $manager->persist($project);
        }

        // create 3 status!
        $label = ['A faire', 'En cours', 'Terminé'];
        for ($i = 0; $i < 3; $i++) {
            $status = new Status();
            $status->setLabel($label[$i]);

            $manager->persist($status);
        }

        // create 5 tasks!
        // for ($i = 1; $i < 6; $i++) {
        //     $task = new Task();
        //     $task->setTitle($faker->sentence());
        //     $task->setStatus($status->getId(mt_rand(5, 7)));
        //     $task->setProject($project->getId(mt_rand(36, 40)));

        //     $manager->persist($task);
        // }


        $manager->flush();
    }
}
