<?php

namespace App\Traits;

trait UploadFile
{
    public function insertFile($fileRequest)
    {
        if ($fileRequest->hasFile('photo')) {
            return $fileRequest->file('photo')->store('public');
        }
    }
}
