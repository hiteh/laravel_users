<?php

namespace App\Abstracts;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

abstract class CrudableRepository
{

    /**
     * Data model.
     *
     * @var Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * Current CRUD operation output.
     *
     * @var array;
     */
    protected $instance = [];

    /**
     * The users repository instance.
     *
     * @var array;
     */
    protected $message = ['info' => 'messages.no_operation'];

    
    public function __construct( $model )
    {
        $this->model = $model;
    }
    /**
     * Checks if repository is empty.
     *
     * @return bool
     */
    public function hasAny()
    {
        return $this->model->first() ? true : false;
    }
    
    /**
     *  Default text.
     *
     * @param string $id
     * @return mixed
     */
    protected function fetch( $id = null )
    {
        return isset( $id ) ? $this->model->all()->find( $id ) : $this->model->all();
    }
    
    /**
     *  Default text
     *
     * @param array $data
     * @return mixed $instance
     */
    public function response()
    {
        return $this->message;
    }

    /**
     * Create instance.
     *
     * @param array $data
     * @return mixed $instance
     */
    public function create( array $data )
    {
        try
        {
            DB::transaction( function() use ( $data )
                {
                    $data = $this->createBefore( $data );
                    $this->instance = $this->model->create( $data );
                    $data = $this->createAfter( $data );
                    $this->instance->save();
                    $this->message = [ 'success' => $this->instance ];
                }
            );
            return $this->instance;
        }
        catch ( \Exception $e )
        {
            report( $e );
            $this->message = ['error' => __( 'messages.unexpected_error_msg' )];
            return;
        }

    }

    /**
     * Callback before instance is created.
     *
     * @param array $data
     * @param mixed $instance
     * @return mixed $instance
     */
    protected function createBefore( $data )
    {
        return $data;
    }

    /**
     * Callback before after instance is created.
     *
     * @param array $data
     * @param mixed $instance
     * @return mixed $instance
     */
    protected function createAfter( $data )
    {
        return $data;
    }

    /**
     * Get instance by id.
     *
     * @param string $id
     * @return App\Model
     */
    public function read( string $id = null )
    {
        try
        {
            if ( isset( $id ) )
            {   
                $this->instance = $this->fetch( $id );
                $this->readIdAfter();
                $this->message = null !== $this->instance ? [ 'success' => $this->instance ] : [ 'warning' => __( 'messages.object_not_found' ) ];
            }
            else
            {
                $this->instance = $this->fetch();
                $this->readAfter();
                $this->message = null !== $this->instance ? [ 'success' => $this->instance ] : [ 'warning' => __( 'messages.objects_not_found' ) ];
                return $this->instance;
            }
        }
        catch( \Exception $e )
        {
            report( $e );
            $this->message = ['error' => __( 'messages.unexpected_error_msg' )];
            return;
        }
    }

    /**
     * Callback after read.
     *
     * @param mixed $instance
     * @return mixed $instance
     */
    protected function readAfter()
    {
        return $this->instance;
    }

    /**
     * Callback after read by id.
     *
     * @param mixed $instance
     * @return mixed $instance
     */
    protected function readIdAfter()
    {
        return $this->instance;
    }

    /**
     * Update instance.
     *
     * @param array $data
     * @param string $id
     * @return mixed $instance
     */
    public function update( array $data, string $id )
    {
        try
        {
            $this->instance = $this->fetch( $id );
            
            if( null !== $this->instance )
            {
                DB::transaction( function() use ( $data )
                    {
                        $data = array_filter( $data );
                        $data = $this->updateBefore( $data );
                        $this->instance->update( $data );
                        $data = $this->updateAfter( $data );
                        $this->instance->save();
                        $this->message = [ 'success' => $this->instance ];
                    }
                );
                return $this->instance;
            }
            else
            {
                $this->message = ['warning' => __( 'messages.object_not_found_msg' )];
                return;
            }
        }
        catch ( \Exception $e )
        {
            report( $e );
            $this->message = ['error' => __( 'messages.unexpected_error_msg' )];
            return;
        }
    }

    /**
     * Callback before before instance is updated.
     *
     * @param array $data
     * @param mixed $instance
     * @return mixed $instance
     */
    protected function updateBefore( $data )
    {
        return $data;
    }

    /**
     * Callback before after instance is updated.
     *
     * @param array $data
     * @param mixed $instance
     * @return mixed $instance
     */
    protected function updateAfter( $data )
    {
        return $data;
    }

    /**
     * Delete instance.
     *
     * @param string $id
     * @return App\User
     */
    public function delete( string $id )
    {
        try 
        {
            $this->instance = $this->fetch( $id );
            
            if( null !== $this->instance )
            {
                DB::transaction( function() use ( $id )
                    {   
                        $this->deleteBefore( $id );
                        $this->instance->delete();
                        $this->deleteAfter( $id );
                        $this->message = ['success' => $this->instance];
                    }
                );
                return $this->instance;
            }
            else
            {
                $this->message = ['warning' => __( 'messages.object_not_found_msg' )];
                return;
            }
        }
        catch ( \Exception $e )
        {
            report( $e );
            $this->message = ['error' => __( 'messages.unexpected_error_msg' )];
            return;
        }
    }

    /**
     * Callback before before instance is deleted.
     *
     * @param string $id
     * @return mixed $instance
     */
    protected function deleteBefore( $id )
    {
        return $id;
    }

    /**
     * Callback before after instance is deleted.
     *
     * @param array $data
     * @param mixed $instance
     * @return mixed $instance
     */
    protected function deleteAfter( $id )
    {
        return $id;
    }
}