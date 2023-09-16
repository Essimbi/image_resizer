<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ResizerController extends Controller
{
    /**
     * Fonction de redimensionnement d'image
     *
     * @param resquest
     *
     * $request est un objet contenant le corsp de la requête (formulaire), celle-ci contient un champ image qui est une image
     *
     */
    public function resizeImage(Request $request)
    {
        try {
            // Récupération de l'image du formulaire pour traitement.
            $image = $request->file('image');

            // Récupération de l'extension de l'image
            $filename = time() . '.' . $image->getClientOriginalExtension();

            // Copie de l'image sur le disque du serveur pour traitement
            $image->move(public_path('images'), $filename);

            // Redimensionnement de l'image et sauvegarde sur le disque du serveur
            $img = Image::make(public_path('images/' . $filename));
            $img->resize(200, 200);
            $img->save(public_path('images/thumb/' . $filename));

            // Suppression de l'original
            unlink(public_path('images/' . $filename));
            // return response()->json(["message" => "Image redimensionnée"], 200);
            return response()->file(public_path('images/thumb/' . $filename));
        } catch (\Throwable $th) {
            return response()->json(["error" => $th->getMessage()], 500);
        }
    }
}
