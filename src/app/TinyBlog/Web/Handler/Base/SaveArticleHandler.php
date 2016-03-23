<?php

namespace TinyBlog\Web\Handler\Base;

use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use TinyBlog\Core\Contract\IDependencyContainer;
use TinyBlog\Type\IArticleInitData;
use TinyBlog\Web\RequestData\ArticleData;

abstract class SaveArticleHandler extends AjaxHandler
{
    protected $validators;

    public function __construct(IDependencyContainer $dc)
    {
        parent::__construct($dc);
        $this->validators = $dc->getValidators();
    }

    abstract protected function saveArticle(IArticleInitData $data);

    public function getAllowedMethods()
    {
        return [IRequest::METHOD_POST];
    }

    public function handle(IServerRequest $request)
    {
        $auth_user = $this->getAuthUser();
        if (!$auth_user) {
            return $this->forbidden('Not authorized');
        };

        $data = ArticleData::createFromRequest($request);
        $validator = $this->validators->getArticleValidator();

        $vr = $validator->validate($data);
        if (!$vr->valid()) {
            return $this->badParams($vr->getErrors());
        };

        try {
            $article = $this->saveArticle($data);
        } catch (\Exception $ex) {
            return $this->error(['msg' => 'Try again later: ' . $ex->getMessage()]);
        };

        return $this->ok([
            'article_url' => $this->url_builder->buildArticleUrl($article)
        ]);
    }
}
