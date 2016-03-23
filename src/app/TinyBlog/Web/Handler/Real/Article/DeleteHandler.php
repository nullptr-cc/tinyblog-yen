<?php

namespace TinyBlog\Web\Handler\Real\Article;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Web\Handler\Base\AjaxHandler;
use TinyBlog\Web\RequestData\ArticleDeleteData;

class DeleteHandler extends AjaxHandler
{
    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        $data = ArticleDeleteData::createFromRequest($request);
        $finder = $this->domain_registry->getArticleFinder();
        $editor = $this->domain_registry->getArticleEditor();

        try {
            $article = $finder->getArticle($data->getArticleId());
            $editor->deleteArticle($article);
        } catch (\InvalidArgumentException $ex) {
            return $this->badParams(['msg' => 'invalid article id']);
        } catch (\Exception $ex) {
            return $this->error(['msg' => 'Something wrong: ' . $ex->getMessage()]);
        };

        return $this->ok([
            'redirect_url' => $this->url_builder->buildMainPageUrl()
        ]);
    }
}
