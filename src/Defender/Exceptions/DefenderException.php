<?php  namespace Artesaos\Defender\Exceptions;

use Exception;

abstract class DefenderException extends Exception {}
class RoleExistsException extends  DefenderException {}
class PermissionExistsException extends DefenderException {}