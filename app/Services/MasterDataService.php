<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Organization;
use App\Models\Port;
use App\Models\PortCategory;
use App\Models\Role;
use App\Models\State;
use App\Models\StateBoard;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\MasterDataException;
use Illuminate\Support\Collection;

class MasterDataService
{

    /**
     * Execute Master Data Query.
     *
     * Wraps all master data queries with
     * centralized exception handling.
     * Handles:
     * - Model Not Found
     * - Unexpected Exceptions
     *
     * @param Closure $callback
     * @param string $notFoundMessage
     * @param string $errorMessage
     *
     * @return mixed
     *
     * @throws MasterDataException
     */
    private function executeMasterQuery(
        Closure $callback,
        string $notFoundMessage = 'Record not found.',
        string $errorMessage = 'Unable to load data.'
    ): mixed {

        try {

            return $callback();
        } catch (ModelNotFoundException $e) {

            throw new MasterDataException(
                $notFoundMessage
            );
        } catch (\Throwable $e) {

            report($e);

            throw new MasterDataException(

                message: $errorMessage,

                previous: $e

            );
        }
    }

    /**
     * Get Active Organizations.
     */
    public function getOrganizations(): Collection
    {
        return $this->executeMasterQuery(

            callback: fn() => Organization::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('organization_name')
                ->get(),

            errorMessage: 'Unable to load organizations.'

        );
    }

    /**
     * Get Organization By Id.
     */
    public function getOrganization(int $id)
    {
        return $this->executeMasterQuery(

            callback: fn() => Organization::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->findOrFail($id),

            notFoundMessage: 'Organization not found.',

            errorMessage: 'Unable to load organization.'

        );
    }

    /**
     * Get Active Departments.
     */
    public function getDepartments(): Collection
    {
        return $this->executeMasterQuery(

            callback: fn() => Department::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('department_name')
                ->get(),

            errorMessage: 'Unable to load departments.'

        );
    }

    /**
     * Get Department By Id.
     */
    public function getDepartment(int $id)
    {
        return $this->executeMasterQuery(

            callback: fn() => Department::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->findOrFail($id),

            notFoundMessage: 'Department not found.',

            errorMessage: 'Unable to load department.'

        );
    }

    /**
     * Get Departments By Organization.
     */
    public function getDepartmentsByOrganization(
        int $organizationId
    ) {
        return $this->executeMasterQuery(

            callback: fn() => Department::query()
                ->where('organization_id', $organizationId)
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('department_name')
                ->get(),

            errorMessage: 'Unable to load Departments By Organization.'

        );
    }

    /**
     * Get Active Roles.
     */
    public function getRoles(): Collection
    {
        return $this->executeMasterQuery(

            callback: fn() => Role::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('role_name')
                ->get(),

            errorMessage: 'Unable to load Roles.'

        );
    }

    /**
     * Get Role By Id.
     */
    public function getRole(int $id)
    {
        return $this->executeMasterQuery(

            callback: fn() => Role::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->findOrFail($id),

            notFoundMessage: 'Role not found.',

            errorMessage: 'Unable to load Role.'

        );
    }

    /**
     * Get Active Port Categories.
     */
    public function getPortCategories(): Collection
    {
        return $this->executeMasterQuery(

            callback: fn() => PortCategory::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('name')
                ->get(),

            errorMessage: 'Unable to load Port Categories.'

        );
    }

    /**
     * Get Port Category By Id.
     */
    public function getPortCategory(int $id)
    {
        return $this->executeMasterQuery(

            callback: fn() => PortCategory::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->findOrFail($id),

            notFoundMessage: 'Port Category not found.',

            errorMessage: 'Unable to load Port Category.'

        );
    }

    /**
     * Get Active States.
     */
    public function getStates(): Collection
    {
        return $this->executeMasterQuery(

            callback: fn() => State::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('state_name')
                ->get(),

            errorMessage: 'Unable to load States.'

        );
    }

    /**
     * Get State By Id.
     */
    public function getState(int $id)
    {
        return $this->executeMasterQuery(

            callback: fn() => State::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->findOrFail($id),

            notFoundMessage: 'State not found.',

            errorMessage: 'Unable to load State.'

        );
    }

    /**
     * Get Active State Boards.
     */
    public function getStateBoards(): Collection
    {
        return $this->executeMasterQuery(

            callback: fn() => StateBoard::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('board_name')
                ->get(),

            errorMessage: 'Unable to load State Boards.'

        );
    }

    /**
     * Get State Board By Id.
     */
    public function getStateBoard(int $id)
    {
        return $this->executeMasterQuery(

            callback: fn() => StateBoard::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->findOrFail($id),

            notFoundMessage: 'State Board not found.',

            errorMessage: 'Unable to load State Board.'

        );
    }

    /**
     * Get State Boards By State.
     */
    public function getStateBoardsByState(
        int $stateId
    ) {
        return $this->executeMasterQuery(

            callback: fn() => StateBoard::query()
                ->where('state_id', $stateId)
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('board_name')
                ->get(),

            errorMessage: 'Unable to load State Boards By State.'

        );
    }

    /**
     * Get Active Ports.
     */
    public function getPorts(): Collection
    {
        return $this->executeMasterQuery(

            callback: fn() => Port::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('port_name')
                ->get(),

            errorMessage: 'Unable to load Ports.'

        );
    }

    /**
     * Get Port By Id.
     */
    public function getPort(int $id)
    {
        return $this->executeMasterQuery(

            callback: fn() => Port::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->findOrFail($id),

            notFoundMessage: 'Port not found.',

            errorMessage: 'Unable to load Port.'

        );
    }

    /**
     * Get Ports By Port Category.
     *
     * Major Port Flow
     */
    public function getPortsByCategory(
        int $portTypeId
    ) {
        return $this->executeMasterQuery(

            callback: fn() => Port::query()
                ->where('port_type_id', $portTypeId)
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('port_name')
                ->get(),

            errorMessage: 'Unable to load Ports By Port Category.'

        );
    }

    /**
     * Get Ports By State Board.
     *
     * Non Major Port Flow
     */
    public function getPortsByStateBoard(
        int $stateBoardId
    ) {
        return $this->executeMasterQuery(

            callback: fn() => Port::query()
                ->where('state_board_id', $stateBoardId)
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('port_name')
                ->get(),

            errorMessage: 'Unable to load Ports By State Board.'

        );
    }

    /**
     * Get Active Reporting Officers.
     */
    public function getReportingOfficers(): Collection
    {
        return $this->executeMasterQuery(

            callback: fn() => User::query()
                ->where('status', true)
                ->where('is_deleted', false)
                ->orderBy('name')
                ->get(),

            errorMessage: 'Unable to load Reporting Officers.'

        );
    }
}
