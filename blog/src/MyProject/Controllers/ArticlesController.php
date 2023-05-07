<?php

namespace MyProject\Controllers;

use MyProject\Models\Articles\Article;
use MyProject\Models\Users\User;
use MyProject\View\View;
use MyProject\Exceptions\NotFoundException;

class ArticlesController
{
    /** @var View */
    private $view;

    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
    }

    public function view(int $articleId)
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }

        $this->view->renderHtml('articles/view.php', [
            'article' => $article
        ]);
    }

    public function edit($articleId): void
    {
        /** @var Article $article */
        $article = Article::getById($articleId);
        
        if ($article == null) {
            throw new NotFoundException();
        }

        $article->setName('новое название статьи');
        $article->setText('новый текст статьи');

        $article->save();
    }

    public function add(): void 
    {
        $author = User::getById(1);

        $article = new Article();
        $article->setAuthor($author);
        $article->setName('нью название статьи');
        $article->setText('нью текст');

        $article->save();

        var_dump($article);
    }

    public function delete(int $id)
    {
        $article = Article::getById($id);

        if ($article !== null) {
            $article->delete();
            $this->view->renderHtml('articles/delete.php');
        } else {
            $this->view->renderHtml('errors/notFound.php',[], 404);
        }
    }
}