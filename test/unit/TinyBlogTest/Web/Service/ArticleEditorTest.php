<?php

namespace TinyBlogTest\Web\Service;

use TinyBlog\Article\Article;
use TinyBlog\Article\ArticleRepo;
use TinyBlog\Article\Content;
use TinyBlog\Tools\Contract\IMarkdownTransformer;
use TinyBlog\Tools\TeaserMaker;
use TinyBlog\User\User;
use TinyBlog\Web\RequestData\ArticleData;
use TinyBlog\Web\Service\ArticleEditor;
use DateTimeImmutable;
use Prophecy\Argument;

class ArticleEditorTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateArticle()
    {
        $repo = $this->prophesize(ArticleRepo::class);
        $ret_article = new Article([
            'id' => 42,
            'title' => 'Test title',
            'body' => new Content('Test body **foo** bar', 'Test body <b>foo</b> bar'),
            'teaser' => 'Test teaser',
            'created_at' => new DateTimeImmutable('@112233'),
            'author' => new User(['id' => 11])
        ]);
        $repo->insertArticle(Argument::that([$this, 'prpCheckNewArticle']))
             ->willReturn($ret_article);

        $mdt = $this->prophesize(IMarkdownTransformer::class);
        $mdt->toHtml('Test body **foo** bar')
            ->willReturn('Test body <b>foo</b> bar');

        $tm = $this->prophesize(TeaserMaker::class);
        $tm->makeTeaser('Test body <b>foo</b> bar')
           ->willReturn('Test teaser');

        $data = new ArticleData(0, 'Test title', 'Test body **foo** bar');
        $author = new User(['id' => 11]);
        $created = new DateTimeImmutable('@112233');

        $editor = new ArticleEditor($repo->reveal(), $mdt->reveal(), $tm->reveal());
        $result = $editor->createArticle($data, $author, $created);

        $this->assertInstanceOf(Article::class, $result);
        $this->assertEquals(42, $result->getId());
        $this->assertEquals('Test title', $result->getTitle());
        $this->assertEquals('Test body **foo** bar', $result->getBody()->getSource());
        $this->assertEquals('Test body <b>foo</b> bar', $result->getBody()->getHtml());
        $this->assertEquals(112233, $result->getCreatedAt()->getTimestamp());
        $this->assertEquals('Test teaser', $result->getTeaser());
        $this->assertEquals(11, $result->getAuthor()->getId());
    }

    public function prpCheckNewArticle(Article $article)
    {
        $this->assertNull($article->getId());
        $this->assertEquals('Test title', $article->getTitle());
        $this->assertEquals('Test body **foo** bar', $article->getBody()->getSource());
        $this->assertEquals('Test body <b>foo</b> bar', $article->getBody()->getHtml());
        $this->assertEquals(112233, $article->getCreatedAt()->getTimestamp());
        $this->assertEquals('Test teaser', $article->getTeaser());
        $this->assertEquals(11, $article->getAuthor()->getId());
        return true;
    }

    public function testUpdateArticle()
    {
        $repo = $this->prophesize(ArticleRepo::class);
        $repo->updateArticle(Argument::that([$this, 'prpCheckExistentArticle']))
             ->willReturnArgument(0);

        $mdt = $this->prophesize(IMarkdownTransformer::class);
        $mdt->toHtml('Test body **foo** bar. UPD.')
            ->willReturn('Test body <b>foo</b> bar. UPD.');

        $tm = $this->prophesize(TeaserMaker::class);
        $tm->makeTeaser('Test body <b>foo</b> bar. UPD.')
           ->willReturn('Test teaser (upd)');

        $article = new Article([
            'id' => 42,
            'title' => 'Test title',
            'body' => new Content('Test body **foo** bar', 'Test body <b>foo</b> bar'),
            'teaser' => 'Test teaser',
            'created_at' => new DateTimeImmutable('@112233'),
            'author' => new User(['id' => 11])
        ]);
        $data = new ArticleData(42, 'Test title [UPD]', 'Test body **foo** bar. UPD.');

        $editor = new ArticleEditor($repo->reveal(), $mdt->reveal(), $tm->reveal());
        $result = $editor->updateArticle($article, $data);

        $this->assertInstanceOf(Article::class, $result);
        $this->assertEquals(42, $result->getId());
        $this->assertEquals('Test title [UPD]', $result->getTitle());
        $this->assertEquals('Test body **foo** bar. UPD.', $result->getBody()->getSource());
        $this->assertEquals('Test body <b>foo</b> bar. UPD.', $result->getBody()->getHtml());
        $this->assertEquals(112233, $result->getCreatedAt()->getTimestamp());
        $this->assertEquals('Test teaser (upd)', $result->getTeaser());
        $this->assertEquals(11, $result->getAuthor()->getId());
    }

    public function prpCheckExistentArticle(Article $article)
    {
        $this->assertEquals(42, $article->getId());
        $this->assertEquals('Test title [UPD]', $article->getTitle());
        $this->assertEquals('Test body **foo** bar. UPD.', $article->getBody()->getSource());
        $this->assertEquals('Test body <b>foo</b> bar. UPD.', $article->getBody()->getHtml());
        $this->assertEquals(112233, $article->getCreatedAt()->getTimestamp());
        $this->assertEquals('Test teaser (upd)', $article->getTeaser());
        $this->assertEquals(11, $article->getAuthor()->getId());
        return true;
    }
}
