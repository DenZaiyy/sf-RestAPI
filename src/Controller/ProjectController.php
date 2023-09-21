<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ProjectController extends AbstractController
{
	public function __construct(private EntityManagerInterface $em) {}

    #[Route('/projects', name: 'project_index', methods: ['GET'])]
    public function index(ProjectRepository $repository): JsonResponse
    {
		$products = $repository->findAll();

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

	#[Route('/project/{id}', name: 'project_update', methods:['put', 'patch'] )]
	public function update(Request $request, int $id): JsonResponse
	{
		$project = $this->em->getRepository(Project::class)->find($id);

		if (!$project) {
			return $this->json('No project found for id' . $id, 404);
		}

		$project->setName($request->request->get('name'));
		$project->setDescription($request->request->get('description'));
		$this->em->flush();

		$data =  [
			'id' => $project->getId(),
			'name' => $project->getName(),
			'description' => $project->getDescription(),
		];

		return $this->json($data);
	}

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
