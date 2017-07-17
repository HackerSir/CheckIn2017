<?php

namespace App\Http\Controllers;

use DNS1D;
use DNS2D;
use File;

class CodePictureController extends Controller
{
    /**
     * QR Code
     *
     * @param $code
     * @return \Illuminate\Http\Response
     */
    public function qrcode($code)
    {
        $scanUrl = route('qrcode.scan', $code);

        $path = DNS2D::getBarcodePNGPath($scanUrl, 'QRCODE', 12, 12);
        $file = File::get($path);

        $headers = [
            'Content-Type'   => File::mimeType($path),
            'Content-Length' => File::size($path),
            'Cache-Control'  => 'max-age=86400',
        ];

        return \Response::make($file, 200, $headers);
    }

    /**
     * Barcode
     *
     * @param $code
     * @return \Illuminate\Http\Response
     */
    public function barcode($code)
    {
        $path = DNS1D::getBarcodePNGPath($code, 'C128B', 3, 90);
        $file = File::get($path);

        $headers = [
            'Content-Type'   => File::mimeType($path),
            'Content-Length' => File::size($path),
            'Cache-Control'  => 'max-age=86400',
        ];

        return \Response::make($file, 200, $headers);
    }
}
