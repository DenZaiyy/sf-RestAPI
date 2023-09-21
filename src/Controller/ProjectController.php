<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ProjectController extends AbstractController
{
	public function __construct(private EntityManagerInterface $em) {}

	#[Route('/', name: 'index')]
	public function index(): Response
	{
		$projets = json_decode($this->getAllProjects()->getContent());
		$project2 = json_decode($this->show(2)->getContent());

		$lastProject = $this->em->getRepository(Project::class)->findOneBy([], ['id' => 'desc']); // Get last project

		return $this->render('project/index.html.twig', [
			'projects' => $projets,
			'project2' => $project2,
			'lastProject' => $lastProject->getId(),
		]);
	}

	// Function to get all products using GET method
    #[Route('/projects', name: 'project_all', methods: ['GET'])]
    public function getAllProjects(): JsonResponse
    {
		$products = $this->em->getRepository(Project::class)->findAll();

		$data = [];

		foreach ($products as $product) {
			$data[] = [
				'id' => $product->getId(),
				'name' => $product->getName(),
				'description' => $product->getDescription(),
			];
		}
        return $this->json($data);
    }

	// Function to create a new product using POST method
	#[Route('/project', name: 'project_create', methods: ['POST'])]
	public function create(Request $request): JsonResponse
	{
		$project = new Project();
		$project->setName($request->get('name'));
		$project->setDescription($request->get('description'));

		$this->em->persist($project);
		$this->em->flush();

		$data = [
			'id' => $project->getId(),
			'name' => $project->getName(),
			'description' => $project->getDescription(),
		];

		return $this->json($data);
	}

	// Function to get a single product using GET method
	#[Route('/project/{id}', name: 'project_show', methods:['GET'] )]
	public function show(int $id): JsonResponse
	{
		$project = $this->em->getRepository(Project::class)->find($id);

		if (!$project) {
			return $this->json('No project found for id ' . $id, 404);
		}

		$data =  [
			'id' => $project->getId(),
			'name' => $project->getName(),
			'description' => $project->getDescription(),
		];

		return $this->json($data);
	}

	// Function to update a product using PUT/PATCH method
	#[Route('/project/{id}', name: 'project_update', methods:['put', 'patch'] )]
	public function update(Request $request, int $id): JsonResponse
	{
		$project = $this->em->getRepository(Project::class)->find($id);

		if (!$project) {
			return $this->json('No project found for id' . $id, 404);
		}

		$project->setName($request->get('name'));
		$project->setDescription($request->get('description'));
		$this->em->flush();

		$data =  [
			'id' => $project->getId(),
			'name' => $project->getName(),
			'description' => $project->getDescription(),
		];

		return $this->json($data);
	}

	// Function to delete a product using DELETE method
	#[Route('/project/{id}', name: 'project_delete', methods:['delete'] )]
	public function delete(int $id): JsonResponse
	{
		$project = $this->em->getRepository(Project::class)->find($id);

		if (!$project) {
			return $this->json('No project found for id' . $id, 404);
		}

		$this->em->remove($project);
		$this->em->flush();

		return $this->json('Deleted a project successfully with id ' . $id);
	}
}
