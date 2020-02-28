<?php

namespace App\Repositories;

use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Traits\LengthAwarePaginable;
use App\Interfaces\UsersRepositoryInterface;
use App\Interfaces\RolesRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use App\Abstracts\CrudableRepository;
use Illuminate\Support\Facades\DB;

class UsersRepository extends CrudableRepository implements UsersRepositoryInterface
{

    use LengthAwarePaginable;

    /**
     * The roles repository instance.
     *
     * @var App/Repositories/RolesRepository;
     */
    protected $roles;

    /**
     * Create a new repository instance.
     *
     * @param App\User $model
     * @return void
     */
    public function __construct( User $model, RolesRepositoryInterface $roles )
    {
        $this->model = $model;
        $this->roles = $roles;
    }

    /**
     * Callback after users are extracted form database.
     *
     * @param App\User $user
     * @param array $data
     * @return App\User $user
     */
    protected function readAfter()
    {
        $this->instance = $this->instance->load('roles');
    }

    /**
     * Callback after single user is extracted form databse.
     *
     * @param App\User $user
     * @param array $data
     * @return App\User $user
     */
    protected function readIdAfter()
    {
        $this->instance = $this->instance->load('roles');
    }

    /**
     * Display extracted users pagineted.
     *
     * @param int $items_per_page
     * @param array $query
     * @param string $url
     * @param string $key
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginated( int $items_per_page, array $query, string $url, string $key )
    {
        $this->read();
        $message = $this->response();
        extract( $message );
        $users = isset( $success ) ? $success->keyBy( $key )->toArray() : [];
        return $this->paginate( $items_per_page, $users, $query, $url );
    }

    /**
     * Get all available user roles.
     *
     * @return Illuminate\Support\Collection
     */
    public function roles()
    {
        $this->roles->read();
        $message = $this->roles->response();
        extract( $message );

        return isset( $success ) ? $success->where( 'name','<>','root' ): collect();
    }
    
    /**
     * Set role.
     *
     * @param App\User $user
     * @param string $role
     * @return App\User $user
     */
    private function setRole( User $user, string $role )
    {
        $role = $this->model->roles()->getRelated()->where( 'name', $role )->first();
        $user->roles()->detach();
        $user->roles()->attach( $role );
        return $user;
    }

    /**
     * Update password.
     *
     * @param App\User $user
     * @param string $password
     * @return App\User $user
     */
    private function updatePassword( User $user, string $password )
    {
        $user->update( ['password' => Hash::make( $password )] );

        return $user;
    }

    /**
     * Update avatar.
     *
     * @param App\User $user
     * @param file $avatar
     * @return string $name
     */
    private function updateAvatar( User $user, $avatar )
    {
        $avatar->store('public');
        Storage::delete( 'public/'. $user->avatar );
        return $avatar->hashName();
    }
    
    /**
     * Callback before user instance is updated.
     *
     * @param App\User $user
     * @param array $data
     * @return App\User $user
     */
    protected function updateBefore( $data )
    {
        extract( $data );
        if( isset( $avatar ) )
        {
            $data['avatar'] = $this->updateAvatar( Auth::user(), $avatar );
        }

        return $data;
    }

    /**
     * Callback after user instance is updated.
     *
     * @param App\User $user
     * @param array $data
     * @return App\User $user
     */
    protected function updateAfter( $data )
    {
        $instance = $this->instance;
        extract( $data = array_filter( $data ) );
        isset( $role ) && ! $this->instance->hasRole( 'root' ) ? $this->setRole( $instance, $role ) : $instance;
        isset( $password ) ? $this->updatePassword( $instance, $password ) : $instance;

        return $instance;
    }
    /**
     * Callback before user instance is created.
     *
     * @param App\User $user
     * @param array $data
     * @return App\User $user
     */
    protected function createBefore( $data )
    {
        if( ! $this->hasAny() )
        {
            $data['role'] = 'root';
        }

        return $data;
    }

    /**
     * Callback after user instance is created.
     *
     * @param App\User $user
     * @param array $data
     * @return App\User $user
     */
    protected function createAfter( $data )
    {
        $instance = $this->instance;
        extract( $data = array_filter( $data ) );
        isset( $role ) && ! $this->instance->hasRole( 'root' ) ? $this->setRole( $instance, $role ) : $instance;
        isset( $password ) ? $this->updatePassword( $instance, $password ) : $instance; 

        return $instance;
    }

    /**
     * Callback before user is deleted.
     *
     * @param string $id
     * @return string $id
     */
    protected function deleteBefore( $id )
    {
        $this->fetch( $id )->roles()->detach();

        return $id;
    }
}