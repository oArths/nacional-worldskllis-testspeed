<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Credit;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Review;
use App\Models\ReviewEvaluation;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Lexer\TokenEmulator\ReverseEmulator;

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
    public function ListReviews(Request $parms)
    {
        $userId = $parms->auth['user'];
        $User = User::where('email', $userId)->first();

        $movieId = $parms->route('movieId') ?? $error[] = ['message' => 'Invalid movie id'];
        $page = $parms->page ?? 1;
        $pagesize = $parms->pageSize ?? 10;
        $sortBy = $parms->sortBy ?? 'desc';
        $sortDir = $parms->sortDir ?? 'stars';
        $offset = ($page - 1) * $pagesize;


        // $revire = Review::whereYear('createdAt', date('Y'))->get();
        // return $revire;
        $Review = Review::with(['user', 'reviewevaluation' => function ($query) use ($User) {
            $query->where('userId', $User->id);
        }])->where('movieId', $movieId)->orderBy($sortBy, $sortDir)->get();


        $a = [];
        $a[] = $Review->map(function ($Review) {

            $reviewEvaluitoion = $Review->reviewevaluation;


            $myevalution  = $reviewEvaluitoion->isNotEmpty() ? $myevalution = $reviewEvaluitoion->first()->positive : NULL;
            $CREATE = \Carbon\Carbon::parse($Review->createdAt)->format('d/m/Y H:i');
            return [
                'id' => $Review->id,
                'username' => $Review->user->username,
                'content' => $Review->content,
                'stars' => $Review->stars,
                'createAt' => $CREATE,
                'myEvaluation' => $myevalution
            ];
        });

        $pagination = array_slice($a, $offset, $pagesize);
        return res($pagination[0], 200);
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
                'role' => $credit->role->title,
                'photoUrl' => $this->url . $credit->artist->photoUrl,
                'singlePageUrl' => $this->urlArtist . $credit->artist->id,
            ];
        });
        $movie['Credits'] = $A;


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
                'singlePageUrl' => $this->urlMovie . $credit->movie->id,
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
        $id = $parms->route('id') ?? $error[] = ['message' => "Could not find any file with the id $parms->id"];


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
    public function CreateReviwe(Request $request)
    {

        $userId = $request->auth['user'];
        $User = User::where('email', $userId)->first();

        $error = [];

        $stars = $request->query('stars') ?? $error[] = ['stars' => 'é necessario informar o a quiantiodade de estrelas'];
        $movieId = $request->route('movieId') ?? $error[] = ['movieId' => 'é necessario informar o id do filme'];
        $content = $request->query('content') ?? null;

        if ($stars > 10 || $stars <= 0) {
            $error[] = ['stars' => 'as estrelas devem ser entre 10 e 1'];
        }

        if ($error) {
            res(['messege' => 'Invalid properties', 'error' => $error], 422);
        }

        $movie = Movie::find($movieId);
        if (!$movie) {
            return  res(['messege' => 'invalid movie Id'], 400);
        }

        $rEVIEW = Review::where('userId', $User->id)->where('movieId', $movie->id)->first();
        $revieConten = [
            'content' => $content,
            'stars' => $stars,

        ];

        if ($rEVIEW) {
            $rEVIEW->update($revieConten);
            return res(['message' => 'Review has been successfully updated'], 200);
        } else {
            $revieConten['movieId'] = $movie->id;
            $revieConten['userId'] = $User->id;
            Review::create($revieConten);
            return res(['message' => 'Review has been successfully created'], 200);
        }
    }
    public function DeleteReviwe(Request $parms)
    {
        $error = [];

        $userId = $parms->auth['user'];
        $User = User::where('email', $userId)->first();

        $movieId = $parms->route('movieId') ?? $error[] = ['Invalid movie ide'];

        if ($error) {
            res(['messege' => $error], 422);
        }
        $movie = Movie::find($movieId);
        if (!$movie) {
            return  res(['messege' => 'invalid movie Id'], 404);
        }
        $reviewUser = Review::where('userId', $User->id)->where('movieId', $movie->id)->first();
        if (!$reviewUser) {
            return  res(['messege' => 'haven’t published a review to this movie'], 404);
        }
        $reviewUser->delete();
        return  res([], 204);
    }
    public function DeleteReviweevaluations(Request $parms)
    {
        $error = [];

        $userId = $parms->auth['user'];
        $User = User::where('email', $userId)->first();

        $reviewId = $parms->route('reviewId') ?? $error[] = ['Invalid review id'];

        if ($error) {
            res(['messege' => $error], 422);
        }
        $reviewId = ReviewEvaluation::find($reviewId);
        if (!$reviewId) {
            return  res(['messege' => 'invalid review Id'], 404);
        }
        $reviewUser = ReviewEvaluation::where('userId', $User->id)->where('reviewId', $reviewId->id)->first();
        if (!$reviewUser) {
            return  res(['messege' => 'haven’t published a review to this movie'], 404);
        }
        $reviewUser->delete();
        return  res([], 204);
    }
    public function CreateReviweEvalatiuon(Request $request)
    {

        $userId = $request->auth['user'];
        $User = User::where('email', $userId)->first();

        $error = [];
        $positive = $request->query('positive') ?? $error[] = ['positive' => 'é necessario informar o a quiantiodade de positive'];
        $reviewId = $request->route('reviewId') ?? $error[] = ['reviewId' => 'é necessario informar o id da review'];

        if ($positive !== 1 && $positive !== 0) {
            $error[] = ['positive' => 'o tipomde avaliação deve ser entre 0 e 1'];
        }

        if ($error) {
            res(['messege' => 'Invalid properties', 'error' => $error], 422);
        }

        $review = Review::find($reviewId);
        if (!$review) {
            return  res(['messege' => 'invalid review Id'], 400);
        }

        if ($review->userId === $User->id) {
            return  res(['messege' => 'The user cant evaluate his own review'], 400);
        }
        $content = [
            'reviewId' => $reviewId,
            'positive' => intval($positive),
        ];
        $EXIST = ReviewEvaluation::where('userId', $User->id)->where('reviewId', $reviewId)->first();
        if ($EXIST) {
            $EXIST->update($content);
            return res(['message' => 'Review evaluation has been successfully updated'], 200);
        } else {
            $content['userId'] = "$User->id";
            ReviewEvaluation::create($content);
            return res(['message' => 'Review evaluation has been successfully created'], 200);
        }
    }
}
