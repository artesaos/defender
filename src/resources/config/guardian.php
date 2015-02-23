<?php

/**
 * Guardian - Laravel 5 ACL Package
 * Author: PHPArtisans
 */
return [

	/*
	 * Default Role model used by Guardian.
	 */
	'role_model' => 'Artisans\Guardian\Role',

	/*
	 * Default Permission model used by Guardian.
	 */
	'permission_model' => 'Artisans\Guardian\Permission',

	/*
	 * Roles table name
	 */
	'role_table' => 'roles',

	/*
	 *
	 */
	'role_key' => 'role_id',

	/*
	 * Permissions table name
	 */
	'permission_table' => 'permissions',

	/*
	 *
	 */
	'permission_key' => 'permission_id',

	/*
	 * Pivot table for roles and users
	 */
	'role_user_table' => 'role_user',

	/*
	 * Pivot table for permissions and roles
	 */
	'permission_role_table' => 'permission_role',

	/*
	 * Pivot table for permissions and users
	 */
	'permission_user_table' => 'permission_user'

];