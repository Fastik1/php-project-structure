<?

require_once __DIR__ . '/vendor/autoload.php';

use app\components\DB;
use app\components\Environments;

Environments::load(__DIR__);
DB::getConnetion();