<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $savedMessage = "Successfully Saved.";
    public $savedFailed = "Failed to save.";
    public $updatedMessage = "Successfully Updated.";
    public $deletedMessage = "Successfully Deleted.";
    public $failedToUpdateMessage = "Failed to update";
    public $notPermittedMessage = "Not permitted for you";
    public $disabledMessage = "Successfully disabled";
    public $enabledMessage = "Successfully enabled";
    public $cannotDisableMessage = "Cannot disable";
}
