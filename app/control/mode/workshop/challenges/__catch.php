<?php

class WorkshopModeController extends JCatchControl
{
    public function Handle($request)
    {
        // This gives complete request path
        $request = jf::$BaseRequest;

        return $this->Present();
    }

    private function getRelativePath($request)
    {
        $presentDir = basename(dirname(__FILE__));
        return substr($request, (strpos($request, $presentDir) + strlen($presentDir) + 1));
    }
}
