<?php

namespace App\Models;

use Carbon\Carbon;
use BaconQrCode\Writer;
use Endroid\QrCode\QrCode;
use App\Models\Inscription;
use Illuminate\Support\Facades\DB;
use BaconQrCode\Renderer\Image\Png;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Mail;
use Endroid\QrCode\Encoding\Encoding;
use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Renderer\ImageRenderer;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\PhoneNumber;
use BaconQrCode\Renderer\Image\GDImageBackEnd;   // si besoin PNG
use BaconQrCode\Renderer\Image\ImagickImageBackEnd; 
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class Helper_function extends Model
{
    //

    //Send Mail
    public static function send_mail($modelmail,$email){
        $resul = Mail::to($email)->send($modelmail);
        return $resul;
    }
    

    public static function formatBirthdate($birthdate)
    {
        if (empty($birthdate)) {
            return null;
        }

        // Si tu sais que la saisie est en d/m/Y => utilise createFromFormat
        try {
            $date = Carbon::createFromFormat('d/m/Y', $birthdate);
        } catch (\Exception $e) {
            // fallback : essayer quelques formats courants
            $formats = ['Y-m-d', 'd-m-Y', 'm/d/Y'];
            $date = null;
            foreach ($formats as $fmt) {
                try {
                    $date = Carbon::createFromFormat($fmt, $birthdate);
                    break;
                } catch (\Exception $e2) {
                    // ignorer
                }
            }
            // dernier recours : strtotime (si il ne retourne pas false)
            if (!$date) {
                $ts = strtotime($birthdate);
                if ($ts !== false) {
                    $date = Carbon::createFromTimestamp($ts);
                }
            }
        }

        return $date ? $date->format('d/m/Y') : null;
    }

    //phone
    public static function phone ($phone){

        /*validate phone*/
            /*retire le +*/
            $tel = new PhoneNumber($phone, 'CG');
            $phone = ltrim($tel,'+');
            /*fin*/
        /*fin*/

        return $phone;
    }

    public static function qrSvg($text)
    {
      // URL de génération du QR code
        $url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($text);

        // Chemin où sauvegarder le fichier dans public/
        $filename = $text.'.png';
        $path = public_path('qrcode/'.$filename);

        // Récupérer l'image depuis l'API
        $image = file_get_contents($url);

        // Sauvegarder dans public/
        file_put_contents($path, $image);

        // Passer le chemin relatif à la vue
        return 'qrcode/'.$filename;
    }


    public static function UniqueInscription(){
          
        $count = Inscription::count();
        $next  = $count + 1;

        do {
            $numero = str_pad($next, 3, '0', STR_PAD_LEFT);
            $exists = Inscription::where('code', $numero)->exists();

            if ($exists) {
                $next++;
            } else {
                break;
            }
        } while (true);

       return $numero;
    }
}
