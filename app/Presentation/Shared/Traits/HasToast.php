<?php

namespace App\Presentation\Shared\Traits;

use Illuminate\Support\Facades\Session;

trait HasToast
{
    public function toastSuccess($content, $duration = 5000)
    {
        $this->add($content, 'success', $duration);
    }

    public function toastError($content, $duration = 5000)
    {
        $this->add($content, 'error', $duration);
    }

    public function toastWarning($content, $duration = 5000)
    {
        $this->add($content, 'warning', $duration);
    }

    public function toastInfo($content, $duration = 5000)
    {
        $this->add($content, 'info', $duration);
    }
    
    private function add($content, $type = 'success', $duration = 5000)
    {
        Session::flash('notify', [
            'content' => $content,
            'type' => $type,
            'duration' => $duration,
        ]);
    }
}
