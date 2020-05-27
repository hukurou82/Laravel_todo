<?php

use Illuminate\Support\Facades\Route;
use App\Book;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $books = Book::all(); //Book.phpに書籍データを全て送る
    return view('books', ['books' => $books]); //二つ目の引数でbooksという変数に$booksを入れる。'books' =>はBladeテンプレートで参照する変数名
})->middleware('auth');//->middleware('auth')を書くとトップページがログイン画面となりログインしなければ中を見れなくなる

Route::post('/book', function (Request $request) { //ホームから入手されたデータを遷移先の/bookに送る記述
    $validator = Validator::make($request->all(), [ //Validator::makeメソッドを使い、データを取ってきて入っているかチェック
        'name' => 'required|max:255', //name属性に条件が入っているか。タイトル（required）が入っているか、文字数が255文字以内か
    ]);

    //空文字で追加したらそのままスルーする記述
    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    $book = new Book; //オブジェクトの作成
    $book->title = $request->name; //$bookのtitleには$validator = Validator::make($request->all()でとってきたtitleを入れる
    $book->save(); //saveメソッドで保存

    return redirect('/'); //作った情報をreturnする。redirect（リダイレクト）としてトップページに戻す
});

Route::delete('/book/{book}', function(Book $book){//book{book}はbookのIDが返ってくる。Bookにbook{book}で取ってきたBookIdを渡している。
    $book->delete();//deleteはモデルに定義されているメソッド

    return redirect('/');//処理が終わったらリダイレクトでトップ画面に戻る
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
