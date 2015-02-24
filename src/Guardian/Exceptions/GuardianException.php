<?php  namespace Artesaos\Guardian\Exceptions;

use Exception;

abstract class GuardianException extends Exception {}
class RoleExistsException extends  GuardianException {}
class PermissionExistsException extends GuardianException {}