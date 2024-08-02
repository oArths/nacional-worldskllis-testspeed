<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Credit;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;

class MovieController extends Controller

{
    public $url;
    public $urlMovie;
    public $urlArtist;
    public function __construct()
    {
        $this->urlMovie = 'http://127.0.0.1:8000/api/v1/movies/';
        $this->urlArtist = 'http://127.0.0.1:8000/api/v1/artist/';
        $this->url = env('URL');
    }
    public function getMovies(Request $parms)
    {

        $page = $parms->page ?? 1;
        $pagesize = $parms->pageSize ?? 4;
        $sortBy = $parms->sortBy ?? 'desc';
        $sortDir = $parms->sortDir ?? 'releseDate';
        $offset = ($page - 1) * $pagesize;

        $movie = Movie::orderBy($sortBy, $sortDir)->get();
        $a = [];
        $a[] = $movie->map(function ($movie) {
            return [
                'id' => $movie->id,
                'title' => $movie->title,
                'duration' => $movie->durationMinutes,
                'releseDate' => $movie->releaseDate,
                'posterUrl' => $this->url . $movie->posterUrl,
                'singlePageUrl' => $this->urlMovie . $movie->singlePageUrl,
            ];
        });

        $pagination = array_slice($a, $offset, $pagesize);
        return res($pagination, 200);
    }
    public function getIdMovies(Request $parms)
    {
        $error = [];

        $id = $parms->id ?? $error[] = ['message' => 'invalid movie id'];

        $movie = Movie::find($id);
        $movie = [
            'title' => $movie->title,
            'synopsis' => $movie->synopsis,
            'duration' => $movie->durationMinutes,
            'releseDate' => $movie->releseDate,
            'posterUrl' => $this->url . $movie->posterUrl,
            'trailerUrl' => $this->url . $movie->trailerUrl,
        ];

        if (!$movie) {
            $error[] = ['message' => 'invalid movie id'];
        }

        if ($error) {
            return res($error, 404);
        }
        $credit = Credit::where('movieId', $id)->with(['artist', 'Role'])->get();

        $A = $credit->map(function ($credit) {
            return [
                'artistId' => $credit->artist->id,
                'name' => $credit->artist->name,
                'photoUrl' => $this->url . $credit->artist->photoUrl,
                'singlePageUrl' => $this->urlArtist . $credit->artist->id,
                'role' => $credit->role->title
            ];
        });
        $movie['Credit'] = $A;


        return res($movie, 200);
    }
    public function getIdArtist(Request $parms)
    {
        $error = [];

        $id = $parms->id ?? $error[] = ['message' => 'invalid artist  id'];

        $Artist = Artist::find($id);

        if (!$Artist) {
            $error[] = ['message' => 'invalid artist  id'];
        }

        if ($error) {
            return res($error, 404);
        }
        $NewArtist =
            [
                'name' => $Artist->name,
                'birthday' => $Artist->birthday,
                'biography' => $Artist->biography,
                'photoUrl' => $this->url . $Artist->photoUrl,
            ];

        $credit = Credit::where('artistId', $id)->with(['movie'])->get();

        $A = $credit->map(function ($credit) {
            return [
                'Id' => $credit->movie->id,
                'title' => $credit->movie->title,
                'durationMinutes' => $credit->movie->durationMinutes,
                'releaseDate' => $credit->movie->releaseDate,
                'releaseDate' => $credit->movie->releaseDate,
                'posterUrl' => $this->url . $credit->movie->posterUrl,
                'trailerUrl' => $this->url . $credit->movie->trailerUrl,
            ];
        });
        $NewArtist['movies'] = $A;


        return res($NewArtist, 200);
    }
    public function getArtist(Request $parms)
    {

        $page = $parms->page ?? 1;
        $pagesize = $parms->pageSize ?? 10;
        $sortDir = $parms->sortDir ?? 'asc';
        $offset = ($page - 1) * $pagesize;

        $artist = Artist::orderBy('name', $sortDir)->get();
        $a[] = $artist->map(function ($artist) {
            return [
                'Id' => $artist->id,
                'name' => $artist->name,
                'photoUrl' => $this->urlMovie . $artist->id,
                'singlePageUrl' => $this->urlArtist . $artist->id,
            ];
        });
        $pagination = array_slice($a, $offset, $pagesize);
        return res($pagination, 200);
    }
    public function getGenere(Request $parms)
    {

        $page = $parms->page ?? 1;
        $pagesize = $parms->pageSize ?? 10;
        $sortDir = $parms->sortDir ?? 'desc';
        $offset = ($page - 1) * $pagesize;

        $movie = Genre::orderBy('title', $sortDir)->get();
        $a = $movie->toArray();
        $pagination = array_slice($a, $offset, $pagesize);
        return res($pagination, 200);
    }
    public function getMedia(Request $parms)
    {
        $error = [];
        $id = $parms->id ?? $error[] = ['message' => "Could not find any file with the id $parms->id"];


        $imge = [];
        Movie::select('posterUrl')->where('posterUrl', $id)->first() ? $imge = 'posterUrl' : null;
        Movie::select('trailerUrl')->where('trailerUrl', $id)->first() ? $imge = 'trailerUrl' :  null;
        Artist::select('photoUrl')->where('photoUrl', $id)->first() ? $imge = 'photoUrl' : null;

        if (!$imge) {
            $error[] = ['message' => "Could not find any file with the id $parms->id"];
        }

        if ($error) {
            return res($error, 200);
        }
        switch ($imge) {
            case 'trailerUrl':
                $typ = '.mp4';
                break;
            default:
                $typ = '.jpg';
                break;
        }

        $path = public_path("data\media\\{$id}") . $typ;
        $file = file_get_contents($path);
        // return $imge;
        // $typ = mime_content_type($path);
        // return response($file,200)->header('Content-type', $typ );
        $encode = base64_encode($file);
        return res(['content' => $encode], 200);
    }
}
