<?php

namespace App\Http\Controllers;

use App\Exceptions\MasterDataException;
use App\Services\MasterDataService;
use Illuminate\Http\JsonResponse;
use Throwable;

class MasterDataController extends Controller
{
    /**
     * Master Data Service Instance.
     */
    protected MasterDataService $masterDataService;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        MasterDataService $masterDataService
    ) {
        $this->masterDataService = $masterDataService;
    }

    /**
     * Success Response.
     */
    protected function success(
        mixed $data,
        string $message = 'Success.'
    ): JsonResponse {

        return response()->json([

            'status'  => true,

            'message' => $message,

            'data'    => $data,

        ]);
    }

    /**
     * Error Response.
     */
    protected function error(
        string $message,
        int $status = 500
    ): JsonResponse {

        return response()->json([

            'status'  => false,

            'message' => $message,

        ], $status);
    }

    /**
     * Execute Controller Action.
     */
    private function execute(
        callable $callback,
        string $errorMessage
    ): JsonResponse {

        try {

            return $this->success(
                $callback()
            );
        } catch (MasterDataException $e) {

            return $this->error(
                $e->getMessage(),
                422
            );
        } catch (Throwable $e) {

            report($e);

            return $this->error(
                $errorMessage
            );
        }
    }

    /**
     * Get Active Organizations.
     */
    public function organizations(): JsonResponse
    {
        return $this->execute(
            fn() => $this->masterDataService->getOrganizations(),
            'Unable to load organizations.'
        );
    }

    /**
     * Get Organization.
     */
    public function organization(
        int $organization
    ): JsonResponse {

        return $this->execute(
            fn() => $this->masterDataService
                ->getOrganization($organization),
            'Unable to load organization.'
        );
    }

    /**
     * Get Active Departments.
     */
    public function departments(): JsonResponse
    {
        return $this->execute(
            fn() => $this->masterDataService->getDepartments(),
            'Unable to load departments.'
        );
    }

    /**
     * Get Department.
     */
    public function department(
        int $department
    ): JsonResponse {

        return $this->execute(
            fn() => $this->masterDataService
                ->getDepartment($department),
            'Unable to load department.'
        );
    }

    /**
     * Get Departments By Organization.
     */
    public function departmentsByOrganization(
        int $organization
    ): JsonResponse {

        return $this->execute(
            fn() => $this->masterDataService
                ->getDepartmentsByOrganization($organization),
            'Unable to load departments.'
        );
    }

    /**
     * Get Active Roles.
     */
    public function roles(): JsonResponse
    {
        return $this->execute(
            fn() => $this->masterDataService->getRoles(),
            'Unable to load roles.'
        );
    }

    /**
     * Get Role.
     */
    public function role(
        int $role
    ): JsonResponse {

        return $this->execute(
            fn() => $this->masterDataService
                ->getRole($role),
            'Unable to load role.'
        );
    }

    /**
     * Get Active Port Categories.
     */
    public function portCategories(): JsonResponse
    {
        return $this->execute(
            fn() => $this->masterDataService->getPortCategories(),
            'Unable to load port categories.'
        );
    }

    /**
     * Get Port Category.
     */
    public function portCategory(
        int $portCategory
    ): JsonResponse {

        return $this->execute(
            fn() => $this->masterDataService
                ->getPortCategory($portCategory),
            'Unable to load port category.'
        );
    }

    /**
     * Get Active States.
     */
    public function states(): JsonResponse
    {
        return $this->execute(
            fn() => $this->masterDataService->getStates(),
            'Unable to load states.'
        );
    }

    /**
     * Get State.
     */
    public function state(
        int $state
    ): JsonResponse {

        return $this->execute(
            fn() => $this->masterDataService
                ->getState($state),
            'Unable to load state.'
        );
    }

    /**
     * Get Active State Boards.
     */
    public function stateBoards(): JsonResponse
    {
        return $this->execute(
            fn() => $this->masterDataService->getStateBoards(),
            'Unable to load state boards.'
        );
    }

    /**
     * Get State Board.
     */
    public function stateBoard(
        int $stateBoard
    ): JsonResponse {

        return $this->execute(
            fn() => $this->masterDataService
                ->getStateBoard($stateBoard),
            'Unable to load state board.'
        );
    }

    /**
     * Get State Boards By State.
     */
    public function stateBoardsByState(
        int $state
    ): JsonResponse {

        return $this->execute(
            fn() => $this->masterDataService
                ->getStateBoardsByState($state),
            'Unable to load state boards.'
        );
    }

    /**
     * Get Active Ports.
     */
    public function ports(): JsonResponse
    {
        return $this->execute(
            fn() => $this->masterDataService->getPorts(),
            'Unable to load ports.'
        );
    }

    /**
     * Get Port.
     */
    public function port(
        int $port
    ): JsonResponse {

        return $this->execute(
            fn() => $this->masterDataService
                ->getPort($port),
            'Unable to load port.'
        );
    }

    /**
     * Get Ports By Port Category.
     */
    public function portsByCategory(
        int $portType
    ): JsonResponse {

        return $this->execute(
            fn() => $this->masterDataService
                ->getPortsByCategory($portType),
            'Unable to load ports.'
        );
    }

    /**
     * Get Ports By State Board.
     */
    public function portsByStateBoard(
        int $stateBoard
    ): JsonResponse {

        return $this->execute(
            fn() => $this->masterDataService
                ->getPortsByStateBoard($stateBoard),
            'Unable to load ports.'
        );
    }

    /**
     * Get Active Reporting Officers.
     */
    public function reportingOfficers(): JsonResponse
    {
        return $this->execute(
            fn() => $this->masterDataService
                ->getReportingOfficers(),
            'Unable to load reporting officers.'
        );
    }
}
